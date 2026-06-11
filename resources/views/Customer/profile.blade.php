<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('img/fav.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/fav.png') }}">
    <meta name="author" content="CodePixar">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="UTF-8">
    <title>Long BlackS - My Profile</title>

    <!-- CSS External Files -->
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

<body>

<!-- Start Header Area -->
<header class="header_area sticky-header customer-site-header">
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
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="customer-header-logout-form js-logout-form">
                            @csrf
                            <button type="submit" class="customer-logout-btn" aria-label="Logout">
                                <span class="lnr lnr-exit"></span>
                                <span class="logout-text">Logout</span>
                            </button>
                        </form>
                    </li>
				</ul>
				</div>
			</div>
			</nav>
		</div>
	</header>
<!-- End Header Area -->

    <!-- Start Banner Area -->
    <section class="banner-area">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            
            <!-- Left Content -->
            <div class="col-lg-6">
                <nav class="breadcrumb-nav mb-4">
                    <a href="{{ url('/') }}">Home</a>
                    <span class="lnr lnr-arrow-right mx-2"></span>
                    <a href="#" class="active">{{ $pageTitle ?? 'Profile' }}</a>
                </nav>
                
                <h1 class="page-title">{{ $pageTitle ?? 'Profile' }}</h1>
                
                <div class="hero-branding">
                    <h2 class="typing">Long Black</h2>
                    <div class="decor-line"></div>
                    <p class="tagline">Best Coffee</p>
                </div>
            </div>

            <!-- Right Side (Optional) -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <!-- Bisa diisi gambar dekoratif atau kosongkan -->
            </div>

        </div>
    </div>
</section>
    <!-- End Banner Area -->

    <!--================Profile Area =================-->
    <section class="section_gap">
    <div class="container">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="profile-sidebar text-center">
                    <div class="profile-avatar mb-3" id="avatarDisplay">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <h4 id="fullNameSide">{{ Auth::user()->name ?? 'Nama Pengguna' }}</h4>
                    <p><i class="fa fa-map-marker"></i> <span id="addressSide">{{ Auth::user()->alamat ?? 'Belum diisi' }}</span></p>
                    
                    <button class="btn btn-outline-primary w-100 mt-3" id="editProfileBtn">
                        <i class="fa fa-pencil"></i> Edit Profile
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="profile-logout-form js-logout-form">
                        @csrf
                        <button type="submit" class="profile-logout-btn">
                            <i class="fa fa-sign-out"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Info -->
            <div class="col-lg-8">
                <div class="profile-info-card">
                    <h4 class="section-title">Personal Information</h4>
                    
                        <div class="info-row">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value" id="displayFullName">{{ Auth::user()->name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Username</div>
                            <div class="info-value" id="displayUsername">{{ Auth::user()->username ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value" id="displayEmail">{{ Auth::user()->email ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">No. Telepon</div>
                            <div class="info-value" id="displayPhone">{{ Auth::user()->telp ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value" id="displayAddress">{{ Auth::user()->alamat ?? 'Belum diisi' }}</div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-primary" id="editProfileBtn2">
                            <i class="fa fa-edit"></i> Update Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Profile Area =================-->

    <!-- Modal for Editing Profile -->
<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <span class="modal-close" id="closeModalBtn">&times;</span>
        <h3 style="margin-bottom: 20px;">Edit Profile</h3>
        
        <form id="profileEditForm" method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ Auth::user()->username }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
            </div>

            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" name="telp" class="form-control" value="{{ Auth::user()->telp ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required>{{ Auth::user()->alamat }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
        </form>
    </div>
</div>
    <!-- Toast notification -->
    <div id="toastMsg" class="toast-notif">
        <i class="fa fa-check-circle"></i> Profile updated successfully!
    </div>
    <!-- ================== FORM LOGOUT ================== -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

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

    <!-- start footer Area -->
    <footer class="footer-area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>About Us</h6>
                        <p>RKB Jember atau Rumah Kopi Banjarsengon rumah produksi kopi Specialty origin pegunungan Argopuro Lereng Selatan Jember - Jawa Timur.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Newsletter</h6>
                        <p>Subscribe for latest updates</p>
                        <div class="" id="mc_embed_signup">
                            <form target="_blank" novalidate action="#" method="get" class="form-inline">
                                <div class="d-flex flex-row">
                                    <input class="form-control" name="EMAIL" placeholder="Enter Email" required="" type="email">
                                    <button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                                </div>
                                <div class="info"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
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
        </div>
    </footer>
    <!-- End footer Area -->

    <!-- JS Files -->
    <script src="{{ asset('js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/nouislider.min.js') }}"></script>
    <script src="{{ asset('js/countdown.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            let userProfile = {
                fullName: "{{ Auth::user()->name ?? 'User Name' }}",
                username: "{{ Auth::user()->username ?? 'username' }}",
                email: "{{ Auth::user()->email ?? 'email@example.com' }}",
                password: "{{ Auth::user()->password ?? 'hashed_password' }}",
                phone: "{{ Auth::user()->phone ?? 'No Telepon' }}",
                address: "{{ Auth::user()->address ?? 'Alamat Lengkap' }}"
            };
            
            function refreshProfileUI() {
                $('#fullNameSide').text(userProfile.fullName);
                $('#usernameSide').text('@' + userProfile.username);
                let shortAddress = userProfile.address.split(',')[0];
                $('#addressSide').text(shortAddress.length > 30 ? shortAddress.substring(0, 27) + '...' : shortAddress);
                $('#avatarDisplay').text(userProfile.fullName.charAt(0).toUpperCase());
                $('#displayFullName').text(userProfile.fullName);
                $('#displayUsername').text(userProfile.username);
                $('#displayEmail').text(userProfile.email);
                $('#displayPhone').text(userProfile.phone);
                $('#displayAddress').text(userProfile.address);
            }
            
            function openEditModal() {
                $('#editFullName').val(userProfile.fullName);
                $('#editUsername').val(userProfile.username);
                $('#editEmail').val(userProfile.email);
                $('#editPhone').val(userProfile.phone);
                $('#editAddress').val(userProfile.address);
                $('#editPassword').val('');
                $('#editConfirmPassword').val('');
                $('#editModal').fadeIn(200);
            }
            
            function closeModal() {
                $('#editModal').fadeOut(200);
            }
            
            function showToast(message) {
                $('#toastMsg').text(message || 'Profile updated successfully!').fadeIn(300);
                setTimeout(() => {
                    $('#toastMsg').fadeOut(300);
                }, 2500);
            }
            
            $('#editProfileBtn, #editProfileBtn2').on('click', openEditModal);
            $('#closeModalBtn').on('click', closeModal);
            $(window).on('click', function(e) {
                if ($(e.target).is('#editModal')) {
                    closeModal();
                }
            });
            
            let passwordVisible = false;
            $('#displayPassword').on('click', function() {
                if (!passwordVisible) {
                    $(this).text('••••••••');
                    passwordVisible = true;
                    setTimeout(() => {
                        if(passwordVisible) {
                            $('#displayPassword').text('••••••••');
                            passwordVisible = false;
                        }
                    }, 2000);
                }
            });
            
            $('#profileEditForm').on('submit', function(e) {
                e.preventDefault();
                showToast('Profile updated successfully!');
                closeModal();
                location.reload();
            });
            
            let typingText = "Long Black";
            let i = 0;
            function typeWriter() {
                if (i < typingText.length) {
                    $(".typing").text(typingText.substring(0, i+1));
                    i++;
                    setTimeout(typeWriter, 100);
                }
            }
            if($(".typing").length) typeWriter();
            
            refreshProfileUI();
        });
    </script>
</body>

</html>
