@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $formatStatus = fn ($status) => ucwords(str_replace('_', ' ', $status ?: 'Belum Ada'));
@endphp

<div class="container-fluid px-4 pb-5">
    <div class="report-hero mb-4">
        <div>
            <span><i class="fa fa-chart-bar me-2"></i>Laporan Bisnis</span>
            <h2>Laporan Keuangan</h2>
            <p>Ringkasan {{ $periodLabel }} dari laba kotor, biaya operasional, laba bersih, grafik, dan laporan bulanan.</p>
        </div>
        <div class="report-actions">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="report-filter">
                <select name="periode" class="form-select" onchange="this.form.submit()">
                    @foreach($allowedPeriods as $period)
                        <option value="{{ $period }}" @selected($periodDays === $period && ! $selectedMonth)>
                            {{ $period }} Hari Terakhir
                        </option>
                    @endforeach
                </select>
                <input type="month" name="bulan" value="{{ $selectedMonth }}" class="form-control" onchange="this.form.submit()" aria-label="Pilih bulan laporan">
                @if($selectedMonth)
                    <a href="{{ route('admin.laporan.index', ['periode' => $periodDays]) }}" class="btn report-light-btn" title="Reset filter bulan">
                        <i class="fa fa-times"></i>
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn report-export-btn">
                <i class="fa fa-download me-2"></i>Export Laporan
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm report-metric">
                <div class="card-body">
                    <span>Laba Kotor</span>
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
            <div class="card border-0 shadow-sm report-metric">
                <div class="card-body">
                    <span>Laba Bersih</span>
                    <h3 class="{{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">{{ $formatRupiah($labaBersih) }}</h3>
                    <small>Laba kotor - biaya</small>
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
            <div class="card border-0 shadow-sm report-panel">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Grafik Keuangan</h5>
                </div>
                <div class="card-body">
                    <canvas id="reportChart" height="110"></canvas>
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
                                            <strong class="{{ $monthlyReport['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $formatRupiah($monthlyReport['laba_bersih']) }}
                                            </strong>
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

        <div class="col-xl-5">
            <div class="card border-0 shadow-sm report-panel">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Produk Penyumbang Omzet</h5>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $product)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
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

        <div class="col-xl-7">
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
                    backgroundColor: '#C08552'
                },
                {
                    label: 'Biaya',
                    data: @json($chartData['biaya']),
                    backgroundColor: '#8C5A3C'
                },
                {
                    label: 'Laba Bersih',
                    data: @json($chartData['laba_bersih']),
                    type: 'line',
                    borderColor: '#2E7D32',
                    backgroundColor: '#2E7D32',
                    tension: 0.35
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
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
