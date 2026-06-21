@extends('layouts.admin')  {{-- atau buat layout khusus admin jika belum ada --}}

@section('title', 'Profile - Administrator')

@section('content')
<div class="container-fluid py-5" style="background: #FFF8F0; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="background: white;">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success px-3 py-2">Admin</span>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0 text-dark">Administrator</h4>
                            <small class="text-muted">Long Black - Kopi Specialty</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="mb-4">
                                <div class="admin-profile-icon rounded-circle border border-3 border-light shadow-sm">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark">Admin</h5>
                        </div>

                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-medium text-muted" style="width: 140px;">Username</td>
                                    <td>: <strong>admin</strong></td>
                                </tr>
                                <tr>
                                    <td class="fw-medium text-muted">Email</td>
                                    <td>: admin@longblack.com</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium text-muted">No. Telepon</td>
                                    <td>: 081234567890</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium text-muted">Alamat</td>
                                    <td>: Jember, Jawa Timur</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium text-muted">Tanggal Bergabung</td>
                                    <td>: 03 May 2026</td>
                                </tr>
                            </table>

                            <div class="alert alert-light mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Profil ini bersifat read only (tidak dapat diubah)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-profile-icon {
        width: 160px;
        height: 160px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff4e8;
        color: #8C5A3C;
        font-size: 70px;
    }
</style>
@endpush
