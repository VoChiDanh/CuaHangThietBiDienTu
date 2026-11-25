<?php

use App\Http\Controllers\Api\CouponApiController;
use Illuminate\Support\Facades\Route;

// API kiểm tra và tính toán giảm giá của Voucher (Thành viên 1 cung cấp cho Thành viên 4 kết nối giỏ hàng/thanh toán)
Route::post('/coupons/validate', [CouponApiController::class, 'validateCoupon'])->name('api.coupons.validate');
