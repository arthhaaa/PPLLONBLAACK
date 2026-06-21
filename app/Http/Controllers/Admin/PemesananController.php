<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PemesananController extends Controller
{
    private const STATUS_FLOW = [
        'menunggu_pembayaran' => ['dibatalkan'],
        'sedang_diproses' => ['siap_dikirim'],
        'siap_dikirim' => ['selesai'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    public function index()
    {
        $orders = Pemesanan::with('produk')->latest()->paginate(12);

        return view('admin.pesanan.index', compact('orders'));
    }

    public function updateStatus(Request $request, Pemesanan $pesanan)
    {
        $request->validate([
            'status_transaksi' => ['required', Rule::in(array_keys(self::STATUS_FLOW))],
        ]);

        $newStatus = $request->status_transaksi;

        $updated = DB::transaction(function () use ($pesanan, $newStatus): bool {
            $orders = Pemesanan::query()
                ->when(
                    $pesanan->kode_transaksi,
                    fn ($query) => $query->where('kode_transaksi', $pesanan->kode_transaksi),
                    fn ($query) => $query->where('id_pesanan', $pesanan->id_pesanan)
                )
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                return false;
            }

            $currentStatus = $orders->first()->status_transaksi ?? 'menunggu_pembayaran';
            $allowedNextStatuses = self::STATUS_FLOW[$currentStatus] ?? [];

            if (! in_array($newStatus, $allowedNextStatuses, true)) {
                return false;
            }

            $updates = ['status_transaksi' => $newStatus];

            if ($newStatus === 'selesai') {
                $updates['dibayar_pada'] = $orders->first()->dibayar_pada ?? now();
            }

            if ($newStatus === 'dibatalkan') {
                $updates['dibatalkan_pada'] = now();
            }

            Pemesanan::query()
                ->whereIn('id_pesanan', $orders->pluck('id_pesanan'))
                ->update($updates);

            return true;
        });

        if (! $updated) {
            return back()->withErrors([
                'status_transaksi' => 'Status pesanan tidak bisa diubah ke tahap tersebut.',
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
