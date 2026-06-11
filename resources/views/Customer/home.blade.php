<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="icon" type="image/png" href="{{ asset('img/fav.png') }}">
	<link rel="shortcut icon" type="image/png" href="{{ asset('img/fav.png') }}">
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
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="{{ asset('css/customer-polish.css') }}">
</head>

<body>

<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="" style="width: 155px; height: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
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
	@include('partials.homepage-products', ['products' => $latestProducts, 'detailRouteName' => 'customer.product.show', 'shopRouteName' => 'customer.product.index'])
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
						<h6>Seng Sabar</h6>
						<p>Enteni yoh bakal tak kerjake </p>
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
				<div class="col-lg-2 col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Follow Us</h6>
						<p>Let us be social</p>
						<div class="footer-social d-flex align-items-center">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-dribbble"></i></a>
							<a href="#"><i class="fa fa-behance"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
			</div>
		</div>
	</footer>
	<!-- End footer Area -->

	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/countdown.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
