@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="product-form-page container-fluid px-4 pb-5">
    <div class="product-form-hero mb-4">
        <div>
            <span class="product-kicker"><i class="fa fa-pen-to-square me-2"></i>Edit Produk</span>
            <h2>{{ $produk->nama_produk }}</h2>
            <p>Perbarui detail produk, stok, dan gambar agar informasi di katalog tetap akurat.</p>
        </div>
        <a href="{{ route('admin.produk.index') }}" class="btn product-light-btn">
            <i class="fa fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="product-form-card">
            <form action="{{ route('admin.produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk <span>*</span></label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Harga <span>*</span></label>
                            <div class="input-group product-input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_produk" class="form-control @error('harga_produk') is-invalid @enderror"
                                       value="{{ old('harga_produk', $produk->harga_produk) }}" min="0" required>
                            </div>
                            @error('harga_produk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stok <span>*</span></label>
                            <input type="number" name="stok_produk" class="form-control @error('stok_produk') is-invalid @enderror"
                                   value="{{ old('stok_produk', $produk->stok_produk) }}" min="0" required>
                            @error('stok_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi Produk <span>*</span></label>
                    <textarea name="deskripsi_produk" class="form-control @error('deskripsi_produk') is-invalid @enderror" rows="6" required>{{ old('deskripsi_produk', $produk->deskripsi_produk) }}</textarea>
                    @error('deskripsi_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Ganti Gambar Produk</label>
                    <input type="file" name="gambar_produk" class="form-control @error('gambar_produk') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                    <small class="product-help-text">Kosongkan jika tidak ingin mengganti gambar. Format JPG, JPEG, PNG, atau WEBP maksimal 2MB.</small>
                    @error('gambar_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn product-submit-btn">
                        <i class="fa fa-save"></i>
                        <span>Update Produk</span>
                    </button>
                    <a href="{{ route('admin.produk.index') }}" class="btn product-cancel-btn">Batal</a>
                </div>
            </form>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="product-preview-card">
                <span class="product-preview-label">Preview Produk</span>
                <div class="product-preview-image">
                    @if($produk->gambar_produk)
                        <img src="{{ Storage::url($produk->gambar_produk) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <div>
                            <i class="fa fa-image"></i>
                            <span>Belum ada gambar</span>
                        </div>
                    @endif
                </div>
                <div class="product-preview-body">
                    <span class="product-preview-code">PRD-{{ str_pad($produk->id_produk, 4, '0', STR_PAD_LEFT) }}</span>
                    <h5>{{ $produk->nama_produk }}</h5>
                    <p>Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</p>
                    <strong class="{{ $produk->stok_produk > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $produk->stok_produk > 0 ? $produk->stok_produk . ' stok tersedia' : 'Stok habis' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-form-page {
        color: #3f302b;
    }

    .product-form-hero,
    .product-form-card,
    .product-preview-card {
        border-radius: 8px;
        box-shadow: 0 14px 35px rgba(75, 46, 43, 0.1);
    }

    .product-form-hero {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: center;
        padding: 28px 32px;
        background:
            linear-gradient(135deg, rgba(75, 46, 43, 0.96), rgba(126, 79, 47, 0.9)),
            radial-gradient(circle at 88% 12%, rgba(73, 169, 137, 0.45), transparent 28%);
    }

    .product-kicker {
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-form-hero h2 {
        color: #ffffff;
        font-weight: 800;
        margin: 10px 0 8px;
    }

    .product-form-hero p {
        color: rgba(255, 255, 255, 0.78);
        margin: 0;
    }

    .product-light-btn,
    .product-submit-btn,
    .product-cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border-radius: 8px;
        font-weight: 700;
        padding: 11px 18px;
    }

    .product-light-btn {
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.28);
    }

    .product-light-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .product-form-card,
    .product-preview-card {
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.08);
        padding: 28px;
    }

    .product-form-card .form-label {
        color: #4b2e2b;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .product-form-card .form-label span {
        color: #dc3545;
    }

    .product-form-card .form-control,
    .product-input-group .input-group-text {
        border-radius: 8px;
        border-color: #ead8c4;
        padding: 11px 13px;
    }

    .product-input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .product-form-card .form-control:focus {
        border-color: #49a989;
        box-shadow: 0 0 0 0.2rem rgba(73, 169, 137, 0.14);
    }

    .product-help-text {
        display: block;
        color: #8a7771;
        margin-top: 8px;
        font-size: 13px;
    }

    .product-submit-btn {
        border: 0;
        background: #49a989;
        color: #ffffff;
    }

    .product-submit-btn:hover {
        background: #3d9679;
        color: #ffffff;
    }

    .product-cancel-btn {
        background: #f3e7da;
        color: #5f4a43;
        border: 1px solid #e4d0bc;
    }

    .product-preview-card {
        position: sticky;
        top: 105px;
        overflow: hidden;
        padding: 0;
    }

    .product-preview-label {
        display: inline-flex;
        margin: 20px 20px 14px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(73, 169, 137, 0.14);
        color: #2e8b70;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .product-preview-image {
        height: 245px;
        margin: 0 20px;
        overflow: hidden;
        border-radius: 8px;
        background: #f7eadc;
    }

    .product-preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-preview-image div {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #9b877f;
    }

    .product-preview-image i {
        font-size: 34px;
    }

    .product-preview-body {
        padding: 20px;
    }

    .product-preview-code {
        color: #9b877f;
        font-size: 12px;
        font-weight: 700;
    }

    .product-preview-body h5 {
        color: #3f302b;
        font-weight: 800;
        margin: 8px 0;
    }

    .product-preview-body p {
        color: #b35c0c;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .product-form-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 24px;
        }
    }
</style>
@endpush
