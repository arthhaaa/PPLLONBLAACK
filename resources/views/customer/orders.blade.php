@extends('layouts.customer')

@section('title', 'Pesanan Saya - Long Black')

@section('content')
<main class="customer-orders-page">
    <section class="product-archive-hero customer-page-banner">
        <div class="container product-page-container">
            <h1>Transactions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="section_gap">
        <div class="container product-page-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive customer-orders-panel">
                <table class="table table-bordered align-middle customer-orders-table">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Item</th>
                            <th>Total Produk</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td data-label="Kode"><strong>{{ $transaction['kode_transaksi'] }}</strong></td>
                                <td data-label="Item">{{ $transaction['jumlah_item'] }} item</td>
                                <td data-label="Total Produk">{{ $transaction['total_produk'] }}</td>
                                <td data-label="Total">Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</td>
                                <td data-label="Pembayaran">{{ $transaction['metode_pembayaran'] }}</td>
                                <td data-label="Status">
                                    <span class="order-status-badge status-{{ str_replace('_', '-', $transaction['status_transaksi']) }}">
                                        {{ ucwords(str_replace('_', ' ', $transaction['status_transaksi'])) }}
                                    </span>
                                </td>
                                <td data-label="Tanggal">{{ $transaction['created_at'] ? $transaction['created_at']->format('d M Y H:i') : '-' }}</td>
                                <td data-label="Aksi">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('customer.orders.show', $transaction['kode_transaksi']) }}" class="btn btn-sm order-detail-btn">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 customer-empty-orders">
                                    Belum ada pesanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    .order-status-badge {
        display: inline-flex;
        border-radius: 999px;
        padding: 7px 12px;
        background: rgba(75, 46, 43, 0.1);
        color: #4b2e2b;
        font-size: 12px;
        font-weight: 800;
    }

    .status-dibatalkan {
        background: rgba(210, 65, 65, 0.14);
        color: #b22d2d;
    }

    .status-selesai {
        background: rgba(73, 169, 137, 0.16);
        color: #2e8b70;
    }
</style>
@endsection
