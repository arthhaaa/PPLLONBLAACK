@extends('layouts.admin')

@section('title', 'Data Produk')

@section('content')
@php
    $visibleProducts = $products->getCollection();
    $availableCount = $visibleProducts->where('stok_produk', '>', 0)->count();
    $lowStockCount = $visibleProducts->filter(fn ($product) => (int) $product->stok_produk > 0 && (int) $product->stok_produk <= 5)->count();
@endphp

<div class="product-admin-page container-fluid px-4 pb-5">
    <div class="product-hero mb-4">
        <div class="product-hero__content">
            <span class="product-kicker"><i class="fa fa-mug-hot me-2"></i>Gudang Produk</span>
            <h2>Daftar Produk Kopi</h2>
            <p>Kelola katalog Long Black dari gambar, harga, stok, sampai deskripsi produk yang tampil ke pelanggan.</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="btn product-primary-btn">
            <i class="fa fa-plus"></i>
            <span>Tambah Produk</span>
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="product-stat-card">
                <span class="product-stat-icon"><i class="fa fa-boxes-stacked"></i></span>
                <div>
                    <p>Produk Aktif</p>
                    <strong>{{ method_exists($products, 'total') ? $products->total() : $products->count() }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="product-stat-card product-stat-card--green">
                <span class="product-stat-icon"><i class="fa fa-circle-check"></i></span>
                <div>
                    <p>Tersedia di Halaman Ini</p>
                    <strong>{{ $availableCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="product-stat-card product-stat-card--orange">
                <span class="product-stat-icon"><i class="fa fa-triangle-exclamation"></i></span>
                <div>
                    <p>Stok Menipis</p>
                    <strong>{{ $lowStockCount }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="product-stat-card product-stat-card--red">
                <span class="product-stat-icon"><i class="fa fa-box-archive"></i></span>
                <div>
                    <p>Produk Terhapus</p>
                    <strong>{{ $trashedCount }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-6 col-xl-4 col-xxl-3">
            <div class="product-card h-100">
                <div class="product-card__image">
                    @if($product->gambar_produk)
                        <img src="{{ Storage::url($product->gambar_produk) }}" alt="{{ $product->nama_produk }}">
                    @else
                        <div class="product-card__empty">
                            <i class="fa fa-image fa-3x"></i>
                            <span>Belum ada gambar</span>
                        </div>
                    @endif
                    <span class="product-stock-badge {{ $product->stok_produk > 0 ? 'is-ready' : 'is-empty' }}">
                        {{ $product->stok_produk > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>

                <div class="product-card__body">
                    <div class="product-card__top">
                        <span>Kode PRD-{{ str_pad($product->id_produk, 4, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $product->stok_produk }} stok</strong>
                    </div>
                    <h5>{{ $product->nama_produk }}</h5>
                    <p class="product-card__desc">{{ \Illuminate\Support\Str::limit($product->deskripsi_produk, 88) }}</p>
                    <div class="product-card__price">
                        Rp {{ number_format($product->harga_produk, 0, ',', '.') }}
                    </div>
                </div>

                <div class="product-card__actions">
                    <a href="{{ route('admin.produk.edit', $product) }}" class="btn product-edit-btn">
                        <i class="fa fa-pen-to-square"></i>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.produk.destroy', $product) }}"
                          method="POST"
                          class="admin-confirm-form"
                          data-confirm-title="Hapus Produk"
                          data-confirm-message="Yakin ingin menghapus produk {{ $product->nama_produk }}?"
                          data-confirm-action="Produk akan masuk ke data terhapus dan tidak tampil di halaman customer."
                          data-confirm-button='<i class="fa fa-trash me-1"></i> Ya, hapus produk'>
                        @csrf
                        @method('DELETE')
                        <button class="btn product-delete-btn" type="submit">
                            <i class="fa fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="product-empty-state">
                <span><i class="fa fa-mug-saucer"></i></span>
                <h4>Belum ada produk</h4>
                <p>Tambahkan produk pertama agar katalog customer mulai terlihat hidup.</p>
                <a href="{{ route('admin.produk.create') }}" class="btn product-primary-btn">
                    <i class="fa fa-plus"></i>
                    <span>Tambah produk pertama</span>
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="product-pagination-wrap mt-4">
            <div class="product-pagination-summary">
                Menampilkan {{ $products->count() }} produk di halaman ini dari {{ $products->total() }} total produk
            </div>
            <nav class="product-pagination" aria-label="Navigasi halaman produk">
                <ul class="pagination">
                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $products->appends(request()->except('page'))->previousPageUrl() ?: '#' }}" aria-label="Halaman sebelumnya">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>

                    @foreach($products->appends(request()->except('page'))->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $products->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $products->appends(request()->except('page'))->nextPageUrl() ?: '#' }}" aria-label="Halaman berikutnya">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif

    <div class="product-trash-section mt-5">
        <div class="product-section-heading">
            <div>
                <span><i class="fa fa-box-archive me-2"></i>Soft Delete</span>
                <h4>Produk Terhapus</h4>
                <p>Produk di bagian ini tidak tampil ke customer, tetapi masih bisa dipulihkan oleh admin.</p>
            </div>
        </div>

        @if($trashedProducts->count())
            <div class="table-responsive product-trash-table">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Dihapus Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedProducts as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="product-trash-item">
                                        <div class="product-trash-thumb">
                                            @if($product->gambar_produk)
                                                <img src="{{ Storage::url($product->gambar_produk) }}" alt="{{ $product->nama_produk }}">
                                            @else
                                                <i class="fa fa-image"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $product->nama_produk }}</strong>
                                            <span>PRD-{{ str_pad($product->id_produk, 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($product->harga_produk, 0, ',', '.') }}</td>
                                <td>{{ $product->stok_produk }} stok</td>
                                <td>{{ optional($product->deleted_at)->format('d M Y H:i') }}</td>
                                <td class="text-end pe-4">
                                    <div class="product-trash-actions">
                                        <form action="{{ route('admin.produk.restore', $product->id_produk) }}"
                                              method="POST"
                                              class="admin-confirm-form"
                                              data-confirm-title="Pulihkan Produk"
                                              data-confirm-message="Pulihkan produk {{ $product->nama_produk }}?"
                                              data-confirm-action="Produk akan kembali tampil di data produk admin dan katalog customer."
                                              data-confirm-button='<i class="fa fa-rotate-left me-1"></i> Ya, pulihkan'>
                                            @csrf
                                            <button type="submit" class="btn product-restore-btn">
                                                <i class="fa fa-rotate-left"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.produk.force-delete', $product->id_produk) }}"
                                              method="POST"
                                              class="admin-confirm-form"
                                              data-confirm-title="Hapus Permanen"
                                              data-confirm-message="Yakin ingin menghapus permanen produk {{ $product->nama_produk }}?"
                                              data-confirm-action="Data dan gambar produk akan dihapus permanen dan tidak bisa dipulihkan."
                                              data-confirm-button='<i class="fa fa-trash me-1"></i> Ya, hapus permanen'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn product-force-delete-btn">
                                                <i class="fa fa-trash"></i>
                                                <span>Permanen</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($trashedProducts->hasPages())
                <div class="product-pagination-wrap mt-4">
                    <div class="product-pagination-summary">
                        Menampilkan {{ $trashedProducts->count() }} produk terhapus dari {{ $trashedProducts->total() }} total data terhapus
                    </div>
                    <nav class="product-pagination" aria-label="Navigasi produk terhapus">
                        {{ $trashedProducts->appends(request()->except('terhapus'))->links() }}
                    </nav>
                </div>
            @endif
        @else
            <div class="product-empty-state product-empty-state--compact">
                <span><i class="fa fa-box-open"></i></span>
                <h4>Belum ada produk terhapus</h4>
                <p>Produk yang dihapus dengan soft delete akan muncul di sini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-admin-page {
        color: #3f302b;
    }

    .product-hero {
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

    .product-hero::after {
        content: "";
        position: absolute;
        right: 34px;
        bottom: -34px;
        width: 150px;
        height: 150px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
    }

    .product-hero__content,
    .product-hero .btn {
        position: relative;
        z-index: 1;
    }

    .product-kicker {
        display: inline-flex;
        align-items: center;
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-hero h2 {
        color: #ffffff;
        font-weight: 800;
        margin: 10px 0 8px;
    }

    .product-hero p {
        color: rgba(255, 255, 255, 0.78);
        max-width: 620px;
        margin: 0;
    }

    .product-primary-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        border: 0;
        border-radius: 8px;
        background: #8c5a3c;
        color: #ffffff;
        font-weight: 700;
        padding: 11px 18px;
        box-shadow: 0 10px 22px rgba(140, 90, 60, 0.24);
    }

    .product-primary-btn:hover {
        background: #744832;
        color: #ffffff;
    }

    .product-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 98px;
        padding: 20px;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.09);
        border: 1px solid rgba(75, 46, 43, 0.07);
    }

    .product-stat-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #fff1df;
        color: #b35c0c;
    }

    .product-stat-card--green .product-stat-icon {
        background: rgba(128, 111, 68, 0.14);
        color: #756434;
    }

    .product-stat-card--orange .product-stat-icon {
        background: rgba(255, 184, 77, 0.2);
        color: #b56b00;
    }

    .product-stat-card--red .product-stat-icon {
        background: rgba(220, 53, 69, 0.12);
        color: #c43845;
    }

    .product-stat-card p {
        color: #8a7771;
        margin: 0 0 4px;
        font-size: 13px;
    }

    .product-stat-card strong {
        display: block;
        color: #3f302b;
        font-size: 26px;
        line-height: 1;
    }

    .product-card {
        overflow: hidden;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.08);
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.09);
    }

    .product-card__image {
        position: relative;
        height: 210px;
        background: #f7eadc;
        overflow: hidden;
    }

    .product-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.25s ease;
    }

    .product-card:hover .product-card__image img {
        transform: scale(1.04);
    }

    .product-card__empty {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #a08d83;
        gap: 10px;
    }

    .product-stock-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        border-radius: 999px;
        padding: 7px 12px;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        background: #dc3545;
    }

    .product-stock-badge.is-ready {
        background: #756434;
    }

    .product-card__body {
        padding: 18px;
    }

    .product-card__top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: #9b877f;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .product-card h5 {
        min-height: 48px;
        margin: 0 0 8px;
        color: #3f302b;
        font-weight: 800;
        line-height: 1.25;
    }

    .product-card__desc {
        min-height: 44px;
        color: #806b63;
        font-size: 14px;
        margin-bottom: 14px;
    }

    .product-card__price {
        color: #b35c0c;
        font-size: 19px;
        font-weight: 800;
    }

    .product-card__actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 0 18px 18px;
    }

    .product-card__actions form {
        margin: 0;
    }

    .product-edit-btn,
    .product-delete-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 8px;
        font-weight: 700;
        padding: 9px 12px;
    }

    .product-edit-btn {
        background: #fff4df;
        color: #9b580c;
        border: 1px solid #f0d5ad;
    }

    .product-delete-btn {
        background: #fff0f1;
        color: #c43845;
        border: 1px solid #f2c7cd;
    }

    .product-empty-state {
        text-align: center;
        padding: 56px 24px;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.09);
    }

    .product-empty-state > span {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff1df;
        color: #b35c0c;
        font-size: 26px;
        margin-bottom: 16px;
    }

    .product-empty-state p {
        color: #806b63;
    }

    .product-empty-state--compact {
        padding: 36px 24px;
    }

    .product-section-heading {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: center;
        margin-bottom: 18px;
    }

    .product-section-heading span {
        color: #b35c0c;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .product-section-heading h4 {
        color: #3f302b;
        font-weight: 800;
        margin: 6px 0;
    }

    .product-section-heading p {
        color: #806b63;
        margin: 0;
    }

    .product-trash-table {
        overflow: hidden;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.08);
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.09);
    }

    .product-trash-table thead th {
        background: #fff4e8;
        color: #4b2e2b;
        border-bottom: 0;
        font-size: 13px;
        text-transform: uppercase;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .product-trash-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 220px;
    }

    .product-trash-thumb {
        width: 54px;
        height: 54px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 54px;
        overflow: hidden;
        border-radius: 8px;
        background: #f7eadc;
        color: #a08d83;
    }

    .product-trash-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-trash-item strong,
    .product-trash-item span {
        display: block;
    }

    .product-trash-item strong {
        color: #3f302b;
        font-weight: 800;
    }

    .product-trash-item span {
        color: #9b877f;
        font-size: 12px;
    }

    .product-trash-actions {
        display: inline-flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .product-trash-actions form {
        margin: 0;
    }

    .product-restore-btn,
    .product-force-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 8px;
        font-weight: 700;
        padding: 8px 12px;
    }

    .product-restore-btn {
        background: #eef8ec;
        color: #3f7a3b;
        border: 1px solid #cfe8ca;
    }

    .product-force-delete-btn {
        background: #fff0f1;
        color: #c43845;
        border: 1px solid #f2c7cd;
    }

    .product-pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.08);
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.08);
    }

    .product-pagination-summary {
        color: #806b63;
        font-size: 14px;
        font-weight: 700;
    }

    .product-pagination .pagination {
        gap: 8px;
        margin: 0;
    }

    .product-pagination .page-item .page-link {
        min-width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(75, 46, 43, 0.12);
        border-radius: 8px;
        background: #ffffff;
        color: #4b2e2b;
        font-weight: 700;
        box-shadow: 0 8px 18px rgba(75, 46, 43, 0.08);
    }

    .product-pagination .page-item.active .page-link {
        border-color: #8c5a3c;
        background: #8c5a3c;
        color: #ffffff;
    }

    .product-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        border-color: #c08552;
        background: #fff4e8;
        color: #8c4f16;
    }

    .product-pagination .page-item.disabled .page-link {
        background: #f2e5d8;
        color: #a8958d;
        box-shadow: none;
    }

    @media (max-width: 768px) {
        .product-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 24px;
        }

        .product-card__actions {
            grid-template-columns: 1fr;
        }

        .product-pagination-wrap {
            align-items: flex-start;
            flex-direction: column;
        }

        .product-pagination .pagination {
            flex-wrap: wrap;
        }

        .product-section-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .product-trash-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush
