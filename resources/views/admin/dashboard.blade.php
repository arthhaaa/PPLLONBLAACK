@extends('layouts.admin')

@section('title', 'Long Black')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $formatStatus = fn ($status) => ucwords(str_replace('_', ' ', $status ?: 'Belum Ada'));
    $isProfit = $labaBersih >= 0;
@endphp

<div class="admin-dashboard pb-4" style="background: #FFF8F0; min-height: 100vh;">
    <div class="container-fluid px-4">
        <div class="dashboard-hero mb-4">
            <div class="dashboard-hero__content">
                <span class="dashboard-kicker"><i class="fa fa-bar-chart me-2"></i>Ringkasan Toko</span>
                <h2>Dashboard</h2>
                <p>Visual penjualan dan aktivitas toko {{ $periodDays }} hari terakhir.</p>
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="dashboard-period-form">
                <select name="periode" class="form-select" onchange="this.form.submit()">
                    @foreach($allowedPeriods as $period)
                        <option value="{{ $period }}" @selected($periodDays === $period)>
                            {{ $period }} Hari Terakhir
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Penjualan</p>
                                <h3 class="fw-bold text-dark">{{ $formatRupiah($totalPenjualan) }}</h3>
                                <small class="text-muted">{{ $periodDays }} hari terakhir</small>
                            </div>
                            <div class="bg-light p-3 rounded-3">
                                <i class="fas fa-coffee fa-2x" style="color: #C08552;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Pesanan</p>
                                <h3 class="fw-bold text-dark">{{ number_format($totalPesanan, 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ $periodDays }} hari terakhir</small>
                            </div>
                            <div class="bg-light p-3 rounded-3">
                                <i class="fas fa-receipt fa-2x" style="color: #8C5A3C;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Biaya Operasional</p>
                                <h3 class="fw-bold text-dark">{{ $formatRupiah($biayaOperasional) }}</h3>
                                <small class="text-muted">{{ $periodDays }} hari terakhir</small>
                            </div>
                            <div class="bg-light p-3 rounded-3">
                                <i class="fas fa-chart-pie fa-2x" style="color: #C08552;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">{{ $isProfit ? 'Laba Bersih' : 'Biaya Belum Tertutup' }}</p>
                                <h3 class="fw-bold {{ $isProfit ? 'text-success' : 'text-warning' }}">{{ $formatRupiah(abs($labaBersih)) }}</h3>
                                <small class="{{ $isProfit ? 'text-success' : 'text-warning' }}">
                                    {{ $isProfit ? 'Keuntungan periode ini' : 'Target omzet minimum berikutnya' }}
                                </small>
                            </div>
                            <div class="bg-light p-3 rounded-3">
                                <i class="fas fa-wallet fa-2x" style="color: #8C5A3C;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100 dashboard-chart-card" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0">Visual Penjualan</h5>
                    </div>
                    <div class="card-body dashboard-chart-body">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0">Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="180"></canvas>
                        <hr>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="text-muted">Produk Terjual</span>
                            <strong>{{ number_format($produkTerjual, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom">
                            <span class="text-muted">Pelanggan Baru</span>
                            <strong>{{ number_format($pelangganBaru, 0, ',', '.') }} Orang</strong>
                        </div>
                        <div class="d-flex justify-content-between py-3">
                            <span class="text-muted">Stok Menipis</span>
                            <strong class="text-warning">{{ number_format($stokRendah, 0, ',', '.') }} Produk</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 py-4">
                        <h5 class="mb-0">Produk Terlaris</h5>
                        <small class="text-muted">30 Hari</small>
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
                            <p class="text-muted text-center py-4 mb-0">Belum ada produk terjual</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 py-4">
                        <h5 class="mb-0">Stok Menipis</h5>
                        <a href="{{ route('admin.produk.index') }}" class="text-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($lowStockProducts as $product)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <strong class="d-block">{{ $product->nama_produk }}</strong>
                                    <small class="text-muted">{{ $formatRupiah($product->harga_produk) }}</small>
                                </div>
                                <span class="badge bg-warning text-dark">{{ $product->stok_produk }} tersisa</span>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">Tidak ada stok menipis</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0">Transaksi Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentTransactions as $transaction)
                            <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                                <div>
                                    <strong class="d-block">{{ $transaction->kode_transaksi ?? 'TRX-' . $transaction->id_pesanan }}</strong>
                                    <small class="text-muted">
                                        {{ $transaction->username }} - PRD-{{ str_pad($transaction->id_produk ?? 0, 4, '0', STR_PAD_LEFT) }}
                                    </small>
                                    <small class="text-muted d-block">{{ $transaction->produk?->nama_produk ?? $transaction->nama_produk }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-semibold d-block">{{ $formatRupiah($transaction->total_harga_produk) }}</span>
                                    <small class="text-muted">{{ $formatStatus($transaction->status_transaksi) }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Pelanggan Terbaru</h5>
                        <a href="{{ route('admin.akun.index') }}" class="text-primary text-decoration-none small">
                            Lihat Semua Pelanggan
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentCustomers ?? [] as $index => $user)
                                        <tr>
                                            <td class="ps-4">{{ $index + 1 }}</td>
                                            <td><strong>{{ $user->name }}</strong></td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->telp ?? '-' }}</td>
                                            <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                Belum ada pelanggan
                                            </td>
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
</div>
@endsection

@push('styles')
<style>
    .dashboard-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: center;
        min-height: 190px;
        padding: 32px;
        border-radius: 8px;
        background:
            linear-gradient(135deg, rgba(75, 46, 43, 0.96), rgba(126, 79, 47, 0.9)),
            radial-gradient(circle at 86% 24%, rgba(192, 133, 82, 0.38), transparent 28%);
        box-shadow: 0 20px 45px rgba(75, 46, 43, 0.16);
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        right: 34px;
        bottom: -34px;
        width: 150px;
        height: 150px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
    }

    .dashboard-hero__content,
    .dashboard-period-form {
        position: relative;
        z-index: 1;
    }

    .dashboard-kicker {
        display: inline-flex;
        align-items: center;
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .dashboard-hero h2 {
        color: #ffffff;
        font-size: 38px;
        font-weight: 900;
        margin: 10px 0 8px;
    }

    .dashboard-hero p {
        color: rgba(255, 255, 255, 0.78);
        max-width: 620px;
        margin: 0;
    }

    .dashboard-period-form .form-select {
        min-width: 210px;
        min-height: 48px;
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.96);
        color: #4b2e2b;
        font-weight: 700;
        box-shadow: 0 12px 24px rgba(48, 30, 25, 0.14);
    }

    .dashboard-chart-card {
        min-height: 100%;
    }

    .dashboard-chart-body {
        min-height: 520px;
        display: flex;
        align-items: stretch;
    }

    .dashboard-chart-body canvas {
        width: 100% !important;
        height: 100% !important;
    }

    @media (max-width: 1199px) {
        .dashboard-chart-body {
            min-height: 360px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 24px;
        }

        .dashboard-period-form,
        .dashboard-period-form .form-select {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesCtx = document.getElementById('salesChart');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Omzet Penjualan',
                    data: @json($chartData['penjualan']),
                    borderColor: '#087E8B',
                    backgroundColor: 'rgba(8, 126, 139, 0.14)',
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#087E8B',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Pesanan',
                    data: @json($chartData['pesanan']),
                    borderColor: '#D97706',
                    backgroundColor: '#D97706',
                    pointBackgroundColor: '#D97706',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    borderWidth: 3,
                    tension: 0.35,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, padding: 20, color: '#332824', font: { weight: '600' } }
                },
                tooltip: {
                    callbacks: {
                        label(context) {
                            if (context.dataset.yAxisID === 'y') {
                                return `${context.dataset.label}: Rp ${Number(context.raw).toLocaleString('id-ID')}`;
                            }

                            return `${context.dataset.label}: ${Number(context.raw).toLocaleString('id-ID')}`;
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
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#9A4D00', precision: 0 }
                }
            }
        }
    });

    const statusLabels = @json($statusSummary->pluck('status_transaksi')->map(fn ($status) => ucwords(str_replace('_', ' ', $status ?: 'Belum Ada')))->values());
    const statusData = @json($statusSummary->pluck('total')->values());
    const statusCtx = document.getElementById('statusChart');

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels.length ? statusLabels : ['Belum Ada'],
            datasets: [{
                data: statusData.length ? statusData : [1],
                backgroundColor: ['#C08552', '#8C5A3C', '#2F6F4E', '#D9A441', '#A6423C', '#6B7280']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
