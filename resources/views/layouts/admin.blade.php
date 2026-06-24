<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Long Black Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('img/long-black-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/long-black-logo.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Header Admin -->
    <header class="admin-mobile-header">
        <a href="{{ route('admin.dashboard') }}" class="admin-mobile-brand">
            <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black">
            <span>Long Black Admin</span>
        </a>
        <button class="admin-mobile-menu-btn" type="button" aria-label="Buka menu admin" aria-expanded="false" aria-controls="adminSidebar">
            <i class="fa fa-bars"></i>
        </button>
    </header>

    <!-- Sidebar Admin -->
    <div class="admin-sidebar-backdrop" data-admin-sidebar-close hidden></div>
    @include('partials.admin-sidebar')

    <main class="admin-main">
        @if($errors->any())
            <div class="admin-validation-alert" role="alert">
                <div class="admin-validation-alert__icon">
                    <i class="fa fa-circle-exclamation"></i>
                </div>
                <div>
                    <strong>Form belum lengkap.</strong>
                    <p>Periksa kembali data yang wajib diisi sebelum menyimpan.</p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <div id="adminClientValidationAlert" class="admin-client-validation-alert" role="alert" hidden>
        <i class="fa fa-circle-exclamation"></i>
        <span>Harap lengkapi semua field yang wajib diisi sebelum menyimpan.</span>
    </div>

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
    <script>
        function showAdminValidationAlert(message) {
            const alertBox = document.getElementById('adminClientValidationAlert');

            if (!alertBox) {
                return;
            }

            const textNode = alertBox.querySelector('span');
            if (textNode && message) {
                textNode.textContent = message;
            }

            alertBox.hidden = false;
            window.clearTimeout(Number(alertBox.dataset.hideTimer || 0));
            alertBox.dataset.hideTimer = String(window.setTimeout(function () {
                alertBox.hidden = true;
            }, 4200));
        }

        function ensureInvalidFeedback(field) {
            if (!field || field.type === 'hidden' || field.type === 'checkbox' || field.type === 'radio') {
                return;
            }

            const parent = field.parentElement;
            const next = field.nextElementSibling;
            const hasFeedback = next && next.classList.contains('invalid-feedback');

            if (parent && !hasFeedback) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = field.dataset.requiredMessage || 'Field ini wajib diisi.';
                field.insertAdjacentElement('afterend', feedback);
            }
        }

        document.addEventListener('invalid', function (event) {
            const field = event.target;

            if (!(field instanceof HTMLElement)) {
                return;
            }

            const form = field.closest('form');
            if (form) {
                form.classList.add('was-validated');
            }

            ensureInvalidFeedback(field);
            showAdminValidationAlert('Harap lengkapi semua field yang wajib diisi sebelum menyimpan.');
        }, true);

        document.addEventListener('submit', function (event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || form.checkValidity()) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            form.classList.add('was-validated');

            form.querySelectorAll(':invalid').forEach(ensureInvalidFeedback);
            showAdminValidationAlert('Harap lengkapi semua field yang wajib diisi sebelum menyimpan.');

            const firstInvalid = form.querySelector(':invalid');
            if (firstInvalid) {
                firstInvalid.focus({ preventScroll: true });
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            form.reportValidity();
        }, true);

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.admin-mobile-menu-btn');
            const backdrop = document.querySelector('[data-admin-sidebar-close]');
            const closeButtons = document.querySelectorAll('[data-admin-sidebar-close]');

            if (!sidebar || !toggle || !backdrop) {
                return;
            }

            function setSidebar(open) {
                sidebar.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                backdrop.hidden = !open;
                document.body.classList.toggle('admin-menu-open', open);
            }

            toggle.addEventListener('click', function () {
                setSidebar(!sidebar.classList.contains('is-open'));
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    setSidebar(false);
                });
            });

            sidebar.querySelectorAll('a.nav-link, .admin-sidebar-subitem').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.matchMedia('(max-width: 768px)').matches) {
                        setSidebar(false);
                    }
                });
            });

            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setSidebar(false);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
