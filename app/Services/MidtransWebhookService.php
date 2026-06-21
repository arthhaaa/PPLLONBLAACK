<?php

namespace App\Services;

class MidtransWebhookService
{
    public function hasValidSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        if ($serverKey === '' || $orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signature);
    }

    public function statusUpdate(array $payload): ?array
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? 'accept');

        if ($transactionStatus === 'capture' && $fraudStatus !== 'accept') {
            return null;
        }

        return match ($transactionStatus) {
            'capture', 'settlement' => [
                'status_transaksi' => 'sedang_diproses',
                'dibayar_pada' => now(),
                'dibatalkan_pada' => null,
            ],
            'deny', 'cancel', 'expire' => [
                'status_transaksi' => 'dibatalkan',
                'dibatalkan_pada' => now(),
            ],
            'pending' => [
                'status_transaksi' => 'menunggu_pembayaran',
            ],
            default => null,
        };
    }
}
