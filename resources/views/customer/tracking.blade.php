@extends('layouts.customer')

@section('title', 'Tracking Pesanan - Long Black')

@section('content')
@php
    $trackingSteps = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'sedang_diproses' => 'Sedang Diproses',
        'siap_dikirim' => 'Siap Dikirim/Diambil',
        'selesai' => 'Selesai',
    ];

    $stepKeys = array_keys($trackingSteps);
@endphp

<main class="customer-tracking-page">
    <section class="product-archive-hero customer-page-banner">
        <div class="container product-page-container">
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
        <div class="container product-page-container">
            <div class="tracking-section">
                <div class="section-heading-row">
                    <div>
                        <span>Sedang Berjalan</span>
                        <h3>Progress Pesanan Aktif</h3>
                        <p class="tracking-subtitle">Pantau pesanan yang masih menunggu pembayaran atau sedang diproses oleh Long Black.</p>
                    </div>
                    <a href="{{ route('customer.orders') }}" class="tracking-history-link">Lihat Riwayat Orders</a>
                </div>

                @forelse($activeTransactions as $transaction)
                    @php
                        $currentStatus = $transaction['status_transaksi'] ?? 'menunggu_pembayaran';
                        $currentStep = array_search($currentStatus, $stepKeys, true);
                        $currentStep = $currentStep === false ? 0 : $currentStep;
                        $itemsPreview = collect($transaction['items'])->take(2);
                    @endphp

                    <article class="tracking-card">
                        <div class="tracking-card__header">
                            <div>
                                <span class="tracking-code">{{ $transaction['kode_transaksi'] }}</span>
                                <h4>{{ $trackingSteps[$currentStatus] ?? ucwords(str_replace('_', ' ', $currentStatus)) }}</h4>
                                <p>{{ $transaction['jumlah_item'] }} item - Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('customer.orders.show', $transaction['kode_transaksi']) }}" class="tracking-detail-btn">
                                Detail Pesanan
                            </a>
                        </div>

                        <div class="tracking-products">
                            @foreach($itemsPreview as $item)
                                <span>{{ $item->produk?->nama_produk ?? $item->nama_produk }} x{{ $item->total_produk }}</span>
                            @endforeach
                            @if($transaction['items']->count() > 2)
                                <span>+{{ $transaction['items']->count() - 2 }} produk lainnya</span>
                            @endif
                        </div>

                        <div class="tracking-timeline">
                            @foreach($trackingSteps as $status => $label)
                                @php
                                    $stepIndex = array_search($status, $stepKeys, true);
                                    $isDone = $stepIndex < $currentStep;
                                    $isCurrent = $stepIndex === $currentStep;
                                @endphp
                                <div class="tracking-step {{ $isDone ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                                    <span class="tracking-step__dot">
                                        @if($isDone)
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </span>
                                    <div>
                                        <strong>{{ $label }}</strong>
                                        <small>
                                            @if($isDone)
                                                Selesai
                                            @elseif($isCurrent)
                                                Sedang berlangsung
                                            @else
                                                Menunggu tahap sebelumnya
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @empty
                    <div class="empty-tracking">
                        <h4>Tidak ada pesanan aktif.</h4>
                        <p>Pesanan yang sudah selesai atau dibatalkan bisa kamu lihat di menu Orders.</p>
                        <a href="{{ route('customer.orders') }}">Buka Orders</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    .tracking-section {
        max-width: 1040px;
        margin: 0 auto;
    }

    .section-heading-row {
        align-items: flex-end;
        display: flex;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .section-heading-row span {
        color: #b68834;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .section-heading-row h3 {
        color: #2d1e18;
        font-weight: 900;
        margin: 6px 0;
    }

    .tracking-subtitle {
        color: #777777;
        margin: 0;
    }

    .tracking-history-link,
    .tracking-detail-btn,
    .empty-tracking a {
        border-radius: 8px;
        color: #4b2e2b;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .tracking-history-link {
        border: 1px solid rgba(75, 46, 43, 0.16);
        padding: 10px 14px;
        background: #fffaf4;
    }

    .tracking-card {
        display: block;
        padding: 22px;
        border: 1px solid rgba(75, 46, 43, 0.12);
        border-radius: 8px;
        color: #4b2e2b;
        background: #ffffff;
        margin-bottom: 18px;
        box-shadow: 0 14px 30px rgba(75, 46, 43, 0.07);
    }

    .tracking-card__header {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
    }

    .tracking-code {
        display: block;
        color: #b68834;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .tracking-card h4 {
        color: #2d1e18;
        font-weight: 900;
        margin: 6px 0;
    }

    .tracking-card p {
        color: #777777;
        margin: 0;
    }

    .tracking-detail-btn {
        background: #4b2e2b;
        color: #ffffff;
        padding: 10px 14px;
    }

    .tracking-products {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 18px 0 20px;
    }

    .tracking-products span {
        border-radius: 999px;
        background: #fff4e8;
        color: #6f4a2f;
        font-size: 12px;
        font-weight: 800;
        padding: 7px 10px;
    }

    .tracking-timeline {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .tracking-step {
        position: relative;
        display: flex;
        gap: 10px;
        padding: 14px;
        border-radius: 8px;
        background: #f8f4ef;
        color: #8a817b;
    }

    .tracking-step.is-done,
    .tracking-step.is-current {
        background: #fff8ed;
        color: #4b2e2b;
    }

    .tracking-step.is-current {
        box-shadow: inset 0 0 0 2px rgba(182, 136, 52, 0.35);
    }

    .tracking-step__dot {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ddd4ca;
        color: #ffffff;
        font-size: 11px;
        margin-top: 1px;
    }

    .tracking-step.is-done .tracking-step__dot,
    .tracking-step.is-current .tracking-step__dot {
        background: #b68834;
    }

    .tracking-step strong,
    .tracking-step small {
        display: block;
    }

    .tracking-step strong {
        color: inherit;
        font-size: 13px;
        line-height: 1.35;
    }

    .tracking-step small {
        color: #8a817b;
        margin-top: 4px;
    }

    .empty-tracking {
        padding: 34px;
        border-radius: 8px;
        background: #fffaf4;
        color: #777777;
        text-align: center;
        border: 1px dashed rgba(75, 46, 43, 0.18);
    }

    .empty-tracking h4 {
        color: #2d1e18;
        font-weight: 900;
        margin-bottom: 8px;
    }

    .empty-tracking a {
        display: inline-flex;
        margin-top: 10px;
        padding: 10px 16px;
        background: #4b2e2b;
        color: #ffffff;
    }

    @media (max-width: 991px) {
        .tracking-timeline {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .section-heading-row,
        .tracking-card__header {
            align-items: flex-start;
            flex-direction: column;
        }

        .tracking-timeline {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
