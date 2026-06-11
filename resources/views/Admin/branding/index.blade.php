@extends('layouts.admin')  {{-- sesuaikan dengan layout admin kamu --}}

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Manajemen Homepage</h2>
            <p class="text-muted mb-0">Atur konten yang tampil di homepage guest dan customer.</p>
        </div>
        <a href="{{ route('admin.branding.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah Konten Baru
        </a>
    </div>

    <div class="card cms-admin-card js-reveal">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Logo</th>
                        <th>Nama Mitra</th>
                        <th>Nama Konten</th>
                        <th>Jenis</th>
                        <th>Urutan</th>
                        <th>Video</th>
                        <th>Tampil Di</th>
                        <th>Status</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brandings as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($item->logo_mitra)
                                <img src="{{ Storage::url($item->logo_mitra) }}" width="60" height="60" class="rounded" alt="">
                            @else
                                <span class="text-muted">No Logo</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->nama_mitra }}</strong></td>
                        <td>{{ $item->nama_konten }}</td>
                        <td>
                            <span class="badge bg-dark">{{ $item->content_type_label }}</span>
                        </td>
                        <td>{{ $item->urutan }}</td>
                        <td>
                            @if($item->video_konten)
                                <a href="{{ $item->video_konten }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fa fa-play"></i> Lihat Video
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $item->display_target_label }}</span>
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $item->username }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.branding.preview', $item) }}" class="btn btn-info btn-sm" target="_blank" title="Preview">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.branding.edit', $item) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.branding.destroy', $item) }}" method="POST" class="d-inline admin-confirm-form"
                                  data-confirm-title="Hapus Konten"
                                  data-confirm-message="Yakin ingin menghapus konten {{ $item->nama_konten }}?"
                                  data-confirm-action="Konten akan dihapus dari daftar branding dan edukasi admin."
                                  data-confirm-button='<i class="fa fa-trash me-1"></i> Ya, hapus konten'>
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">Belum ada data branding</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $brandings->links() }}
        </div>
    </div>
</div>
@endsection
