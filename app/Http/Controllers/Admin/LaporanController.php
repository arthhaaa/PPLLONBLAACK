<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $report = $this->buildReport($request);

        return view('admin.laporan.index', $report);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $report = $this->buildReport($request);
        $filename = 'laporan-keuangan-' . now()->format('Ymd-His') . '.xls';
        $path = $this->createExcelHtmlFile($this->exportRows($report));

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ])->deleteFileAfterSend(true);
    }

    private function exportRows(array $report): array
    {
        $rows = [
            ['Laporan Keuangan Admin'],
            ['Periode', $report['periodLabel']],
            [],
            ['Ringkasan', 'Nilai'],
            ['Laba Kotor', (int) $report['labaKotor']],
            ['Biaya Operasional', (int) $report['totalBiaya']],
            ['Laba Bersih', (int) $report['labaBersih']],
            ['Total Pesanan', (int) $report['totalPesanan']],
            ['Produk Terjual', (int) $report['totalProdukTerjual']],
            [],
            ['Laporan Per Bulan'],
            ['Bulan', 'Laba Kotor', 'Biaya Operasional', 'Laba Bersih', 'Pesanan'],
        ];

        foreach ($report['monthlyReports'] as $monthlyReport) {
            $rows[] = [
                $monthlyReport['label'],
                (int) $monthlyReport['laba_kotor'],
                (int) $monthlyReport['biaya'],
                (int) $monthlyReport['laba_bersih'],
                (int) $monthlyReport['pesanan'],
            ];
        }

        $rows[] = [];
        $rows[] = ['Transaksi Terbaru'];
        $rows[] = ['Kode', 'Customer', 'Produk', 'Status', 'Total', 'Tanggal'];

        foreach ($report['recentTransactions'] as $transaction) {
            $rows[] = [
                $transaction->kode_transaksi ?? 'TRX-' . $transaction->id_pesanan,
                $transaction->username,
                $transaction->produk?->nama_produk ?? $transaction->nama_produk,
                $transaction->status_transaksi,
                (int) $transaction->total_harga_produk,
                optional($transaction->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }

    private function createExcelHtmlFile(array $rows): string
    {
        $path = tempnam(storage_path('app'), 'laporan-');
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">'
            . '<style>table{border-collapse:collapse}td,th{border:1px solid #999;padding:6px}th{background:#eee}</style>'
            . '</head><body><table>';

        foreach ($rows as $row) {
            $html .= '<tr>';

            foreach ($row as $value) {
                $html .= '<td>' . $this->html($value) . '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</table></body></html>';
        file_put_contents($path, $html);

        return $path;
    }

    private function html(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function buildReport(Request $request): array
    {
        $periodDays = (int) $request->query('periode', 30);
        $allowedPeriods = [7, 14, 30, 90, 365];
        $selectedDate = $request->query('tanggal');

        if (! in_array($periodDays, $allowedPeriods, true)) {
            $periodDays = 30;
        }

        if (! $selectedDate || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = now()->toDateString();
        }

        $endDate = Carbon::createFromFormat('Y-m-d', $selectedDate)->endOfDay();
        $startDate = $endDate->copy()->subDays($periodDays - 1)->startOfDay();
        $periodLabel = $periodDays . ' hari terakhir sampai ' . $endDate->translatedFormat('d F Y');

        $paidStatuses = ['sedang_diproses', 'siap_dikirim', 'selesai'];

        $paidOrders = Pemesanan::whereIn('status_transaksi', $paidStatuses)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $labaKotor = (clone $paidOrders)->sum('total_harga_produk');
        $totalPenjualan = $labaKotor;
        $totalProdukTerjual = (clone $paidOrders)->sum('total_produk');
        $totalPesanan = Pemesanan::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalBiaya = BiayaOperasional::whereBetween('tanggal', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])->sum('jumlah_biaya');
        $labaBersih = $labaKotor - $totalBiaya;

        $salesByDate = (clone $paidOrders)
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga_produk) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $costByDate = BiayaOperasional::whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->selectRaw('tanggal, SUM(jumlah_biaya) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $chartDays = $startDate->diffInDays($endDate) + 1;
        $period = collect(range(0, $chartDays - 1))->map(fn ($day) => $startDate->copy()->addDays($day));
        $chartData = [
            'labels' => $period->map(fn (Carbon $date) => $date->translatedFormat('d M'))->values(),
            'penjualan' => $period->map(fn (Carbon $date) => (int) ($salesByDate[$date->toDateString()] ?? 0))->values(),
            'biaya' => $period->map(fn (Carbon $date) => (int) ($costByDate[$date->toDateString()] ?? 0))->values(),
            'laba_bersih' => $period->map(fn (Carbon $date) => (int) (($salesByDate[$date->toDateString()] ?? 0) - ($costByDate[$date->toDateString()] ?? 0)))->values(),
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
            ->orderByDesc('total_omzet')
            ->take(8)
            ->get();

        $recentTransactions = Pemesanan::with('produk')
            ->latest()
            ->take(10)
            ->get();

        $monthlyReports = $this->monthlyReports($paidStatuses);

        return compact(
            'periodDays',
            'allowedPeriods',
            'selectedDate',
            'periodLabel',
            'totalPenjualan',
            'labaKotor',
            'totalProdukTerjual',
            'totalPesanan',
            'totalBiaya',
            'labaBersih',
            'chartData',
            'statusSummary',
            'topProducts',
            'recentTransactions',
            'monthlyReports'
        );
    }

    private function monthlyReports(array $paidStatuses)
    {
        $months = collect(range(11, 1))->map(fn ($month) => now()->subMonths($month)->startOfMonth())
            ->push(now()->startOfMonth());

        $startDate = $months->first()->copy()->startOfMonth();
        $endDate = $months->last()->copy()->endOfMonth();

        $salesByMonth = Pemesanan::whereIn('status_transaksi', $paidStatuses)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, SUM(total_harga_produk) as total, COUNT(DISTINCT kode_transaksi) as pesanan")
            ->groupBy('bulan')
            ->get()
            ->keyBy('bulan');

        $costByMonth = BiayaOperasional::whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(jumlah_biaya) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return $months->map(function (Carbon $month) use ($salesByMonth, $costByMonth) {
            $key = $month->format('Y-m');
            $labaKotor = (int) optional($salesByMonth->get($key))->total;
            $biaya = (int) ($costByMonth[$key] ?? 0);

            return [
                'key' => $key,
                'label' => $month->translatedFormat('M Y'),
                'laba_kotor' => $labaKotor,
                'biaya' => $biaya,
                'laba_bersih' => $labaKotor - $biaya,
                'pesanan' => (int) optional($salesByMonth->get($key))->pesanan,
            ];
        })->reverse()->values();
    }
}
