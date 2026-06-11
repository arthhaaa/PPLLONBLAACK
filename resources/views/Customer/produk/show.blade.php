@extends('layouts.customer')

@section('title', $product->nama_produk . ' - Long Black')

@section('content')
<main class="product-detail-page">
    <section class="product-archive-hero product-detail-hero">
        <div class="container">
            <h1>Product Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.product.index') }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->nama_produk }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail-layout">
                <div class="product-gallery">
                    <div class="detail-media">
                        @if($product->gambar_produk)
                            <img src="{{ Storage::url($product->gambar_produk) }}" alt="{{ $product->nama_produk }}">
                        @else
                            <div class="detail-placeholder">
                                <i class="fa fa-coffee"></i>
                            </div>
                        @endif
                    </div>

                    <div class="detail-thumbs" aria-label="Gambar produk">
                        <button type="button" class="detail-thumb is-active" aria-label="Gambar utama {{ $product->nama_produk }}">
                            @if($product->gambar_produk)
                                <img src="{{ Storage::url($product->gambar_produk) }}" alt="">
                            @else
                                <i class="fa fa-coffee"></i>
                            @endif
                        </button>
                    </div>
                </div>

                <div class="detail-info">
                    <div class="detail-kicker">Kopi Specialty</div>
                    <div class="detail-title-row">
                        <h2 class="detail-title">{{ $product->nama_produk }}</h2>
                        <span class="detail-stock-pill {{ $product->stok_produk > 0 ? 'is-available' : 'is-empty' }}">
                            {{ $product->stok_produk > 0 ? 'In Stock' : 'Sold Out' }}
                        </span>
                    </div>

                    <div class="detail-price">
                        Rp {{ number_format((float) $product->harga_produk, 0, ',', '.') }}
                    </div>

                    <p class="detail-description">
                        {{ $product->deskripsi_produk ?: 'Deskripsi produk belum tersedia.' }}
                    </p>

                    @if($product->stok_produk > 0)
                        <form action="{{ route('customer.cart.add', $product->id_produk) }}" method="POST" class="detail-purchase">
                            @csrf
                            <div class="detail-form-choice" aria-label="Pilih bentuk kopi">
                                <span class="detail-label">Bentuk Kopi</span>
                                <div class="coffee-type-options">
                                    <label class="coffee-type-option">
                                        <input type="radio" name="bentuk_produk" value="biji" checked>
                                        <span>
                                            <i class="fa fa-circle"></i>
                                            Biji
                                        </span>
                                    </label>
                                    <label class="coffee-type-option">
                                        <input type="radio" name="bentuk_produk" value="bubuk">
                                        <span>
                                            <i class="fa fa-coffee"></i>
                                            Bubuk
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <label for="quantity" class="detail-label">Jumlah</label>
                            <div class="detail-buy-row">
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn" onclick="decrement()" aria-label="Kurangi jumlah">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stok_produk }}" aria-label="Jumlah produk">
                                    <button type="button" class="quantity-btn" onclick="increment()" aria-label="Tambah jumlah">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>

                                <button type="submit" class="btn detail-cart-btn">
                                    <i class="fa fa-shopping-bag"></i> Tambah ke Keranjang
                                </button>

                                <a href="{{ route('customer.product.index') }}" class="btn detail-back-btn" aria-label="Kembali ke daftar produk">
                                    <i class="fa fa-arrow-left"></i>
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="detail-buy-row">
                            <button class="btn detail-sold-btn" disabled>
                                <i class="fa fa-ban"></i> Stok Habis
                            </button>
                            <a href="{{ route('customer.product.index') }}" class="btn detail-back-btn" aria-label="Kembali ke daftar produk">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                    @endif

                    <div class="detail-meta-list">
                        <div><strong>Kode Produk :</strong> PRD-{{ str_pad($product->id_produk, 4, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>Stok :</strong> {{ $product->stok_produk }} pcs</div>
                    </div>
                </div>
            </div>

            <div class="product-detail-tabs">
                <ul class="nav detail-tab-nav" id="productDetailTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">
                            Deskripsi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="false">
                            Informasi Produk
                        </a>
                    </li>
                </ul>

                <div class="tab-content detail-tab-content" id="productDetailTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <p>{{ $product->deskripsi_produk ?: 'Deskripsi produk belum tersedia.' }}</p>
                    </div>
                    <div class="tab-pane fade" id="information" role="tabpanel" aria-labelledby="information-tab">
                        <div class="detail-info-grid">
                            <div>
                                <span>Nama Produk</span>
                                <strong>{{ $product->nama_produk }}</strong>
                            </div>
                            <div>
                                <span>Harga</span>
                                <strong>Rp {{ number_format((float) $product->harga_produk, 0, ',', '.') }}</strong>
                            </div>
                            <div>
                                <span>Ketersediaan</span>
                                <strong>{{ $product->stok_produk > 0 ? $product->stok_produk . ' pcs tersedia' : 'Stok habis' }}</strong>
                            </div>
                            <div>
                                <span>Kategori</span>
                                <strong>Kopi Specialty</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedProducts->count() > 0)
                <section class="related-products-section">
                    <div class="section-heading-row">
                        <div>
                            <span>Rekomendasi</span>
                            <h3>Produk Terkait</h3>
                        </div>
                        <a href="{{ route('customer.product.index') }}">Lihat semua</a>
                    </div>

                    <div class="row g-4">
                        @foreach($relatedProducts as $related)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ route('customer.product.show', $related->id_produk) }}" class="related-card">
                                    @if($related->gambar_produk)
                                        <img src="{{ Storage::url($related->gambar_produk) }}" alt="{{ $related->nama_produk }}">
                                    @else
                                        <div class="related-placeholder">
                                            <i class="fa fa-coffee"></i>
                                        </div>
                                    @endif
                                    <div class="related-body">
                                        <span>{{ $related->stok_produk > 0 ? 'In Stock' : 'Sold Out' }}</span>
                                        <h6>{{ $related->nama_produk }}</h6>
                                        <div class="related-price">
                                            Rp {{ number_format((float) $related->harga_produk, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    function increment() {
        const quantity = document.getElementById('quantity');
        const max = parseInt(quantity.max, 10);
        const value = parseInt(quantity.value || '1', 10);

        if (value < max) {
            quantity.value = value + 1;
        }
    }

    function decrement() {
        const quantity = document.getElementById('quantity');
        const min = parseInt(quantity.min, 10);
        const value = parseInt(quantity.value || '1', 10);

        if (value > min) {
            quantity.value = value - 1;
        }
    }
</script>
@endsection

@section('styles')
<style>
    .detail-form-choice {
        margin: 26px 0 18px;
    }

    .coffee-type-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        max-width: 360px;
        margin-top: 10px;
    }

    .coffee-type-option {
        cursor: pointer;
        margin: 0;
    }

    .coffee-type-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .coffee-type-option span {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 1px solid rgba(75, 46, 43, 0.18);
        border-radius: 8px;
        background: #fffaf4;
        color: #4b2e2b;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .coffee-type-option input:checked + span {
        background: #4b2e2b;
        border-color: #4b2e2b;
        color: #ffffff;
        box-shadow: 0 12px 26px rgba(75, 46, 43, 0.16);
    }

    @media (max-width: 480px) {
        .coffee-type-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
