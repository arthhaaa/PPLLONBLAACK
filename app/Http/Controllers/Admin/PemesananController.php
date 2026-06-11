<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $orders = Pemesanan::with('produk')->latest()->paginate(12);

        return view('admin.pesanan.index', compact('orders'));
    }

    public function updateStatus(Request $request, Pemesanan $pesanan)
    {
        $request->validate([
            'status_transaksi' => 'required|in:menunggu_pembayaran,sedang_diproses,siap_dikirim,selesai,dibatalkan',
        ]);

        $query = Pemesanan::query();

        if ($pesanan->kode_transaksi) {
            $query->where('kode_transaksi', $pesanan->kode_transaksi);
        } else {
            $query->where('id_pesanan', $pesanan->id_pesanan);
        }

        $updates = ['status_transaksi' => $request->status_transaksi];

        if ($request->status_transaksi === 'selesai') {
            $updates['dibayar_pada'] = now();
        }

        if ($request->status_transaksi === 'dibatalkan') {
            $updates['dibatalkan_pada'] = now();
        }

        $query->update($updates);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
