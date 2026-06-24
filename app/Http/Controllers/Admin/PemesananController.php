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

    public function invoice(Pemesanan $pesanan)
    {
        $items = Pemesanan::with('produk')
            ->when(
                $pesanan->kode_transaksi,
                fn ($query) => $query->where('kode_transaksi', $pesanan->kode_transaksi),
                fn ($query) => $query->where('id_pesanan', $pesanan->id_pesanan)
            )
            ->oldest()
            ->get();

        abort_if($items->isEmpty(), 404);

        $first = $items->first();
        $transaction = [
            'kode_transaksi' => $first->kode_transaksi ?: 'ORDER-' . $first->id_pesanan,
            'status_transaksi' => $first->status_transaksi ?? 'menunggu_pembayaran',
            'metode_pembayaran' => $first->metode_pembayaran,
            'alamat_pengiriman' => $first->alamat_pengiriman,
            'catatan' => $first->catatan,
            'ongkir' => (int) ($first->ongkir ?? 0),
            'kurir' => $first->kurir,
            'layanan_kurir' => $first->layanan_kurir,
            'midtrans_order_id' => $first->midtrans_order_id,
            'midtrans_transaction_id' => $first->midtrans_transaction_id,
            'total_produk' => $items->sum('total_produk'),
            'subtotal_produk' => $items->sum('total_harga_produk'),
            'total_harga' => $items->sum('total_harga_produk') + (int) ($first->ongkir ?? 0),
            'jumlah_item' => $items->count(),
            'created_at' => $first->created_at,
        ];

        return view('invoices.receipt', [
            'items' => $items,
            'transaction' => $transaction,
            'customerName' => $first->username,
            'customerEmail' => null,
            'customerPhone' => null,
            'backUrl' => route('admin.pesanan.index'),
            'backLabel' => 'Kembali ke Pesanan',
        ]);
    }
}
