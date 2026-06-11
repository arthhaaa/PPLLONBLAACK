@extends('layouts.customer')

@section('title', 'Detail Transaksi - Long Black')

@section('content')
<main class="customer-transaction-page">
    <section class="product-archive-hero">
        <div class="container">
            <h1>Detail Transaksi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $transaction['kode_transaksi'] }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="section_gap">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="transaction-summary">
                <div>
                    <span>Kode Transaksi</span>
                    <h3>{{ $transaction['kode_transaksi'] }}</h3>
                    <p class="mb-0 text-muted">{{ $transaction['metode_pembayaran'] }} via Midtrans</p>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <strong>Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="detail-box">
                        <h4>Item Pesanan</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Bentuk</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->produk?->nama_produk ?? $item->nama_produk }}
                                                <small class="d-block text-muted">PRD-{{ str_pad($item->id_produk ?? 0, 4, '0', STR_PAD_LEFT) }}</small>
                                            </td>
                                            <td>{{ ucfirst($item->bentuk_produk ?? 'biji') }}</td>
                                            <td>{{ $item->total_produk }}</td>
                                            <td>Rp {{ number_format((float) $item->total_harga_produk, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="detail-box">
                        <h4>Detail Pembayaran</h4>
                        <form method="POST" action="{{ route('customer.orders.update', $transaction['kode_transaksi']) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="metode_pembayaran" value="QRIS">
                            <div class="transaction-qris-box">
                                <div class="transaction-qris-header">
                                    <span class="transaction-qris-icon"><i class="fa fa-qrcode"></i></span>
                                    <div>
                                        <span>Metode Pembayaran</span>
                                        <h5>QRIS Midtrans</h5>
                                        @if($transaction['midtrans_transaction_id'])
                                            <small>ID: {{ $transaction['midtrans_transaction_id'] }}</small>
                                        @endif
                                    </div>
                                </div>

                                @if($transaction['qris_url'])
                                    <div class="transaction-qris-preview">
                                        <img src="{{ $transaction['qris_url'] }}" alt="QRIS pembayaran {{ $transaction['kode_transaksi'] }}">
                                    </div>
                                    <div class="transaction-qris-total">
                                        <span>Total Bayar</span>
                                        <strong>Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</strong>
                                    </div>
                                    <a href="{{ $transaction['qris_url'] }}" target="_blank" rel="noopener noreferrer" class="transaction-qris-fallback">
                                        Buka QRIS di tab baru
                                    </a>
                                @else
                                    <div>
                                        <span>Status QRIS</span>
                                        <h5>QRIS belum tersedia</h5>
                                        <small>Silakan buat ulang transaksi jika QRIS belum muncul.</small>
                                    </div>
                                @endif
                            </div>

                            <div class="transaction-cost-box">
                                <div>
                                    <span>Metode Pembayaran</span>
                                    <strong>QRIS Midtrans</strong>
                                </div>
                                <div><span>Subtotal Produk</span><strong>Rp {{ number_format((float) $transaction['subtotal_produk'], 0, ',', '.') }}</strong></div>
                                <div><span>Ongkir {{ strtoupper($transaction['kurir'] ?? '-') }} {{ $transaction['layanan_kurir'] }}</span><strong>Rp {{ number_format((float) $transaction['ongkir'], 0, ',', '.') }}</strong></div>
                                <div><span>Total</span><strong>Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</strong></div>
                            </div>

                            <label for="alamat_pengiriman" class="mt-3">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control" rows="4" {{ $items->first()->canBeModified() ? '' : 'disabled' }}>{{ old('alamat_pengiriman', $transaction['alamat_pengiriman']) }}</textarea>

                            <label for="catatan" class="mt-3">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3" {{ $items->first()->canBeModified() ? '' : 'disabled' }}>{{ old('catatan', $transaction['catatan']) }}</textarea>

                            <div class="transaction-actions">
                                @if($items->first()->canBeModified())
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <button type="submit" form="cancel-transaction" class="btn btn-outline-danger">
                                        Batalkan Transaksi
                                    </button>
                                @else
                                    <a href="{{ route('customer.tracking') }}" class="btn btn-primary">Lihat Tracking</a>
                                @endif
                            </div>
                        </form>

                        <form id="cancel-transaction" method="POST" action="{{ route('customer.orders.cancel', $transaction['kode_transaksi']) }}" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    .transaction-summary,
    .detail-box {
        border: 1px solid rgba(75, 46, 43, 0.12);
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 14px 32px rgba(75, 46, 43, 0.08);
    }

    .transaction-summary {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .transaction-summary span,
    .detail-box label {
        color: #7b6b62;
        font-weight: 700;
    }

    .transaction-summary h3,
    .detail-box h4 {
        color: #4b2e2b;
        font-weight: 800;
        margin: 6px 0 0;
    }

    .summary-total strong {
        display: block;
        color: #b68834;
        font-size: 24px;
        margin-top: 6px;
    }

    .detail-box {
        padding: 24px;
        margin-bottom: 24px;
    }

    .detail-box h4 {
        margin-bottom: 18px;
    }

    .transaction-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .transaction-qris-box,
    .transaction-cost-box {
        border-radius: 8px;
        border: 1px solid rgba(75, 46, 43, 0.12);
        background: #fffaf5;
        padding: 16px;
        margin-bottom: 16px;
    }

    .transaction-qris-box {
        display: grid;
        gap: 14px;
        text-align: center;
    }

    .transaction-qris-box span,
    .transaction-cost-box span {
        color: #7b6b62;
        font-weight: 800;
        font-size: 12px;
    }

    .transaction-qris-header {
        display: flex;
        align-items: center;
        gap: 12px;
        text-align: left;
    }

    .transaction-qris-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        border-radius: 8px;
        background: #4b2e2b;
        color: #ffffff !important;
        font-size: 20px !important;
    }

    .transaction-qris-box h5 {
        color: #4b2e2b;
        font-weight: 900;
        margin: 4px 0;
    }

    .transaction-qris-preview {
        display: flex;
        justify-content: center;
        padding: 14px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.1);
    }

    .transaction-qris-preview img {
        width: min(100%, 260px);
        aspect-ratio: 1 / 1;
        object-fit: contain;
    }

    .transaction-qris-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 8px;
        background: #4b2e2b;
        color: #ffffff;
    }

    .transaction-qris-total span {
        color: rgba(255, 255, 255, 0.78);
    }

    .transaction-qris-total strong {
        font-size: 22px;
    }

    .transaction-qris-fallback {
        color: #8c5a3c;
        font-weight: 800;
        font-size: 13px;
    }

    .transaction-cost-box div {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
    }

    .transaction-cost-box div:last-child {
        border-top: 1px solid rgba(75, 46, 43, 0.12);
        margin-top: 6px;
        padding-top: 14px;
    }
</style>
@endsection
