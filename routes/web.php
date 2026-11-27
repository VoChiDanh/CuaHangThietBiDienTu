<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SpecificationController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductComparisonController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Hệ Thống Thương Mại Điện Tử SmartTech
|--------------------------------------------------------------------------
*/

// =========================================================================
// PHÂN HỆ CỬA HÀNG, TÌM KIẾM & SO SÁNH (THÀNH VIÊN 3 & THÀNH VIÊN 2)
// =========================================================================
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show.alias');
Route::get('/compare', [ProductComparisonController::class, 'index'])->name('compare.index');

// =========================================================================
// PHÂN HỆ GIỎ HÀNG, THANH TOÁN & ĐƠN HÀNG (THÀNH VIÊN 4)
// =========================================================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{variantId}', [CartController::class, 'update'])->whereNumber('variantId')->name('cart.items.update');
Route::delete('/cart/items/{variantId}', [CartController::class, 'remove'])->whereNumber('variantId')->name('cart.items.destroy');
Route::get('/orders/track', [OrderController::class, 'trackingForm'])->name('orders.track.form');
Route::post('/orders/track', [OrderController::class, 'track'])->name('orders.track');
Route::get('/warranty', [OrderController::class, 'warrantyForm'])->name('warranty.form');
Route::post('/warranty', [OrderController::class, 'warrantyLookup'])->name('warranty.lookup');
Route::get('/payments/vnpay/return', [PaymentController::class, 'returned'])->name('payments.vnpay.return');
Route::get('/payments/vnpay/ipn', [PaymentController::class, 'ipn'])->name('payments.vnpay.ipn');

// =========================================================================
// PHÂN HỆ XÁC THỰC (GUEST ONLY)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Đăng ký
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Quên & Đặt lại mật khẩu
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Đăng nhập qua mạng xã hội (Google OAuth2)
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// =========================================================================
// PHÂN HỆ XÁC THỰC EMAIL & NGƯỜI DÙNG (YÊU CẦU ĐĂNG NHẬP)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Xác thực Email (Email Verification)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->intended('/')->with('success', 'Xác thực địa chỉ email thành công!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Đăng xuất an toàn
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Checkout & Đơn hàng người dùng
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders/{orderCode}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderCode}/payment/retry', [PaymentController::class, 'retry'])->name('orders.payment.retry');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Sổ địa chỉ nhận hàng
    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/profile/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/profile/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
    Route::post('/profile/addresses/normalize-34', [AddressController::class, 'normalizeAllTo34'])->name('addresses.normalize-34');

    // API Sổ địa chỉ (Cung cấp cho Thành viên 4 chọn nhanh khi Checkout & Đồng bộ 34 tỉnh)
    Route::get('/api/user/addresses', [AddressController::class, 'apiList'])->name('api.user.addresses');
    Route::get('/api/user/addresses/preview-sync', [AddressController::class, 'previewSync'])->name('api.user.addresses.preview-sync');
    Route::post('/api/user/addresses/convert', [AddressController::class, 'convert'])->name('api.user.addresses.convert');
    Route::post('/api/user/addresses/parse-convert', [AddressController::class, 'parseConvert'])->name('api.user.addresses.parse-convert');
});

// =========================================================================
// PHÂN HỆ QUẢN TRỊ ADMIN (YÊU CẦU ĐĂNG NHẬP & VAI TRÒ ADMIN/STAFF)
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard tổng quan
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

    // Quản lý Đơn hàng & Kho hàng (Thành viên 4)
    Route::middleware('permission:manage_orders')->group(function () {
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderCode}/invoice', [OrderManagementController::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{orderCode}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{orderCode}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.status.update');
    });

    Route::get('/inventory/low-stock', [OrderManagementController::class, 'lowStock'])
        ->middleware('permission:view_inventory')
        ->name('inventory.low-stock');

    // Quản lý sản phẩm, biến thể và thông số kỹ thuật.
    Route::middleware('permission:manage_products')->group(function () {
        Route::get('/products', [ProductManagementController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductManagementController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductManagementController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductManagementController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductManagementController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductManagementController::class, 'destroy'])->name('products.destroy');

        Route::get('/specifications', [SpecificationController::class, 'index'])->name('specifications.index');
        Route::post('/specification-groups', [SpecificationController::class, 'storeGroup'])->name('specification-groups.store');
        Route::put('/specification-groups/{group}', [SpecificationController::class, 'updateGroup'])->name('specification-groups.update');
        Route::delete('/specification-groups/{group}', [SpecificationController::class, 'destroyGroup'])->name('specification-groups.destroy');
        Route::post('/specifications', [SpecificationController::class, 'store'])->name('specifications.store');
        Route::put('/specifications/{specification}', [SpecificationController::class, 'update'])->name('specifications.update');
        Route::delete('/specifications/{specification}', [SpecificationController::class, 'destroy'])->name('specifications.destroy');
    });

    // Quản lý thương hiệu.
    Route::middleware('permission:manage_categories')->group(function () {
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });

    // Quản lý Voucher & Khuyến mãi (Thành viên 1)
    Route::middleware('permission:manage_coupons')->group(function () {
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::post('/coupons/{id}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    });

    // Ma trận Phân quyền & Vai trò (RBAC)
    Route::get('/roles', [RolePermissionController::class, 'index'])
        ->middleware('permission:manage_roles')
        ->name('roles.index');
    Route::post('/roles/matrix', [RolePermissionController::class, 'updateMatrix'])
        ->middleware('permission:manage_roles')
        ->name('roles.update-matrix');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])
        ->middleware('permission:manage_roles')
        ->name('roles.store');
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroyRole'])
        ->middleware('permission:manage_roles')
        ->name('roles.destroy');

    // Quản lý Nhân sự & Phân vai trò
    Route::get('/staff', [StaffController::class, 'index'])
        ->middleware('permission:manage_users')
        ->name('staff.index');
    Route::get('/staff/search-users', [StaffController::class, 'searchUsers'])
        ->middleware('permission:manage_users')
        ->name('staff.search-users');
    Route::post('/staff/assign-existing', [StaffController::class, 'assignExisting'])
        ->middleware('permission:manage_users')
        ->name('staff.assign-existing');
    Route::post('/staff', [StaffController::class, 'store'])
        ->middleware('permission:manage_users')
        ->name('staff.store');
    Route::put('/staff/{id}', [StaffController::class, 'update'])
        ->middleware('permission:manage_users')
        ->name('staff.update');
    Route::post('/staff/{id}/toggle-status', [StaffController::class, 'toggleStatus'])
        ->middleware('permission:manage_users')
        ->name('staff.toggle-status');
    Route::post('/staff/{id}/demote', [StaffController::class, 'demote'])
        ->middleware('permission:manage_users')
        ->name('staff.demote');
});
