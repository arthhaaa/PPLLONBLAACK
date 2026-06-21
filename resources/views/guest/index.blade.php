<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="icon" type="image/png" href="{{ asset('img/long-black-logo.png') }}">
	<link rel="shortcut icon" type="image/png" href="{{ asset('img/long-black-logo.png') }}">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Long Black</title>
	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="{{ asset('css/linearicons.css') }}">
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
	<link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
	<link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.css') }}">
	<link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.skinFlat.css') }}">
	<link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/customer-polish.css') }}">
</head>

<body>

<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('customer.home')) : route('home') }}">
                    <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black" class="site-brand-logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset customer-navbar" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto customer-menu">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <!-- ================== MENU ADMIN ================== -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>

                            @else
                                <!-- ================== MENU PELANGGAN ================== -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customer.home') }}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customer.product.index') }}">Product</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customer.orders') }}">Orders</a>
                                </li>
                            @endif

                            <!-- Logout untuk semua user yang login -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); 
                                   if(confirm('Yakin ingin logout?')) document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        @else
                            <!-- ================== MENU GUEST ================== -->
                            <li class="nav-item active"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @endauth
                    </ul>

                    <!-- Icon Cart hanya untuk Pelanggan -->
                    <ul class="nav navbar-nav navbar-right customer-actions">
                        @auth
                            @if(auth()->user()->role !== 'admin')
                                <li class="nav-item"><a href="{{ route('customer.cart') }}" class="cart"><span class="ti-bag"></span></a></li>
                            @endif
                        @endauth
                        <li class="nav-item">
                            <button class="search" type="button"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header Area -->

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
	@include('partials.homepage-hero')
@include('partials.homepage-branding')
	@include('partials.homepage-products', ['products' => $featuredProducts, 'requiresLogin' => true])
	<!-- start footer Area -->
	@php
		$footerProfile = isset($homepageBrandings) ? $homepageBrandings->firstWhere('jenis_konten', 'profil_toko') : null;
	@endphp
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>About Us</h6>
						<p>
							{{ $footerProfile?->deskripsi_konten ?: 'Profil toko Long Black belum diisi dari admin.' }}
						</p>
					</div>
				</div>
				<div class="col-lg-4  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Email</h6>
						<p>Send email</p>
						<div class="" id="mc_embed_signup">

							<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
							 method="get" class="form-inline">

								<div class="d-flex flex-row">

									<input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
									 required="" type="email">


									<button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
									<div style="position: absolute; left: -5000px;">
										<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
									</div>

									<!-- <div class="col-lg-4 col-md-4">
												<button class="bb-btn btn"><span class="lnr lnr-arrow-right"></span></button>
											</div>  -->
								</div>
								<div class="info"></div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget mail-chimp">
						<h6 class="mb-20">Instagram Feed</h6>
						@include('partials.instagram-feed')
					</div>
				</div>
			</div>
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
			</div>
		</div>
	</footer>
	<!-- End footer Area -->

	<script src="{{ asset('js/vendor/jquery-2.2.4.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/jquery.ajaxchimp.min.js') }}"></script>
	<script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
	<script src="{{ asset('js/jquery.sticky.js') }}"></script>
	<script src="{{ asset('js/nouislider.min.js') }}"></script>
	<script src="{{ asset('js/countdown.js') }}"></script>
	<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
	<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="{{ asset('js/gmaps.min.js') }}"></script>
	<script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
