@extends('layouts.admin')

@section('title', 'Biaya Operasional')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<div class="container-fluid px-4 pb-5">
    <div class="admin-page-hero mb-4">
        <div>
            <span><i class="fa fa-calculator me-2"></i>Keuangan Toko</span>
            <h2>Biaya Operasional</h2>
            <p>Catat pengeluaran toko seperti bahan baku, listrik, sewa, kemasan, gaji, dan kebutuhan operasional lain.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm admin-metric-card">
                <div class="card-body">
                    <span>Total Biaya</span>
                    <h3>{{ $formatRupiah($totalBiaya) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm admin-metric-card">
                <div class="card-body">
                    <span>Bulan Ini</span>
                    <h3>{{ $formatRupiah($biayaBulanIni) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm admin-metric-card">
                <div class="card-body">
                    <span>Kategori Terbesar</span>
                    <h3>{{ $kategoriTerbesar->jenis_biaya ?? '-' }}</h3>
                    <small>{{ $kategoriTerbesar ? $formatRupiah($kategoriTerbesar->total) : 'Belum ada data' }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm admin-panel expense-form-card">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="mb-0">Tambah Biaya</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.biaya-operasional.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Jenis Biaya</label>
                            <input type="text" name="jenis_biaya" class="form-control" value="{{ old('jenis_biaya') }}" placeholder="Contoh: Bahan Baku" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Biaya</label>
                            <input type="text" name="nama_biaya" class="form-control" value="{{ old('nama_biaya') }}" placeholder="Contoh: Pembelian biji kopi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Biaya</label>
                            <input type="number" name="jumlah_biaya" class="form-control" value="{{ old('jumlah_biaya') }}" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan">{{ old('keterangan') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-brown w-100">
                            <i class="fa fa-plus me-1"></i> Simpan Biaya
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm admin-panel expense-table-card">
                <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Biaya Operasional</h5>
                    <small class="text-muted">{{ $biayaOperasional->total() }} data</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive expense-table-scroll">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Nama Biaya</th>
                                    <th>Jumlah</th>
                                    <th>Admin</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($biayaOperasional as $item)
                                    <tr>
                                        <td class="ps-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $item->jenis_biaya }}</span></td>
                                        <td>
                                            <strong class="d-block">{{ $item->nama_biaya }}</strong>
                                            <small class="text-muted">{{ $item->keterangan ?: '-' }}</small>
                                        </td>
                                        <td class="fw-semibold">{{ $formatRupiah($item->jumlah_biaya) }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editBiaya{{ $item->id_biaya }}">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <form action="{{ route('admin.biaya-operasional.destroy', $item) }}" method="POST" class="d-inline admin-confirm-form"
                                                  data-confirm-title="Hapus Biaya"
                                                  data-confirm-message="Yakin ingin menghapus data biaya ini?"
                                                  data-confirm-action="Data biaya akan dihapus dari daftar operasional."
                                                  data-confirm-button='<i class="fa fa-trash me-1"></i> Ya, hapus'>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada biaya operasional.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($biayaOperasional->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $biayaOperasional->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@foreach($biayaOperasional as $item)
    <div class="modal fade" id="editBiaya{{ $item->id_biaya }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.biaya-operasional.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Biaya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Biaya</label>
                            <input type="text" name="jenis_biaya" class="form-control" value="{{ $item->jenis_biaya }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Biaya</label>
                            <input type="text" name="nama_biaya" class="form-control" value="{{ $item->nama_biaya }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Biaya</label>
                            <input type="number" name="jumlah_biaya" class="form-control" value="{{ $item->jumlah_biaya }}" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ \Carbon\Carbon::parse($item->tanggal)->toDateString() }}" required>
                        </div>
                        <div>
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3">{{ $item->keterangan }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brown">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('styles')
<style>
    .admin-page-hero {
        padding: 30px 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(75, 46, 43, 0.96), rgba(126, 79, 47, 0.9));
        box-shadow: 0 18px 38px rgba(75, 46, 43, 0.14);
    }

    .admin-page-hero span {
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .admin-page-hero h2 {
        color: #ffffff;
        font-weight: 800;
        margin: 10px 0 8px;
    }

    .admin-page-hero p {
        color: rgba(255, 255, 255, 0.78);
        margin: 0;
    }

    .admin-metric-card,
    .admin-panel {
        border-radius: 8px;
        overflow: hidden;
    }

    .admin-metric-card span {
        color: #6b625c;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .admin-metric-card h3 {
        color: #2c1f1b;
        font-weight: 800;
        margin: 8px 0 0;
    }

    .admin-panel thead th {
        background: #fff4e8;
        color: #4b2e2b;
        font-size: 13px;
        text-transform: uppercase;
        border-bottom: 0;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .expense-form-card {
        position: sticky;
        top: 24px;
    }

    .expense-table-card {
        min-height: 100%;
    }

    .expense-table-scroll {
        max-height: 680px;
        overflow: auto;
    }

    .expense-table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        box-shadow: inset 0 -1px 0 #ead8c8;
    }

    .expense-table-scroll tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .btn-brown {
        background: #8C5A3C;
        color: #fff;
        border: 0;
    }

    .btn-brown:hover {
        background: #74482f;
        color: #fff;
    }

    @media (max-width: 1199px) {
        .expense-form-card {
            position: static;
        }

        .expense-table-scroll {
            max-height: none;
        }
    }
</style>
@endpush
