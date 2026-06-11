<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">

                <!-- Logo -->
                <a class="navbar-brand logo_h" href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.home')) : route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Long Black" style="width: 155px; height: auto;">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#longblackMainNav"
                        aria-controls="longblackMainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse longblack-navbar" id="longblackMainNav">

                    <!-- Menu Navigasi -->
                    <ul class="nav navbar-nav menu_nav longblack-menu">
                        @auth
                        @if(auth()->user()->role === 'admin')
                            <!-- Menu Admin -->
                            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('admin.produk.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.produk.index') }}">Produk</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('admin.branding.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.branding.index') }}">Branding</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('admin.akun.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.akun.index') }}">Akun</a>
                            </li>
                        @else
                            <!-- Menu Customer -->
                            <li class="nav-item {{ request()->routeIs('customer.home') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('customer.home') }}">Home</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('customer.product.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('customer.product.index') }}">Shop</a>
                            </li>
                        @endif
                        @else
                            <!-- Menu Guest -->
                            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('home') }}">Home</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side -->
                    <ul class="nav navbar-nav navbar-right longblack-actions">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                                   data-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                    <span class="badge bg-success ms-1">{{ Auth::user()->role === 'admin' ? 'Admin' : 'Customer' }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('customer.profile') }}">Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" 
                                       onclick="event.preventDefault(); if(confirm('Yakin ingin logout?')) document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header Area -->

<!-- Form Logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    .header_area .main_box {
        min-height: 82px;
    }

    .header_area .navbar .container {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .header_area .logo_h {
        flex: 0 0 auto;
        margin-right: 0;
    }

    .longblack-navbar {
        min-width: 0;
    }

    .longblack-menu {
        flex: 1 1 auto;
        justify-content: center;
        gap: 18px;
        min-width: 0;
    }

    .longblack-actions {
        flex: 0 0 auto;
        margin-left: 20px;
        white-space: nowrap;
    }

    .longblack-menu .nav-link,
    .longblack-actions .nav-link {
        padding-left: 12px;
        padding-right: 12px;
        white-space: nowrap;
    }

    .longblack-actions .dropdown-menu {
        right: 0;
        left: auto;
    }

    @media (max-width: 991px) {
        .header_area .navbar .container {
            gap: 12px;
        }

        .longblack-navbar {
            flex-basis: 100%;
            margin-top: 14px;
        }

        .longblack-menu,
        .longblack-actions {
            align-items: flex-start;
            gap: 0;
            margin-left: 0;
        }

        .longblack-menu .nav-item,
        .longblack-actions .nav-item {
            width: 100%;
        }

        .longblack-menu .nav-link,
        .longblack-actions .nav-link {
            padding: 10px 0;
        }

        .longblack-actions .dropdown-menu {
            position: static;
            float: none;
            width: 100%;
        }
    }
</style>
