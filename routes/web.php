<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\BiayaOperasionalController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\MidtransWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================== GUEST ROUTES ======================
Route::get('/', [GuestController::class, 'index'])->name('home');

// ====================== AUTH ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Midtrans memanggil endpoint ini langsung, tanpa sesi login pengguna.
Route::post('/midtrans/webhook', MidtransWebhookController::class)
    ->name('midtrans.webhook');

// ====================== CUSTOMER ROUTES ======================
Route::middleware('auth')
     ->prefix('customer')
     ->name('customer.')
     ->group(function () {

    // Dashboard / Home
    Route::get('/', [CustomerController::class, 'home'])->name('home');
    Route::get('/dashboard', [CustomerController::class, 'home'])->name('dashboard');

    // Product Routes (untuk customer)
    Route::get('/products', [CustomerController::class, 'productIndex'])->name('product.index');
    Route::get('/product/{id}', [CustomerController::class, 'showProduct'])->name('product.show');

    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    
    // Orders & Tracking
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/{kodeTransaksi}', [CustomerController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{kodeTransaksi}/invoice', [CustomerController::class, 'invoice'])->name('orders.invoice');
    Route::put('/orders/{kodeTransaksi}', [CustomerController::class, 'updateOrder'])->name('orders.update');
    Route::post('/orders/{kodeTransaksi}/cancel', [CustomerController::class, 'cancelOrder'])->name('orders.cancel');
    Route::delete('/orders/{kodeTransaksi}', [CustomerController::class, 'hideOrder'])->name('orders.destroy');
    Route::get('/tracking', [CustomerController::class, 'tracking'])->name('tracking');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/search-destinations', [CartController::class, 'searchDestinations'])->name('cart.search-destinations');
    Route::post('/cart/shipping-estimate', [CartController::class, 'shippingEstimate'])->name('cart.shipping-estimate');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// ====================== ADMIN ROUTES ======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard (gunakan satu saja)
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Akun
    Route::get('/akun', [App\Http\Controllers\Admin\AkunController::class, 'index'])->name('akun.index');
    Route::get('/profile', [App\Http\Controllers\Admin\AkunController::class, 'profile'])->name('profile');
    Route::resource('akun', App\Http\Controllers\Admin\AkunController::class);
    
    // Product Management
    Route::post('/produk/{id}/restore', [App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('produk.restore');
    Route::delete('/produk/{id}/force-delete', [App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('produk.force-delete');
    Route::resource('produk', App\Http\Controllers\Admin\ProductController::class);
    
    // Branding & Edukasi
    Route::get('/branding/live-preview', [App\Http\Controllers\Admin\BrandingEdukasiController::class, 'livePreview'])
        ->name('branding.live-preview');
    Route::get('/branding/{branding}/preview', [App\Http\Controllers\Admin\BrandingEdukasiController::class, 'preview'])
        ->name('branding.preview');
    Route::resource('branding', App\Http\Controllers\Admin\BrandingEdukasiController::class);
    
    // Pesanan / Transaksi
    Route::get('/pesanan', [App\Http\Controllers\Admin\PemesananController::class, 'index'])->name('pesanan.index');
    Route::post('/pesanan/{pesanan}/status', [App\Http\Controllers\Admin\PemesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::get('/pesanan/{pesanan}/invoice', [App\Http\Controllers\Admin\PemesananController::class, 'invoice'])->name('pesanan.invoice');

    // Biaya Operasional
    Route::resource('biaya-operasional', BiayaOperasionalController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});
    // Route::resource('akun', Admin\AkunController::class);
    // Route::resource('transaksi', Admin\TransaksiController::class);
    // Route::resource('laporan', Admin\LaporanController::class);

// Redirect Profile
Route::get('/profile', function () {
    return redirect()->route('customer.profile');
})->name('profile');
