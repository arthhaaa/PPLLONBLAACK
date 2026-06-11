@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="product-form-page container-fluid px-4 pb-5">
    <div class="product-form-hero mb-4 js-reveal">
        <div>
            <span class="product-kicker"><i class="fa fa-plus-circle me-2"></i>Produk Baru</span>
            <h2>Tambah Produk Baru</h2>
            <p>Isi detail produk dengan rapi agar katalog customer terlihat jelas dan siap dijual.</p>
        </div>
        <a href="{{ route('admin.produk.index') }}" class="btn product-light-btn">
            <i class="fa fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="product-form-card js-reveal">
                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Produk <span>*</span></label>
                            <input type="text"
                                   name="nama_produk"
                                   class="form-control @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk') }}"
                                   placeholder="Contoh: Arabica Argopuro Natural"
                                   required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga <span>*</span></label>
                            <div class="input-group product-input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                       name="harga_produk"
                                       class="form-control @error('harga_produk') is-invalid @enderror"
                                       value="{{ old('harga_produk') }}"
                                       min="0"
                                       placeholder="0"
                                       required>
                            </div>
                            @error('harga_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok <span>*</span></label>
                            <input type="number"
                                   name="stok_produk"
                                   class="form-control @error('stok_produk') is-invalid @enderror"
                                   value="{{ old('stok_produk', 0) }}"
                                   min="0"
                                   required>
                            @error('stok_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi Produk <span>*</span></label>
                        <textarea name="deskripsi_produk"
                                  class="form-control @error('deskripsi_produk') is-invalid @enderror"
                                  rows="6"
                                  placeholder="Ceritakan rasa, proses, ukuran, atau catatan penting produk..."
                                  required>{{ old('deskripsi_produk') }}</textarea>
                        @error('deskripsi_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file"
                               name="gambar_produk"
                               class="form-control js-image-preview-input @error('gambar_produk') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp"
                               data-preview-target="#productImagePreview">
                        <small class="product-help-text">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2MB.</small>
                        <div class="product-image-preview mt-3" id="productImagePreview">
                            <i class="fa fa-image"></i>
                        </div>
                        @error('gambar_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn product-submit-btn">
                            <i class="fa fa-save"></i>
                            <span>Simpan Produk</span>
                        </button>
                        <a href="{{ route('admin.produk.index') }}" class="btn product-cancel-btn">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="product-side-card js-reveal">
                <div class="product-side-card__icon">
                    <i class="fa fa-mug-hot"></i>
                </div>
                <h5>Tips Konten Produk</h5>
                <p>Gunakan foto terang, nama produk singkat, dan deskripsi yang menyebutkan rasa utama agar customer lebih cepat memilih.</p>
                <div class="product-side-list">
                    <span><i class="fa fa-check"></i> Nama mudah dicari</span>
                    <span><i class="fa fa-check"></i> Harga sudah final</span>
                    <span><i class="fa fa-check"></i> Stok sesuai gudang</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
