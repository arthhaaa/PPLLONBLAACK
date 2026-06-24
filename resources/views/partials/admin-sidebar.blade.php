<!-- Admin Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
    
    <div class="admin-sidebar-brand p-4 border-bottom border-light border-opacity-10">
        <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('img/long-black-logo.png') }}" class="admin-sidebar-logo" alt="Long Black">
            <div class="ms-3">
                <h5 class="mb-0 text-white fw-bold">LONG BLACK</h5>
            </div>
        </a>
        <button class="admin-sidebar-close" type="button" aria-label="Tutup menu admin" data-admin-sidebar-close>
            <i class="fa fa-times"></i>
        </button>
    </div>

    <div class="admin-sidebar-menu p-3">
        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link text-white {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-3"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.akun.index') }}" class="nav-link text-white {{ request()->is('admin/akun*') ? 'active' : '' }}">
                    <i class="fas fa-users me-3"></i> Data Akun
                </a>
            </li>

            <li class="nav-item admin-sidebar-group">
                <button class="nav-link text-white admin-sidebar-toggle {{ request()->is('admin/branding*') ? 'active' : '' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#brandingMenu"
                        aria-expanded="{{ request()->is('admin/branding*') ? 'true' : 'false' }}"
                        aria-controls="brandingMenu">
                    <span><i class="fas fa-palette me-3"></i> Branding & Edukasi</span>
                    <i class="fas fa-chevron-down admin-sidebar-chevron"></i>
                </button>
                <div class="collapse {{ request()->is('admin/branding*') ? 'show' : '' }}" id="brandingMenu">
                    <div class="admin-sidebar-submenu">
                        <a href="{{ route('admin.branding.index') }}" class="admin-sidebar-subitem {{ request()->routeIs('admin.branding.index') ? 'active' : '' }}">
                            <i class="fas fa-list"></i> Kelola Konten
                        </a>
                        <a href="{{ route('admin.branding.create') }}" class="admin-sidebar-subitem {{ request()->routeIs('admin.branding.create') ? 'active' : '' }}">
                            <i class="fas fa-plus"></i> Tambah Konten
                        </a>
                        <a href="{{ route('admin.branding.live-preview') }}" class="admin-sidebar-subitem {{ request()->routeIs('admin.branding.live-preview') || request()->routeIs('admin.branding.preview') ? 'active' : '' }}">
                            <i class="fas fa-eye"></i> Live Preview
                        </a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.produk.index') }}" class="nav-link text-white {{ request()->is('admin/produk*') ? 'active' : '' }}">
                    <i class="fas fa-coffee me-3"></i> Data Produk
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.pesanan.index') }}" class="nav-link text-white {{ request()->is('admin/pesanan*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-3"></i> Pesanan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.biaya-operasional.index') }}" class="nav-link text-white {{ request()->is('admin/biaya-operasional*') ? 'active' : '' }}">
                    <i class="fas fa-calculator me-3"></i> Biaya Operasional
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.laporan.index') }}" class="nav-link text-white {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-3"></i> Laporan
                </a>
            </li>

            <hr class="border-light border-opacity-10 my-3">

            <!-- Quick Links -->
            <li class="nav-item">
                <a href="{{ url('/admin/profile') }}" class="nav-link text-white">
                    <i class="fas fa-user-circle me-3"></i> Profile Admin
                </a>
            </li>

        </ul>
    </div>

<!-- Logout Section -->
    <div class="admin-sidebar-footer p-4 border-top border-light border-opacity-10">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <small class="opacity-75">Logged in as</small><br>
                <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong>
            </div>
            
            <!-- Form Logout -->
            <form action="{{ route('logout') }}" method="POST" class="admin-confirm-form"
                  data-confirm-title="Logout Admin"
                  data-confirm-message="Yakin ingin keluar dari dashboard admin?"
                  data-confirm-action="Sesi admin akan diakhiri dan Anda akan kembali ke halaman login."
                  data-confirm-button='<i class="fas fa-sign-out-alt me-1"></i> Ya, logout'>
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
