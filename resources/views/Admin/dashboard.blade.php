@extends('layouts.admin')

@section('title', 'Dashboard Admin - Long Black')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $formatStatus = fn ($status) => ucwords(str_replace('_', ' ', $status ?: 'Belum Ada'));
@endphp

<div class="admin-dashboard py-4" style="background: #FFF8F0; min-height: 100vh;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Visual penjualan dan aktivitas toko {{ $periodDays }} hari terakhir</p>
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <select name="periode" class="form-select w-auto" style="border-radius: 12px;" onchange="this.form.submit()">
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
                                <p class="text-muted mb-1">Laba Bersih</p>
                                <h3 class="fw-bold text-dark">{{ $formatRupiah($labaBersih) }}</h3>
                                <small class="{{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $labaBersih >= 0 ? 'Profit' : 'Rugi' }}
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
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="mb-0">Visual Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="110"></canvas>
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
                            <span class="text-muted">Stok Rendah</span>
                            <strong class="text-danger">{{ number_format($stokRendah, 0, ',', '.') }} Produk</strong>
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
                                <span class="badge bg-danger">{{ $product->stok_produk }} tersisa</span>
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
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
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
                    borderColor: '#C08552',
                    backgroundColor: 'rgba(192, 133, 82, 0.16)',
                    tension: 0.35,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Jumlah Pesanan',
                    data: @json($chartData['pesanan']),
                    borderColor: '#8C5A3C',
                    backgroundColor: '#8C5A3C',
                    tension: 0.35,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
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
                    ticks: {
                        callback(value) {
                            return `Rp ${Number(value).toLocaleString('id-ID')}`;
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false }
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
