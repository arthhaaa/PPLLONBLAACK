<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ route('customer.home') }}">
                    <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black" class="site-brand-logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset customer-navbar" id="navbarSupportedContent">
				<ul class="nav navbar-nav menu_nav ml-auto customer-menu">
					<li class="nav-item {{ request()->routeIs('customer.home') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.home') }}">Home</a>
					</li>
					<li class="nav-item {{ request()->routeIs('customer.cart') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.cart') }}">Cart</a>
					</li>	
					<li class="nav-item {{ request()->routeIs('customer.product.*') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.product.index') }}">Product</a>
					</li>
					<li class="nav-item {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.orders') }}">Orders</a>
					</li>
					<li class="nav-item {{ request()->routeIs('customer.tracking') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.tracking') }}">Tracking</a>
					</li>
					<li class="nav-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('customer.profile') }}">Profile</a>
					</li>
				</ul>
				
				<!-- Icon Cart dan Search dipisah ke navbar-right -->
				<ul class="nav navbar-nav navbar-right customer-actions">
					<li class="nav-item">
						<a href="{{ route('customer.cart') }}" class="cart">
							<span class="ti-bag"></span>
						</a>
					</li>
					<li class="nav-item">
						<button class="search" type="button">
							<span class="lnr lnr-magnifier" id="search"></span>
						</button>
					</li>
					@auth
						<li class="nav-item">
							<form id="customer-header-logout-form" action="{{ route('logout') }}" method="POST" class="customer-header-logout-form js-logout-form">
								@csrf
								<button type="submit" class="customer-logout-btn" aria-label="Logout">
									<span class="lnr lnr-exit"></span>
									<span class="logout-text">Logout</span>
								</button>
							</form>
						</li>
					@endauth
				</ul>
				</div>
			</div>
			</nav>
		</div>
	</header>
<!-- End Header Area -->

@auth
<div class="logout-modal-overlay" id="logoutConfirmModal" aria-hidden="true">
	<div class="logout-modal" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
		<button type="button" class="logout-modal-close" data-logout-cancel aria-label="Tutup">
			<span class="lnr lnr-cross"></span>
		</button>
		<div class="logout-modal-icon">
			<span class="lnr lnr-exit"></span>
		</div>
		<h3 id="logoutConfirmTitle">Keluar dari akun?</h3>
		<p>Anda akan keluar dari sesi pelanggan Long Black saat ini.</p>
		<div class="logout-modal-actions">
			<button type="button" class="logout-modal-cancel" data-logout-cancel>Batal</button>
			<button type="button" class="logout-modal-confirm" id="confirmLogoutBtn">Logout</button>
		</div>
	</div>
</div>
@endauth
