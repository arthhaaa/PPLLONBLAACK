<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Services\MidtransWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransWebhookService $webhook): JsonResponse
    {
        $payload = $request->all();

        if (! $webhook->hasValidSignature($payload)) {
            Log::warning('Midtrans webhook rejected: invalid signature.', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $orderId = (string) $payload['order_id'];
        $updates = $webhook->statusUpdate($payload);

        if ($updates === null) {
            return response()->json(['message' => 'Notification acknowledged.']);
        }

        $updated = DB::transaction(function () use ($orderId, $payload, $updates): int {
            $orders = Pemesanan::where(function ($query) use ($orderId) {
                    $query->where('midtrans_order_id', $orderId)
                        ->orWhere('kode_transaksi', $orderId);
                })
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                return 0;
            }

            foreach ($orders as $order) {
                $status = $updates['status_transaksi'];

                if ($status === 'menunggu_pembayaran' && $order->status_transaksi !== 'menunggu_pembayaran') {
                    continue;
                }

                if ($status === 'dibatalkan' && ! $order->canBeModified()) {
                    continue;
                }

                $order->update(array_merge($updates, [
                    'midtrans_transaction_id' => $payload['transaction_id'] ?? $order->midtrans_transaction_id,
                    'payment_response' => $payload,
                ]));
            }

            return $orders->count();
        });

        if ($updated === 0) {
            Log::warning('Midtrans webhook order not found.', ['order_id' => $orderId]);

            return response()->json(['message' => 'Order not found.'], 404);
        }

        return response()->json([
            'message' => 'Notification processed.',
            'order_id' => $orderId,
            'status' => $updates['status_transaksi'],
        ]);
    }
}
