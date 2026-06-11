@php
    $homepageProducts = collect($products ?? []);
    $detailRouteName = $detailRouteName ?? 'product.show';
    $shopRouteName = $shopRouteName ?? 'shop';
    $requiresLogin = $requiresLogin ?? false;
@endphp

<section class="homepage-product-area section_gap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title homepage-product-title js-reveal">
                    <span>Produk Long Black</span>
                    <h1>Produk Terbaru Kami</h1>
                    <p>Daftar produk ini diambil langsung dari data produk yang sudah dibuat admin.</p>
                </div>
            </div>
        </div>

        @if($homepageProducts->isNotEmpty())
            <div class="row">
                @foreach($homepageProducts->take(8) as $product)
                    @php
                        $productUrl = $requiresLogin ? route('login') : route($detailRouteName, $product->id_produk);
                    @endphp
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="single-product homepage-product-card js-reveal">
                            <a href="{{ $productUrl }}" class="homepage-product-image">
                                @if($product->gambar_produk)
                                    <img class="img-fluid" src="{{ asset('storage/' . $product->gambar_produk) }}" alt="{{ $product->nama_produk }}">
                                @else
                                    <span class="lnr lnr-coffee-cup"></span>
                                @endif
                            </a>

                            <div class="product-details">
                                <h6>{{ $product->nama_produk }}</h6>
                                <div class="price">
                                    <h6>Rp{{ number_format((float) $product->harga_produk, 0, ',', '.') }}</h6>
                                    <span class="homepage-stock">Stok {{ $product->stok_produk }}</span>
                                </div>
                                <div class="prd-bottom">
                                    <a href="{{ $productUrl }}" class="social-info">
                                        <span class="lnr lnr-move"></span>
                                        <p class="hover-text">detail</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <a href="{{ $requiresLogin ? route('login') : route($shopRouteName) }}" class="primary-btn homepage-product-more">Lihat Semua Produk</a>
            </div>
        @else
            <div class="homepage-product-empty js-reveal">
                <h4>Belum ada produk</h4>
                <p>Produk yang dibuat admin akan tampil otomatis di area ini.</p>
            </div>
        @endif
    </div>
</section>
