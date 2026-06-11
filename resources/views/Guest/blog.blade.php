<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/banner.css">
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

                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item active"><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <!-- ================== MENU ADMIN ================== -->
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin/dashboard') }}">Dashboard Admin</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Data Akun</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Branding & Edukasi</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.produk.index') }}">Data Produk</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Transaksi</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Biaya Operasional</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Laporan</a></li>
                            <!-- Menu Logout untuk Admin -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>

                        @else
                            <!-- ================== MENU PELANGGAN ================== -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('customer.cart') }}">Cart</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('customer.tracking') }}">Tracking</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('customer.profile') }}">Profile</a></li>
                            <!-- Menu Logout untuk Customer -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                        @endif

                    @else
                        <!-- ================== MENU GUEST (BELUM LOGIN) ================== -->
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endauth
                    </ul>

                    <!-- Icon Cart hanya untuk Pelanggan -->
                    <ul class="nav navbar-nav navbar-right">
                        @auth
                            @if(auth()->user()->role !== 'admin')
                                <li class="nav-item"><a href="{{ route('customer.cart') }}" class="cart"><span class="ti-bag"></span></a></li>
                            @endif
                        @endauth
                        <li class="nav-item">
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
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
    <!-- Start Banner Area -->
    <section class="banner-area">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            
            <!-- Left Content -->
            <div class="col-lg-6">
                <h1 class="page-title">Explore Long Black</h1>
                
                <div class="hero-branding">
                    <h2 class="typing">Long Black</h2>
                    <div class="decor-line"></div>
                    <p class="tagline">Best Coffee</p>
                </div>
            </div>

            <!-- Right Side - Bisa diisi form nanti -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <!-- Form akan ditempatkan di sini nanti -->
            </div>

        </div>
    </div>
    </section>
    <!-- End Banner Area -->

    <!--================Blog Categorie Area =================-->
    <section class="blog_categorie_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="categories_post">
                        <img src="img/blog/cat-post/cat-post-3.jpg" alt="post">
                        <div class="categories_details">
                            <div class="categories_text">
                                <a href="blog-details.html">
                                    <h5>Coffee Moments</h5>
                                </a>
                                <div class="border_line"></div>
                                <p>Enjoy every sip with the people you love</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="categories_post">
                        <img src="img/blog/cat-post/cat-post-2.jpg" alt="post">
                        <div class="categories_details">
                            <div class="categories_text">
                                <a href="blog-details.html">
                                    <h5>Coffee & Conversation</h5>
                                </a>
                                <div class="border_line"></div>
                                <p>Deep talks start with a good cup of coffee</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="categories_post">
                        <img src="img/blog/cat-post/cat-post-1.jpg" alt="post">
                        <div class="categories_details">
                            <div class="categories_text">
                                <a href="blog-details.html">
                                    <h5>Our Coffee Journey</h5>
                                </a>
                                <div class="border_line"></div>
                                <p>From farm to cup — traceability & quality</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================Blog Categorie Area =================-->

<!--================Blog Area =================-->
<section class="blog_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog_left_sidebar">

                    <!-- Blog Item 1 -->
                    <article class="row blog_item">
                        <div class="col-md-3">
                            <div class="blog_info text-right">
                                <div class="post_tag">
                                    <a href="#">Brewing Guide,</a>
                                    <a class="active" href="#">Lifestyle</a>
                                </div>
                                <ul class="blog_meta list">
                                    <li><a href="#">Long Black Team<i class="lnr lnr-user"></i></a></li>
                                    <li><a href="#">28 Apr, 2026<i class="lnr lnr-calendar-full"></i></a></li>
                                    <li><a href="#">2.4K Views<i class="lnr lnr-eye"></i></a></li>
                                    <li><a href="#">12 Comments<i class="lnr lnr-bubble"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="blog_post">
                                <img src="img\blog\artikel\pour-over.jpg" alt="Pour Over Coffee">
                                <div class="blog_details">
                                    <a href="#">
                                        <h2>The Perfect Pour Over: Teknik Seduh yang Menghadirkan Rasa Terbaik</h2>
                                    </a>
                                    <p>Discover the art of manual brewing with pour over. Pelajari langkah demi langkah cara menyeduh kopi yang kaya rasa dan aromatik di rumah.</p>
                                    <a href="#" class="white_bg_btn">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Blog Item 2 -->
                    <article class="row blog_item">
                        <div class="col-md-3">
                            <div class="blog_info text-right">
                                <div class="post_tag">
                                    <a href="#">Origin,</a>
                                    <a class="active" href="#">Single Origin</a>
                                </div>
                                <ul class="blog_meta list">
                                    <li><a href="#">Long Black Team<i class="lnr lnr-user"></i></a></li>
                                    <li><a href="#">25 Apr, 2026<i class="lnr lnr-calendar-full"></i></a></li>
                                    <li><a href="#">1.8K Views<i class="lnr lnr-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="blog_post">
                                <img src="img\blog\artikel\gayo.jpg" alt="Kopi Gayo">
                                <div class="blog_details">
                                    <a href="#">
                                        <h2>Kopi Gayo Sumatra: Mengapa Menjadi Favorit Pecinta Kopi Indonesia</h2>
                                    </a>
                                    <p>Perjalanan biji kopi dari dataran tinggi Gayo hingga menjadi secangkir Long Black yang kaya body dan rasa cokelat.</p>
                                    <a href="#" class="white_bg_btn">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Blog Item 3 -->
                    <article class="row blog_item">
                        <div class="col-md-3">
                            <div class="blog_info text-right">
                                <div class="post_tag">
                                    <a href="#">Lifestyle,</a>
                                    <a class="active" href="#">Coffee Culture</a>
                                </div>
                                <ul class="blog_meta list">
                                    <li><a href="#">Long Black Team<i class="lnr lnr-user"></i></a></li>
                                    <li><a href="#">20 Apr, 2026<i class="lnr lnr-calendar-full"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="blog_post">
                                <img src="img\blog\artikel\coffee-moment.jpg" alt="Coffee Moment">
                                <div class="blog_details">
                                    <a href="#">
                                        <h2>Coffee Moments: Menikmati Hidup Satu Seduhan pada Satu Waktu</h2>
                                    </a>
                                    <p>Bagaimana secangkir kopi dapat menjadi ritual harian yang membawa ketenangan dan inspirasi.</p>
                                    <a href="#" class="white_bg_btn">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Pagination -->
                    <nav class="blog-pagination justify-content-center d-flex">
                        <ul class="pagination">
                            <li class="page-item"><a href="#" class="page-link"><span class="lnr lnr-chevron-left"></span></a></li>
                            <li class="page-item"><a href="#" class="page-link">01</a></li>
                            <li class="page-item active"><a href="#" class="page-link">02</a></li>
                            <li class="page-item"><a href="#" class="page-link">03</a></li>
                            <li class="page-item"><a href="#" class="page-link"><span class="lnr lnr-chevron-right"></span></a></li>
                        </ul>
                    </nav>

                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    
                    <!-- Search -->
                    <aside class="single_sidebar_widget search_widget">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari artikel kopi...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="lnr lnr-magnifier"></i></button>
                            </span>
                        </div>
                    </aside>

                    <!-- About Long Black -->
                    <aside class="single_sidebar_widget author_widget">
                        <img class="author_img rounded-circle" src="img\fav.png" alt="Long Black" style="width: 85px; height: 120px; object-fit: cover;">
                        <h4>Long Black</h4>
                        <p>Specialty Coffee Roaster</p>
                        <p>Kami menyajikan pengalaman kopi terbaik dari biji pilihan terbaik Indonesia dan dunia.</p>
                        <div class="br"></div>
                    </aside>

                    <!-- Popular Posts -->
                    <aside class="single_sidebar_widget popular_post_widget">
                        <h3 class="widget_title">Artikel Populer</h3>
                        <div class="media post_item">
                            <img src="img/blog/popular/pour-over-small.jpg" alt="">
                            <div class="media-body">
                                <a href="#"><h3>5 Cara Seduh Pour Over di Rumah</h3></a>
                                <p>2 hari yang lalu</p>
                            </div>
                        </div>
                        <!-- Tambahkan 2-3 item lagi sesuai kebutuhan -->
                    </aside>

                    <!-- Categories -->
                    <aside class="single_sidebar_widget post_category_widget">
                        <h4 class="widget_title">Kategori</h4>
                        <ul class="list cat-list">
                            <li><a href="#">Single Origin <span>18</span></a></li>
                            <li><a href="#">Brewing Guide <span>24</span></a></li>
                            <li><a href="#">Coffee Origin <span>15</span></a></li>
                            <li><a href="#">Lifestyle <span>31</span></a></li>
                            <li><a href="#">Behind The Roast <span>9</span></a></li>
                        </ul>
                    </aside>

                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Blog Area =================-->

	<footer class="footer-area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>About Us</h6>
						<p>
							RKB  Jember atau Rumah Kopi Banjarsengon rumah produksi kopiSpecialty origin pegunungan Argopuro Lereng Selatan Jember - Jawa Timur.
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
