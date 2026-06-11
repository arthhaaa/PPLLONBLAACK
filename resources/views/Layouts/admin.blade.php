<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Long Black Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('img/fav.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/fav.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Header Admin -->

    <!-- Sidebar Admin -->
    @include('partials.admin-sidebar')

    <main class="admin-main">
        @yield('content')
    </main>

    @if(session('success'))
        <div class="admin-toast-wrap">
            <div id="adminSuccessToast" class="toast admin-toast" role="status" aria-live="polite" aria-atomic="true" data-bs-delay="3500">
                <div class="toast-header">
                    <span class="admin-toast-icon">
                        <i class="fa fa-check"></i>
                    </span>
                    <strong class="me-auto">Berhasil</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Tutup"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade admin-confirm-modal" id="adminConfirmModal" tabindex="-1" aria-labelledby="adminConfirmTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminConfirmTitle">Konfirmasi Aksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <span class="admin-confirm-icon">
                            <i class="fa fa-triangle-exclamation"></i>
                        </span>
                        <div>
                            <p class="fw-semibold mb-1" id="adminConfirmMessage">Apakah Anda yakin ingin melanjutkan?</p>
                            <p class="admin-confirm-action" id="adminConfirmAction">Aksi ini akan memproses data admin.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="adminConfirmButton">
                        <i class="fa fa-check me-1"></i> Ya, lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
