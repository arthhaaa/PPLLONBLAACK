@extends('layouts.customer')

@section('title', 'Daftar Produk - Long Black')

@section('content')
<main class="product-page">
    <section class="product-archive-hero customer-page-banner">
        <div class="container product-page-container">
            <h1>Shop</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="product-archive" id="productArchiveAjax">
        <div class="container product-page-container">
            <div class="row">
                <aside class="col-lg-3">
                    <form action="{{ route('customer.product.index') }}" method="GET" class="product-filter">
                        <div class="filter-header">
                            <h2>Filter Options</h2>
                            @if(request()->hasAny(['search', 'min_price', 'max_price', 'stock', 'sort']))
                                <a href="{{ route('customer.product.index') }}">Clear All</a>
                            @endif
                        </div>

                        <div class="filter-group">
                            <label for="filter-search">Cari Produk</label>
                            <input type="text"
                                   id="filter-search"
                                   name="search"
                                   class="form-control"
                                   placeholder="Nama atau deskripsi"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="filter-group">
                            <label>Rentang Harga</label>
                            <div class="price-inputs">
                                <input type="number"
                                       name="min_price"
                                       class="form-control"
                                       min="0"
                                       placeholder="Min"
                                       value="{{ request('min_price') }}">
                                <input type="number"
                                       name="max_price"
                                       class="form-control"
                                       min="0"
                                       placeholder="Max"
                                       value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Ketersediaan</label>
                            <div class="filter-check">
                                <input type="radio" id="stock-all" name="stock" value="" {{ request('stock') ? '' : 'checked' }}>
                                <label for="stock-all">Semua Produk</label>
                            </div>
                            <div class="filter-check">
                                <input type="radio" id="stock-available" name="stock" value="available" {{ request('stock') === 'available' ? 'checked' : '' }}>
                                <label for="stock-available">In Stock</label>
                            </div>
                            <div class="filter-check">
                                <input type="radio" id="stock-empty" name="stock" value="empty" {{ request('stock') === 'empty' ? 'checked' : '' }}>
                                <label for="stock-empty">Out of Stock</label>
                            </div>
                        </div>

                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <button type="submit" class="btn btn-primary w-100">
                            Terapkan Filter
                        </button>
                    </form>
                </aside>

                <div class="col-lg-9">
                    <div class="product-result-bar">
                        <p>
                            Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                            of {{ $products->total() }} results
                        </p>

                        <form action="{{ route('customer.product.index') }}" method="GET" class="sort-form">
                            @foreach(request()->except('sort', 'page') as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $item)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                    @endforeach
                                @elseif($value !== null && $value !== '')
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label for="sort">Sort by :</label>
                            <select id="sort" name="sort" class="form-control">
                                <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="termurah" {{ request('sort') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                                <option value="termahal" {{ request('sort') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                            </select>
                        </form>
                    </div>

                    @if(request()->hasAny(['search', 'min_price', 'max_price', 'stock', 'sort']))
                        <div class="active-filter-row">
                            <span>Active Filter</span>
                            @if(request('search'))
                                <a href="{{ route('customer.product.index', request()->except(['search', 'page'])) }}">Search: {{ request('search') }} x</a>
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <a href="{{ route('customer.product.index', request()->except(['min_price', 'max_price', 'page'])) }}">
                                    Price: Rp {{ number_format((float) request('min_price', 0), 0, ',', '.') }} -
                                    Rp {{ request('max_price') ? number_format((float) request('max_price'), 0, ',', '.') : '...' }} x
                                </a>
                            @endif
                            @if(request('stock'))
                                <a href="{{ route('customer.product.index', request()->except(['stock', 'page'])) }}">
                                    {{ request('stock') === 'available' ? 'In Stock' : 'Out of Stock' }} x
                                </a>
                            @endif
                            @if(request('sort') && request('sort') !== 'terbaru')
                                <a href="{{ route('customer.product.index', request()->except(['sort', 'page'])) }}">
                                    {{ request('sort') === 'termurah' ? 'Termurah' : 'Termahal' }} x
                                </a>
                            @endif
                            <a class="clear-filter" href="{{ route('customer.product.index') }}">Clear All</a>
                        </div>
                    @endif

                    <div class="product-grid-list">
                        @forelse($products as $product)
                            @php
                                $stock = (int) $product->stok_produk;
                                $stockClass = $stock <= 0 ? 'is-empty' : ($stock <= 10 ? 'is-low' : 'is-available');
                                $stockLabel = $stock <= 0 ? 'Out of Stock' : ($stock <= 10 ? 'Low Stock' : 'In Stock');
                            @endphp
                            <article class="customer-product-card">
                                <div class="customer-product-media">
                                    <a href="{{ route('customer.product.show', $product->id_produk) }}">
                                        @if($product->gambar_produk)
                                            <img src="{{ Storage::url($product->gambar_produk) }}" alt="{{ $product->nama_produk }}">
                                        @else
                                            <span class="product-placeholder">
                                                <i class="fa fa-coffee"></i>
                                            </span>
                                        @endif
                                    </a>
                                    <span class="product-stock-pill {{ $stockClass }}">
                                        {{ $stockLabel }}
                                    </span>
                                    <div class="product-card-tools">
                                        <a href="{{ route('customer.product.show', $product->id_produk) }}" aria-label="Lihat detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($product->stok_produk > 0)
                                            <form action="{{ route('customer.cart.add', $product->id_produk) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="bentuk_produk" value="biji">
                                                <button type="submit" aria-label="Tambah ke keranjang">
                                                    <i class="fa fa-shopping-bag"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="customer-product-info">
                                    <div class="product-card-meta">
                                        <span>Kopi</span>
                                        <span>{{ $product->stok_produk }} stok</span>
                                    </div>
                                    <a href="{{ route('customer.product.show', $product->id_produk) }}" class="customer-product-title">
                                        {{ $product->nama_produk }}
                                    </a>
                                    <p>{{ $product->deskripsi_produk ?: 'Deskripsi produk belum tersedia.' }}</p>
                                    <div class="customer-product-price">
                                        Rp {{ number_format((float) $product->harga_produk, 0, ',', '.') }}
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="empty-state">
                                <i class="fa fa-coffee fa-4x text-muted mb-3"></i>
                                <h3 class="mb-2">Belum ada produk tersedia</h3>
                                <p class="text-muted mb-0">Coba ubah filter atau cek kembali nanti.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                        <div class="product-pagination">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="product-service-strip">
        <div class="container product-page-container">
            <div class="row">
                <div class="col-md-4">
                    <div class="service-item">
                        <span><i class="fa fa-truck"></i></span>
                        <div>
                            <h3>Free Shipping</h3>
                            <p>Untuk pembelian tertentu.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <span><i class="fa fa-credit-card"></i></span>
                        <div>
                            <h3>Flexible Payment</h3>
                            <p>Pilihan pembayaran aman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <span><i class="fa fa-headphones"></i></span>
                        <div>
                            <h3>24x7 Support</h3>
                            <p>Kami siap membantu pelanggan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    (function () {
        const archive = document.getElementById('productArchiveAjax');

        if (!archive || !window.fetch || !window.DOMParser) {
            return;
        }

        function buildUrl(form) {
            const formData = new FormData(form);
            const params = new URLSearchParams();

            formData.forEach(function (value, key) {
                if (value !== null && String(value).trim() !== '') {
                    params.append(key, value);
                }
            });

            const query = params.toString();
            return form.action + (query ? '?' + query : '');
        }

        function replaceArchive(html, url, shouldPushState) {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nextArchive = doc.getElementById('productArchiveAjax');

            if (!nextArchive) {
                window.location.href = url;
                return;
            }

            archive.innerHTML = nextArchive.innerHTML;

            if (shouldPushState) {
                window.history.pushState({ productAjax: true }, '', url);
            }
        }

        function loadProducts(url, shouldPushState) {
            archive.classList.add('is-loading');
            archive.setAttribute('aria-busy', 'true');

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Gagal memuat data produk.');
                    }

                    return response.text();
                })
                .then(function (html) {
                    replaceArchive(html, url, shouldPushState);
                })
                .catch(function () {
                    window.location.href = url;
                })
                .finally(function () {
                    archive.classList.remove('is-loading');
                    archive.removeAttribute('aria-busy');
                });
        }

        archive.addEventListener('submit', function (event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || (!form.classList.contains('product-filter') && !form.classList.contains('sort-form'))) {
                return;
            }

            event.preventDefault();
            loadProducts(buildUrl(form), true);
        });

        archive.addEventListener('change', function (event) {
            const field = event.target;

            if (!(field instanceof HTMLSelectElement) || !field.closest('.sort-form')) {
                return;
            }

            loadProducts(buildUrl(field.form), true);
        });

        archive.addEventListener('click', function (event) {
            const link = event.target.closest('.product-pagination a, .active-filter-row a, .filter-header a');

            if (!link || !link.href) {
                return;
            }

            event.preventDefault();
            loadProducts(link.href, true);
        });

        window.addEventListener('popstate', function () {
            loadProducts(window.location.href, false);
        });
    })();
</script>
@endsection
