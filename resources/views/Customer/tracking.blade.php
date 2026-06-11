@extends('layouts.customer')

@section('title', 'Tracking Pesanan - Long Black')

@section('content')
<main class="customer-tracking-page">
    <section class="product-archive-hero">
        <div class="container">
            <h1>Tracking</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tracking</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="section_gap">
        <div class="container">
            <div class="tracking-section">
                <div class="section-heading-row">
                    <div>
                        <span>Sedang Berjalan</span>
                        <h3>Status Transaksi</h3>
                    </div>
                </div>

                @forelse($activeTransactions as $transaction)
                    <a href="{{ route('customer.orders.show', $transaction['kode_transaksi']) }}" class="tracking-card">
                        <div>
                            <strong>{{ $transaction['kode_transaksi'] }}</strong>
                            <span>{{ $transaction['jumlah_item'] }} item - Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</span>
                        </div>
                        <div class="tracking-status">{{ ucwords(str_replace('_', ' ', $transaction['status_transaksi'])) }}</div>
                    </a>
                @empty
                    <div class="empty-tracking">Belum ada transaksi yang sedang berjalan.</div>
                @endforelse
            </div>

            <div class="tracking-section mt-5">
                <div class="section-heading-row">
                    <div>
                        <span>Riwayat</span>
                        <h3>Transaksi Selesai / Dibatalkan</h3>
                    </div>
                </div>

                @forelse($historyTransactions as $transaction)
                    <a href="{{ route('customer.orders.show', $transaction['kode_transaksi']) }}" class="tracking-card is-history">
                        <div>
                            <strong>{{ $transaction['kode_transaksi'] }}</strong>
                            <span>{{ $transaction['jumlah_item'] }} item - Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</span>
                        </div>
                        <div class="tracking-status">{{ ucwords(str_replace('_', ' ', $transaction['status_transaksi'])) }}</div>
                    </a>
                @empty
                    <div class="empty-tracking">Riwayat transaksi masih kosong.</div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    .tracking-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 18px 20px;
        border: 1px solid rgba(75, 46, 43, 0.12);
        border-radius: 8px;
        color: #4b2e2b;
        background: #ffffff;
        margin-bottom: 12px;
        box-shadow: 0 10px 24px rgba(75, 46, 43, 0.06);
    }

    .tracking-card:hover {
        color: #4b2e2b;
        border-color: rgba(182, 136, 52, 0.42);
    }

    .tracking-card strong,
    .tracking-card span {
        display: block;
    }

    .tracking-card span {
        color: #777777;
        margin-top: 4px;
    }

    .tracking-status {
        border-radius: 999px;
        padding: 8px 12px;
        background: rgba(182, 136, 52, 0.14);
        color: #8a6421;
        font-weight: 800;
        white-space: nowrap;
    }

    .tracking-card.is-history .tracking-status {
        background: rgba(75, 46, 43, 0.1);
        color: #4b2e2b;
    }

    .empty-tracking {
        padding: 26px;
        border-radius: 8px;
        background: #fffaf4;
        color: #777777;
        text-align: center;
    }

    @media (max-width: 575px) {
        .tracking-card {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endsection
