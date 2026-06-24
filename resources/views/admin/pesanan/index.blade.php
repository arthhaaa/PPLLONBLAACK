@extends('layouts.admin')

@section('title', 'Pesanan Masuk')

@section('content')
@php
    $statusLabels = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'sedang_diproses' => 'Sedang Diproses',
        'siap_dikirim' => 'Siap Dikirim/Diambil',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    $statusFlow = [
        'menunggu_pembayaran' => ['dibatalkan'],
        'sedang_diproses' => ['siap_dikirim'],
        'siap_dikirim' => ['selesai'],
        'selesai' => [],
        'dibatalkan' => [],
    ];
@endphp

<div class="container-fluid px-4 pb-5">
    <div class="order-admin-hero mb-4">
        <div>
            <span><i class="fa fa-receipt me-2"></i>Pesanan Customer</span>
            <h2>Pesanan Masuk</h2>
            <p>Lihat produk yang dipesan customer beserta pilihan bentuk kopi: biji atau bubuk.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm order-admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Customer</th>
                            <th>ID Produk</th>
                            <th>Bentuk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Aksi Status</th>
                            <th>Struk</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $currentStatus = $order->status_transaksi ?? 'menunggu_pembayaran';
                                $nextStatuses = $statusFlow[$currentStatus] ?? [];
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $orders->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $order->username }}</strong></td>
                                <td>
                                    <strong>PRD-{{ str_pad($order->id_produk ?? 0, 4, '0', STR_PAD_LEFT) }}</strong>
                                    <small class="d-block text-muted">{{ $order->produk?->nama_produk ?? $order->nama_produk }}</small>
                                </td>
                                <td>
                                    <span class="order-type-badge {{ $order->bentuk_produk === 'bubuk' ? 'is-powder' : 'is-bean' }}">
                                        {{ ucfirst($order->bentuk_produk ?? 'biji') }}
                                    </span>
                                </td>
                                <td>{{ $order->total_produk }}</td>
                                <td>Rp {{ number_format((float) $order->total_harga_produk, 0, ',', '.') }}</td>
                                <td>{{ $order->metode_pembayaran }}</td>
                                <td>
                                    <span class="order-status-badge is-{{ str_replace('_', '-', $currentStatus) }}">
                                        {{ $statusLabels[$currentStatus] ?? ucwords(str_replace('_', ' ', $currentStatus)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($nextStatuses)
                                        <form action="{{ route('admin.pesanan.status', $order->id_pesanan) }}" method="POST" class="status-form">
                                            @csrf
                                            <select name="status_transaksi" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="" selected>Pilih aksi status</option>
                                                @foreach($nextStatuses as $value)
                                                    <option value="{{ $value }}">
                                                        {{ $statusLabels[$value] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        <span class="text-muted small">Status final</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pesanan.invoice', $order->id_pesanan) }}" target="_blank" class="btn btn-sm order-receipt-btn">
                                        <i class="fa fa-receipt"></i> Struk
                                    </a>
                                </td>
                                <td>{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    Belum ada pesanan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .order-admin-hero {
        padding: 30px 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(75, 46, 43, 0.96), rgba(126, 79, 47, 0.9));
        box-shadow: 0 18px 38px rgba(75, 46, 43, 0.14);
    }

    .order-admin-hero span {
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .order-admin-hero h2 {
        color: #ffffff;
        font-weight: 800;
        margin: 10px 0 8px;
    }

    .order-admin-hero p {
        color: rgba(255, 255, 255, 0.78);
        margin: 0;
    }

    .order-admin-card {
        border-radius: 8px;
        overflow: hidden;
    }

    .order-admin-card thead th {
        background: #fff4e8;
        color: #4b2e2b;
        font-size: 13px;
        text-transform: uppercase;
        border-bottom: 0;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .order-type-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 7px 12px;
        background: rgba(75, 46, 43, 0.1);
        color: #4b2e2b;
        font-weight: 800;
        font-size: 12px;
    }

    .order-type-badge.is-powder {
        background: rgba(73, 169, 137, 0.16);
        color: #2e8b70;
    }

    .order-status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 7px 12px;
        background: #f3eee8;
        color: #4b2e2b;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-status-badge.is-sedang-diproses {
        background: #e8f2ff;
        color: #245f9f;
    }

    .order-status-badge.is-siap-dikirim {
        background: #fff0d5;
        color: #874300;
    }

    .order-status-badge.is-selesai {
        background: #e4f5ec;
        color: #12633d;
    }

    .order-status-badge.is-dibatalkan {
        background: #fde4e4;
        color: #9f1f1f;
    }

    .status-form {
        min-width: 180px;
    }

    .order-receipt-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 8px;
        background: #4b2e2b;
        color: #ffffff;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-receipt-btn:hover {
        background: #b35c0c;
        color: #ffffff;
    }
</style>
@endpush
