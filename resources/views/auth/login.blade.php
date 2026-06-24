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

        <!--CSS -->
        <link rel="stylesheet" href="{{ asset('css/linearicons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
        <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/customer-polish.css') }}">
    </head>

    <body class="auth-page">

 <!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <a class="navbar-brand logo_h" href="{{ url('/') }}">
                    <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black" class="site-brand-logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset customer-navbar" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto customer-menu">
                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}"><a class="nav-link" href="{{ route('home') }}">Home</a></li>

                        @auth
                            @if(auth()->user()->role === 'admin')
                                <!-- ================== MENU ADMIN ================== -->
                                <li class="nav-item"><a class="nav-link" href="{{ url('/admin/dashboard') }}">Dashboard Admin</a></li>

                            @else
                                <!-- ================== MENU PELANGGAN ================== -->
                                <li class="nav-item"><a class="nav-link" href="{{ route('customer.home') }}">Home</a></li>
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
                            <li class="nav-item active"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
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
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- End Header Area -->

    <!-- Start Banner Area -->
        <section class="banner-area auth-banner">
        <div class="auth-banner-decor" aria-hidden="true">
            <span class="coffee-bean auth-bean auth-bean--one"></span>
            <span class="coffee-bean auth-bean auth-bean--two"></span>
            <span class="coffee-bean auth-bean auth-bean--three"></span>
            <span class="auth-steam auth-steam--one"></span>
            <span class="auth-steam auth-steam--two"></span>
        </div>
        <div class="container">
            <div class="row min-vh-100 align-items-center">
                
                <!-- Left Content -->
                <div class="col-lg-6">
                    <h1 class="page-title">Login</h1>
                    
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

    <!--================Login Box Area =================-->
    <section class="login_box_area section_gap">
        <div class="auth-form-decor" aria-hidden="true">
            <span class="coffee-bean auth-bean auth-bean--four"></span>
            <span class="coffee-bean auth-bean auth-bean--five"></span>
        </div>
        <div class="container">
            <div class="row" id="auth-container">

                <!-- IMAGE SIDE -->
                <div class="col-lg-6" id="image-side">
                    <div class="login_box_img">
                        <img class="img-fluid" src="{{ asset('img/login.jpg') }}" alt="">
                        <div class="hover" id="hover-layer">
                            <h4 id="image-title">Selamat Datang</h4>
                            <p id="image-text">Kemajuan sains dan teknologi terus terjadi setiap harinya...</p>
                            <a class="primary-btn" id="toggle-btn" href="#">Create an Account</a>
                        </div>
                    </div>
                </div>
                <!-- FORM SIDE -->
                <div class="col-lg-6" id="form-side">
                    <!-- LOGIN FORM -->
                    <div class="login_form_inner" id="login-form">
                        <h3>Log in to enter</h3>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->has('username'))
                            <div class="alert alert-danger">
                                {{ $errors->first('username') }}
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="username" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="creat_account">
                                <input type="checkbox" id="remember">
                                <label for="remember">Keep me logged in</label>
                            </div>
                            <button type="submit" class="primary-btn">LOG IN</button>
                            <a href="{{ route('password.request') }}" class="forgot">Forgot Password?</a>
                        </form>
                    </div>
                    <!-- REGISTER FORM -->
                    <div class="login_form_inner" id="register-form" style="display:none;">
                        <h3>Create an Account</h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="telp" class="form-control" placeholder="Nomor Telepon" required>
                            </div>
                            <div class="mb-3">
                                <textarea name="alamat" class="form-control" placeholder="Alamat Lengkap" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                            </div>
                            <button type="submit" class="primary-btn w-100">DAFTAR SEKARANG</button>
                        </form>
                        <p class="text-center mt-3">
                            Sudah punya akun? 
                            <a href="#" onclick="showLogin(event)">Login disini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Box Area =================-->

    <!-- start footer Area -->
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
    <script>
    function showRegister(event) {
        if (event) {
            event.preventDefault();
        }

        const authContainer = document.getElementById('auth-container');
        const toggleBtn = document.getElementById('toggle-btn');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        authContainer.classList.add('register-mode');
        loginForm.style.display = 'none';
        loginForm.style.opacity = '0';
        registerForm.style.display = 'block';
        registerForm.style.opacity = '1';

        if (toggleBtn) {
            toggleBtn.textContent = 'Login';
        }
    }

    function showLogin(event) {
        if (event) {
            event.preventDefault();
        }

        const authContainer = document.getElementById('auth-container');
        const toggleBtn = document.getElementById('toggle-btn');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        authContainer.classList.remove('register-mode');
        registerForm.style.display = 'none';
        registerForm.style.opacity = '0';
        loginForm.style.display = 'block';
        loginForm.style.opacity = '1';

        if (toggleBtn) {
            toggleBtn.textContent = 'Create an Account';
        }
    }
    </script>
    </html>
