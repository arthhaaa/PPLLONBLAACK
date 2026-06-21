@extends('layouts.admin')

@section('content')
<div class="account-admin-page container-fluid px-4 pb-5">
    <div class="account-hero mb-4">
        <div class="account-hero__content">
            <span class="account-kicker"><i class="fa fa-users me-2"></i>Data Akun</span>
            <h2>Daftar Akun Pelanggan</h2>
            <p>Pantau data pelanggan Long Black dari identitas, kontak, alamat, sampai tanggal pendaftaran.</p>
        </div>
        <div class="account-total-pill">
            <strong>{{ $users->total() }}</strong>
            <span>Pelanggan</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<div class="card account-table-card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>                    {{-- ← Diperbaiki --}}
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telp ?? '-' }}</td>
                        <td>{{ Str::limit($user->alamat, 45) }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
    .account-admin-page {
        color: #3f302b;
    }

    .account-hero {
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

    .account-hero::after {
        content: "";
        position: absolute;
        right: 34px;
        bottom: -34px;
        width: 150px;
        height: 150px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
    }

    .account-hero__content,
    .account-total-pill {
        position: relative;
        z-index: 1;
    }

    .account-kicker {
        display: inline-flex;
        align-items: center;
        color: #f8d9b8;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .account-hero h2 {
        color: #ffffff;
        font-size: 38px;
        font-weight: 900;
        margin: 10px 0 8px;
    }

    .account-hero p {
        color: rgba(255, 255, 255, 0.78);
        max-width: 720px;
        margin: 0;
    }

    .account-total-pill {
        min-width: 160px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 18px 22px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff;
        box-shadow: 0 12px 24px rgba(48, 30, 25, 0.14);
    }

    .account-total-pill strong {
        font-size: 34px;
        line-height: 1;
    }

    .account-total-pill span {
        margin-top: 6px;
        color: #f8d9b8;
        font-weight: 800;
    }

    .account-table-card {
        overflow: hidden;
        border: 1px solid rgba(75, 46, 43, 0.08);
        border-radius: 8px;
        box-shadow: 0 12px 30px rgba(75, 46, 43, 0.09) !important;
    }

    .account-table-card thead th {
        background: #fff4e8;
        color: #4b2e2b;
        border-bottom: 0;
        font-size: 13px;
        text-transform: uppercase;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .account-table-card tbody td {
        border-color: rgba(75, 46, 43, 0.08);
        padding-top: 14px;
        padding-bottom: 14px;
    }

    @media (max-width: 768px) {
        .account-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 24px;
        }

        .account-total-pill {
            width: 100%;
            align-items: flex-start;
        }
    }
</style>
@endpush
