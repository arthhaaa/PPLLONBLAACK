<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use App\Models\DataProduk;
use App\Models\Pemesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodDays = (int) $request->query('periode', 30);
        $allowedPeriods = [7, 14, 30, 90, 365];

        if (! in_array($periodDays, $allowedPeriods, true)) {
            $periodDays = 30;
        }

        $startDate = now()->subDays($periodDays - 1)->startOfDay();
        $endDate = now()->endOfDay();
        $paidStatuses = ['sedang_diproses', 'siap_dikirim', 'selesai'];

        $paidOrders = Pemesanan::query()
            ->whereIn('status_transaksi', $paidStatuses)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalPenjualan = (clone $paidOrders)->sum('total_harga_produk');
        $totalPesanan = Pemesanan::whereBetween('created_at', [$startDate, $endDate])->count();
        $produkTerjual = (clone $paidOrders)->sum('total_produk');
        $biayaOperasional = BiayaOperasional::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('jumlah_biaya');
        $labaBersih = $totalPenjualan - $biayaOperasional;
        $pelangganBaru = User::where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $stokRendah = DataProduk::whereRaw('CAST(stok_produk AS UNSIGNED) <= 10')->count();

        $salesByDate = (clone $paidOrders)
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga_produk) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $orderByDate = Pemesanan::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $period = collect(range(0, $periodDays - 1))->map(fn ($day) => $startDate->copy()->addDays($day));
        $chartData = [
            'labels' => $period->map(fn (Carbon $date) => $date->translatedFormat('d M'))->values(),
            'penjualan' => $period->map(fn (Carbon $date) => (int) ($salesByDate[$date->toDateString()] ?? 0))->values(),
            'pesanan' => $period->map(fn (Carbon $date) => (int) ($orderByDate[$date->toDateString()] ?? 0))->values(),
        ];

        $statusSummary = Pemesanan::select('status_transaksi', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status_transaksi')
            ->orderByDesc('total')
            ->get();

        $topProducts = Pemesanan::query()
            ->whereIn('status_transaksi', $paidStatuses)
            ->whereBetween('pemesanan.created_at', [$startDate, $endDate])
            ->leftJoin('data_produk', 'pemesanan.id_produk', '=', 'data_produk.id_produk')
            ->selectRaw('pemesanan.id_produk, COALESCE(data_produk.nama_produk, pemesanan.nama_produk) as nama_produk, SUM(total_produk) as total_terjual, SUM(total_harga_produk) as total_omzet')
            ->groupBy('pemesanan.id_produk', 'data_produk.nama_produk', 'pemesanan.nama_produk')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        $lowStockProducts = DataProduk::whereRaw('CAST(stok_produk AS UNSIGNED) <= 10')
            ->orderByRaw('CAST(stok_produk AS UNSIGNED) ASC')
            ->take(5)
            ->get();

        $recentTransactions = Pemesanan::with('produk')
            ->latest()
            ->take(5)
            ->get();

        $recentCustomers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualan',
            'totalPesanan',
            'produkTerjual',
            'biayaOperasional',
            'labaBersih',
            'pelangganBaru',
            'stokRendah',
            'chartData',
            'statusSummary',
            'topProducts',
            'lowStockProducts',
            'recentTransactions',
            'recentCustomers',
            'periodDays',
            'allowedPeriods'
        ));
    }
}
