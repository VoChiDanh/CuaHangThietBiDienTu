<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\CouponController as ClientCouponController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SMARTTECH E-COMMERCE
| Phân hệ Thành viên 1: Auth, Dynamic RBAC, Profile & Sổ địa chỉ,
| Coupon Engine, Verified Reviews, Banners, Admin Dashboard & Excel Export
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. TRANG CHỦ & CÔNG KHAI
// ==========================================
Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/coupons', [ClientCouponController::class, 'publicList'])->name('coupons.public');
Route::get('/products/search', fn () => redirect('/'))->name('products.search');
Route::get('/warranty-lookup', fn () => redirect('/')->with('info', 'Chức năng tra cứu bảo hành đang được kết nối.'))->name('warranty.lookup');

// ==========================================
// 2. XÁC THỰC NGƯỜI DÙNG (AUTHENTICATION)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Google OAuth2
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 3. KHÁCH HÀNG (CUSTOMER PROTECTED)
// ==========================================
Route::middleware('auth')->group(function () {
    // Hồ sơ cá nhân & Đổi mật khẩu
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Sổ địa chỉ nhận hàng
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::patch('addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Đánh giá sản phẩm đã mua (Verified Purchase)
    Route::post('/reviews', [ClientReviewController::class, 'store'])->name('reviews.store');
});

// ==========================================
// 4. QUẢN TRỊ VIÊN & NHÂN VIÊN (ADMIN PORTAL)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    // Dashboard & Báo cáo Doanh thu (Chart.js & Excel)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'exportExcel'])->name('dashboard.export');

    // Quản lý Mã giảm giá (Coupons / Vouchers)
    Route::resource('coupons', AdminCouponController::class);
    Route::patch('coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');

    // Kiểm duyệt Đánh giá (Reviews)
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::patch('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Quản lý Tiếp thị & Banner
    Route::resource('banners', BannerController::class);
    Route::patch('banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

    // Quản lý Nhân sự & Phân quyền Động (Chỉ Super Admin)
    Route::resource('staffs', StaffController::class)->middleware('role:admin');

    // Stub routes cho các thành viên 2, 3, 4 (để tránh lỗi NotFound trong Navigation)
    Route::get('/orders', fn () => redirect()->route('admin.dashboard')->with('info', 'Phân hệ Quản lý Đơn hàng do Thành viên 4 phụ trách.'))->name('orders.index');
    Route::get('/categories', fn () => redirect()->route('admin.dashboard')->with('info', 'Phân hệ Danh mục do Thành viên 2 phụ trách.'))->name('categories.index');
    Route::get('/brands', fn () => redirect()->route('admin.dashboard')->with('info', 'Phân hệ Thương hiệu do Thành viên 2 phụ trách.'))->name('brands.index');
    Route::get('/products', fn () => redirect()->route('admin.dashboard')->with('info', 'Phân hệ Sản phẩm & Biến thể do Thành viên 2 phụ trách.'))->name('products.index');
});
