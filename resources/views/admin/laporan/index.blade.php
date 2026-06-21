@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $formatStatus = fn ($status) => ucwords(str_replace('_', ' ', $status ?: 'Belum Ada'));
    $isProfit = $labaBersih >= 0;
@endphp

<div class="container-fluid px-4 pb-5">
    <div class="report-hero mb-4">
        <div>
            <span><i class="fa fa-chart-bar me-2"></i>Laporan Bisnis</span>
            <h2>Laporan Keuangan</h2>
            <p>Ringkasan {{ $periodLabel }} dari laba kotor, biaya operasional, laba bersih, dan laporan bulanan.</p>
        </div>
        <div class="report-actions">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="report-filter">
                <select name="periode" class="form-select" onchange="this.form.submit()">
                    @foreach($allowedPeriods as $period)
                        <option value="{{ $period }}" @selected($periodDays === $period)>
                            {{ $period }} Hari Terakhir
                        </option>
                    @endforeach
                </select>
                <input type="date" name="tanggal" value="{{ $selectedDate }}" class="form-control" onchange="this.form.submit()" aria-label="Pilih tanggal akhir laporan">
            </form>
            <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn report-export-btn">
                <i class="fa fa-file-excel me-2"></i>Export Laporan
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm report-metric">
                <div class="card-body">
                    <span>Penjualan</span>
                    <h3>{{ $formatRupiah($labaKotor) }}</h3>
                    <small>Penjualan produk terbayar</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm report-metric">
                <div class="card-body">
                    <span>Biaya Operasional</span>
                    <h3>{{ $formatRupiah($totalBiaya) }}</h3>
                    <small>Pengeluaran periode ini</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm report-metric {{ $isProfit ? 'is-positive' : 'is-attention' }}">
                <div class="card-body">
                    <span>{{ $isProfit ? 'Laba Bersih' : 'Biaya Belum Tertutup' }}</span>
                    <h3>{{ $formatRupiah(abs($labaBersih)) }}</h3>
                    <small>{{ $isProfit ? 'Keuntungan setelah biaya operasional' : 'Target omzet minimum untuk menutup biaya' }}</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm report-metric">
                <div class="card-body">
                    <span>Pesanan / Produk</span>
                    <h3>{{ number_format($totalPesanan, 0, ',', '.') }} / {{ number_format($totalProdukTerjual, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm report-panel h-100 report-chart-card">
                <div class="card-header bg-white border-0 py-4">
                    <div>
                        <h5 class="mb-1">Grafik Keuangan</h5>
                        <small class="text-muted">Perbandingan laba kotor dan biaya operasional.</small>
                    </div>
                </div>
                <div class="card-body report-chart-body">
                    <canvas id="reportChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm report-panel h-100">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Status Pesanan</h5>
                </div>
                <div class="card-body">
                    @forelse($statusSummary as $status)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span>{{ $formatStatus($status->status_transaksi) }}</span>
                            <strong>{{ number_format($status->total, 0, ',', '.') }}</strong>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4 mb-0">Belum ada pesanan pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm report-panel">
                <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Per Bulan</h5>
                    <span class="text-muted small">12 bulan terakhir</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Bulan</th>
                                    <th>Laba Kotor</th>
                                    <th>Biaya Operasional</th>
                                    <th>Laba Bersih</th>
                                    <th>Pesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyReports as $monthlyReport)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $monthlyReport['label'] }}</strong></td>
                                        <td>{{ $formatRupiah($monthlyReport['laba_kotor']) }}</td>
                                        <td>{{ $formatRupiah($monthlyReport['biaya']) }}</td>
                                        <td>
                                            <span class="report-result {{ $monthlyReport['laba_bersih'] >= 0 ? 'is-positive' : 'is-attention' }}">
                                                {{ $monthlyReport['laba_bersih'] >= 0 ? 'Keuntungan' : 'Rugi' }}
                                                {{ $formatRupiah(abs($monthlyReport['laba_bersih'])) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($monthlyReport['pesanan'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm report-panel">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Produk Penyumbang Omzet</h5>
                </div>
                <div class="card-body report-product-list">
                    @forelse($topProducts as $product)
                        <div class="report-product-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block">{{ $product->nama_produk }}</strong>
                                <small class="text-muted">{{ number_format($product->total_terjual, 0, ',', '.') }} produk terjual</small>
                            </div>
                            <span class="fw-semibold">{{ $formatRupiah($product->total_omzet) }}</span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4 mb-0">Belum ada produk terjual.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm report-panel">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Customer</th>
                                    <th>Produk</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $transaction->kode_transaksi ?? 'TRX-' . $transaction->id_pesanan }}</strong></td>
                                        <td>{{ $transaction->username }}</td>
                                        <td>
                                            {{ $transaction->produk?->nama_produk ?? $transaction->nama_produk }}
                                            <small class="d-block text-muted">PRD-{{ str_pad($transaction->id_produk ?? 0, 4, '0', STR_PAD_LEFT) }}</small>
                                        </td>
                                        <td>{{ $formatStatus($transaction->status_transaksi) }}</td>
                                        <td>{{ $formatRupiah($transaction->total_harga_produk) }}</td>
                                        <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .report-hero {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: center;
        padding: 30px 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(75, 46, 43, 0.96), rgba(126, 79, 47, 0.9));
        box-shadow: 0 18px 38px rgba(75, 46, 43, 0.14);
    }

    .report-hero span {
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .report-hero h2 {
        color: #ffffff;
        font-weight: 800;
        margin: 10px 0 8px;
    }

    .report-hero p {
        color: rgba(255, 255, 255, 0.78);
        margin: 0;
    }

    .report-actions,
    .report-filter {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .report-hero .form-select,
    .report-hero .form-control {
        min-width: 190px;
        border-radius: 8px;
    }

    .report-export-btn {
        border-radius: 8px;
        background: #ffffff;
        color: #4b2e2b;
        font-weight: 800;
        white-space: nowrap;
    }

    .report-light-btn {
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.28);
    }

    .report-metric,
    .report-panel {
        border-radius: 8px;
        overflow: hidden;
    }

    .report-metric {
        border-top: 4px solid #087e8b !important;
    }

    .report-metric.is-positive {
        border-top-color: #16794b !important;
        background: linear-gradient(180deg, #ffffff, #f3fbf7);
    }

    .report-metric.is-attention {
        border-top-color: #d97706 !important;
        background: linear-gradient(180deg, #ffffff, #fff8eb);
    }

    .report-metric.is-positive h3 {
        color: #16794b;
    }

    .report-metric.is-attention h3 {
        color: #9a4d00;
    }

    .report-result {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 800;
    }

    .report-result.is-positive {
        background: #e4f5ec;
        color: #12633d;
    }

    .report-result.is-attention {
        background: #fff0d5;
        color: #874300;
    }

    .report-metric span {
        color: #6b625c;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .report-metric h3 {
        color: #2c1f1b;
        font-weight: 800;
        margin: 8px 0 0;
    }

    .report-metric small {
        display: block;
        color: #8b817b;
        margin-top: 8px;
    }

    .report-chart-card {
        min-height: 100%;
    }

    .report-chart-body {
        min-height: 420px;
        display: flex;
        align-items: stretch;
    }

    .report-chart-body canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .report-product-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 12px;
    }

    .report-product-item {
        gap: 16px;
        padding: 14px;
        border: 1px solid #f0dfcf;
        border-radius: 8px;
        background: #fffaf5;
    }

    .report-product-item strong {
        color: #2c1f1b;
    }

    .report-panel thead th {
        background: #fff4e8;
        color: #4b2e2b;
        font-size: 13px;
        text-transform: uppercase;
        border-bottom: 0;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    @media (max-width: 768px) {
        .report-hero {
            align-items: stretch;
            flex-direction: column;
        }

        .report-actions,
        .report-filter {
            align-items: stretch;
            flex-direction: column;
        }

        .report-hero .form-select,
        .report-hero .form-control,
        .report-export-btn,
        .report-light-btn {
            width: 100%;
        }

        .report-chart-body {
            min-height: 320px;
        }

        .report-product-item {
            align-items: flex-start !important;
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const reportCtx = document.getElementById('reportChart');

    new Chart(reportCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Laba Kotor',
                    data: @json($chartData['penjualan']),
                    backgroundColor: '#087E8B',
                    borderColor: '#075F68',
                    borderWidth: 1,
                    borderRadius: 6
                },
                {
                    label: 'Biaya',
                    data: @json($chartData['biaya']),
                    backgroundColor: '#E58A1F',
                    borderColor: '#B85C00',
                    borderWidth: 1,
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, padding: 20, color: '#332824', font: { weight: '600' } }
                },
                tooltip: {
                    callbacks: {
                        label(context) {
                            return `${context.dataset.label}: Rp ${Number(context.raw).toLocaleString('id-ID')}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(75, 46, 43, 0.09)' },
                    ticks: {
                        callback(value) {
                            return `Rp ${Number(value).toLocaleString('id-ID')}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
