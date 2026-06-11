@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Konten Homepage</h2>
            <p class="text-muted mb-0">Perubahan konten aktif akan langsung memengaruhi homepage.</p>
        </div>
        <a href="{{ route('admin.branding.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card cms-admin-card js-reveal">
        <div class="card-body">
            <form action="{{ route('admin.branding.update', $branding) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Label / Mitra</label>
                            <input type="text" name="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" value="{{ old('nama_mitra', $branding->nama_mitra) }}" required>
                            @error('nama_mitra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Judul Konten</label>
                            <input type="text" name="nama_konten" class="form-control @error('nama_konten') is-invalid @enderror" value="{{ old('nama_konten', $branding->nama_konten) }}" required>
                            @error('nama_konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Konten</label>
                            <select name="jenis_konten" class="form-control @error('jenis_konten') is-invalid @enderror" required>
                                @foreach($contentTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('jenis_konten', $branding->jenis_konten) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis_konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Link Tujuan</label>
                            <input type="url" name="link_konten" class="form-control @error('link_konten') is-invalid @enderror" value="{{ old('link_konten', $branding->link_konten) }}" placeholder="https://... atau link referensi eksternal">
                            @error('link_konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Konten</label>
                    <textarea name="deskripsi_konten" class="form-control @error('deskripsi_konten') is-invalid @enderror" rows="4">{{ old('deskripsi_konten', $branding->deskripsi_konten) }}</textarea>
                    @error('deskripsi_konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', $branding->urutan) }}" min="0" max="999">
                            <small class="text-muted">Angka kecil tampil lebih dahulu di homepage.</small>
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Gambar / Logo Konten Saat Ini</label>
                    @if($branding->logo_mitra)
                        <img src="{{ Storage::url($branding->logo_mitra) }}" class="cms-current-image mb-2 rounded" alt="{{ $branding->nama_mitra }}">
                    @endif
                    <input type="file" name="logo_mitra" class="form-control js-image-preview-input @error('logo_mitra') is-invalid @enderror" accept="image/*" data-preview-target="#brandingImagePreview">
                    <div class="cms-image-preview mt-3" id="brandingImagePreview">
                        <span class="lnr lnr-picture"></span>
                    </div>
                    @error('logo_mitra')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Video Konten</label>
                    <input type="url" name="video_konten" class="form-control @error('video_konten') is-invalid @enderror" value="{{ old('video_konten', $branding->video_konten) }}">
                    @error('video_konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tampilkan Di</label>
                            <select name="tampil_di" class="form-control @error('tampil_di') is-invalid @enderror" required>
                                <option value="both" {{ old('tampil_di', $branding->tampil_di) === 'both' ? 'selected' : '' }}>Guest & Customer</option>
                                <option value="guest" {{ old('tampil_di', $branding->tampil_di) === 'guest' ? 'selected' : '' }}>Guest saja</option>
                                <option value="customer" {{ old('tampil_di', $branding->tampil_di) === 'customer' ? 'selected' : '' }}>Customer saja</option>
                            </select>
                            @error('tampil_di')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label d-block">Status Konten</label>
                            <div class="form-check mt-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" {{ old('is_active', $branding->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Aktif dan tampil di homepage</label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Konten</button>
                <a href="{{ route('admin.branding.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
