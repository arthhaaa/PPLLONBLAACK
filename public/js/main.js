if (window.jQuery) {
$(document).ready(function(){
    "use strict";

    var window_width 	 = $(window).width(),
        window_height 	 = window.innerHeight,
        header_height 	 = $(".default-header").height(),
        fitscreen 			 = window_height - header_height;

    $(".fullscreen").css("height", window_height);
    $(".fitscreen").css("height", fitscreen);

    // Nice Select
    if ($.fn.niceSelect) {
        $('select').niceSelect();
    }

    // Dropdown Hover
    $('.navbar-nav li.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });

    // Magnific Popup
    if ($.fn.magnificPopup) {
        $('.img-pop-up').magnificPopup({
            type: 'image',
            gallery:{ enabled:true }
        });
    }

    // Search Toggle
    $("#search_input_box").hide();
    $("#search").on("click", function () {
        $("#search_input_box").slideToggle(300);
        $("#search_input").focus();
    });
    $("#close_search").on("click", function () {
        $('#search_input_box').slideUp(300);
    });

    const stickyHeader = $(".sticky-header");
    if (stickyHeader.length) {
        const toggleHeaderAnimation = function () {
            stickyHeader.toggleClass("is-scrolled", $(window).scrollTop() > 20);
        };

        toggleHeaderAnimation();
        $(window).on("scroll", toggleHeaderAnimation);
        $(window).on("resize", toggleHeaderAnimation);
    }

    // Sticky Header
    if ($.fn.sticky) {
        $(".sticky-header").sticky();
    }

    // Owl Carousel Banner
    if ($.fn.owlCarousel) {
        $(".active-banner-slider").owlCarousel({
            items:1,
            autoplay:false,
            loop:true,
            nav:true,
            navText:["<img src='img/banner/prev.png'>","<img src='img/banner/next.png'>"],
            dots:false
        });
    }

    // Owl Carousel Product
    if ($.fn.owlCarousel) {
        $(".active-product-area").owlCarousel({
            items:1,
            autoplay:false,
            loop:true,
            nav:true,
            navText:["<img src='img/product/prev.png'>","<img src='img/product/next.png'>"],
            dots:false
        });
    }

    /* ================== PROFILE PAGE JAVASCRIPT ================== */
    const editModal = $('#editModal');

    $('#editProfileBtn, #editProfileBtn2').on('click', function() {
        editModal.fadeIn(300);
    });

    $('#closeModalBtn').on('click', function() {
        editModal.fadeOut(300);
    });

    $(window).on('click', function(e) {
        if ($(e.target).is('#editModal')) {
            editModal.fadeOut(300);
        }
    });

    // Custom logout confirmation
    let pendingLogoutForm = null;
    const logoutModal = $('#logoutConfirmModal');

    $('.js-logout-form').on('submit', function(e) {
        e.preventDefault();
        pendingLogoutForm = this;
        logoutModal.addClass('is-visible').attr('aria-hidden', 'false');
    });

    $('[data-logout-cancel]').on('click', function() {
        pendingLogoutForm = null;
        logoutModal.removeClass('is-visible').attr('aria-hidden', 'true');
    });

    logoutModal.on('click', function(e) {
        if ($(e.target).is('#logoutConfirmModal')) {
            pendingLogoutForm = null;
            logoutModal.removeClass('is-visible').attr('aria-hidden', 'true');
        }
    });

    $('#confirmLogoutBtn').on('click', function() {
        if (pendingLogoutForm) {
            pendingLogoutForm.submit();
        }
    });
    
    // Toast Notification
    $('#profileEditForm').on('submit', function() {
        $('#toastMsg').fadeIn(300).delay(2500).fadeOut(300);
        editModal.fadeOut(300);
    });

    // Accordion Icon Change
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find(".lnr-arrow-right").removeClass("lnr-arrow-right").addClass("lnr-arrow-left");
    }).on('hidden.bs.collapse', function(){
        $(this).parent().find(".lnr-arrow-left").removeClass("lnr-arrow-left").addClass("lnr-arrow-right");
    });

    // Smooth Scroll
    $('.main-menubar a[href*="#"]')
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function(event) {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
                location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            }
        });

    // Quantity Increase & Decrease
    var value, quantity = document.getElementsByClassName('quantity-container');
    function createBindings(quantityContainer) {
        var quantityAmount = quantityContainer.getElementsByClassName('quantity-amount')[0];
        var increase = quantityContainer.getElementsByClassName('increase')[0];
        var decrease = quantityContainer.getElementsByClassName('decrease')[0];
        increase.addEventListener('click', function () { increaseValue(quantityAmount); });
        decrease.addEventListener('click', function () { decreaseValue(quantityAmount); });
    }
    function init() {
        for (var i = 0; i < quantity.length; i++ ) {
            createBindings(quantity[i]);
        }
    }
    function increaseValue(quantityAmount) {
        value = parseInt(quantityAmount.value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        quantityAmount.value = value;
    }
    function decreaseValue(quantityAmount) {
        value = parseInt(quantityAmount.value, 10);
        value = isNaN(value) ? 0 : value;
        if (value > 0) value--;
        quantityAmount.value = value;
    }
    init();

// LOGIN REGISTER TOGGLE
const toggleBtn = document.getElementById('toggle-btn');
const authContainer = document.getElementById('auth-container');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

let isLogin = true;

if (loginForm && registerForm) {
    loginForm.style.display = 'block';
    loginForm.style.opacity = '1';
    registerForm.style.display = 'none';
    registerForm.style.opacity = '0';
}

// Pastikan tombol toggle memiliki teks yang benar
if (toggleBtn) {
    toggleBtn.textContent = 'Create an Account';
}
// === AKHIR TAMBAHAN KODE ===

if (toggleBtn && authContainer && loginForm && registerForm) {
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const showingLogin = loginForm.style.display !== 'none';

        if (showingLogin) {
            authContainer.classList.add('register-mode');
            loginForm.style.opacity = '0';

            setTimeout(() => {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                registerForm.style.opacity = '1';
            }, 350);

            toggleBtn.textContent = 'Login';
        } else {
            authContainer.classList.remove('register-mode');
            registerForm.style.opacity = '0';

            setTimeout(() => {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                loginForm.style.opacity = '1';
            }, 350);

            toggleBtn.textContent = 'Create an Account';
        }

        isLogin = !showingLogin;
    });
}
});
}

(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var successToast = document.getElementById('adminSuccessToast');

        if (successToast && window.bootstrap && bootstrap.Toast) {
            bootstrap.Toast.getOrCreateInstance(successToast).show();
        }

        var confirmModalElement = document.getElementById('adminConfirmModal');
        var confirmButton = document.getElementById('adminConfirmButton');

        if (confirmModalElement && confirmButton && window.bootstrap && bootstrap.Modal) {
            var confirmModal = bootstrap.Modal.getOrCreateInstance(confirmModalElement);
            var confirmTitle = document.getElementById('adminConfirmTitle');
            var confirmMessage = document.getElementById('adminConfirmMessage');
            var confirmAction = document.getElementById('adminConfirmAction');
            var pendingForm = null;

            document.querySelectorAll('.admin-confirm-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    pendingForm = form;

                    confirmTitle.textContent = form.dataset.confirmTitle || 'Konfirmasi Aksi';
                    confirmMessage.textContent = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan?';
                    confirmAction.textContent = form.dataset.confirmAction || 'Aksi ini akan memproses data admin.';
                    confirmButton.innerHTML = form.dataset.confirmButton || '<i class="fa fa-check me-1"></i> Ya, lanjutkan';

                    confirmModal.show();
                });
            });

            confirmButton.addEventListener('click', function () {
                if (pendingForm) {
                    confirmModal.hide();
                    pendingForm.submit();
                }
            });
        }

        document.querySelectorAll('.js-image-preview-input').forEach(function (input) {
            input.addEventListener('change', function () {
                var target = document.querySelector(input.dataset.previewTarget);
                var file = input.files && input.files[0];

                if (!target || !file || !file.type.startsWith('image/')) {
                    return;
                }

                var reader = new FileReader();

                reader.onload = function (event) {
                    target.innerHTML = '<img src="' + event.target.result + '" alt="Preview gambar">';
                    target.classList.add('has-image');
                };

                reader.readAsDataURL(file);
            });
        });

        document.body.classList.add('reveal-ready');

        var revealItems = document.querySelectorAll('.js-reveal');

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.14 });

            revealItems.forEach(function (item) {
                observer.observe(item);
            });
        } else {
            revealItems.forEach(function (item) {
                item.classList.add('is-visible');
            });
        }
    });
})();
