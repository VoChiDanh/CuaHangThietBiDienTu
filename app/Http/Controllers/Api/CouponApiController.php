<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    /**
     * API kiểm tra và tính toán giảm giá của Voucher
     * Phục vụ trực tiếp cho Thành viên 4 kết nối Giỏ hàng & Thanh toán (Checkout)
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'integer'],
        ]);

        $code = strtoupper(trim($request->input('coupon_code')));
        $subtotal = (float) $request->input('subtotal');
        $userId = $request->input('user_id') ?: auth('web')->id();

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return response()->json([
                'valid' => false,
                'discount_amount' => 0,
                'message' => 'Mã giảm giá không tồn tại hoặc đã bị xóa.',
            ], 404);
        }

        $result = $coupon->calculateDiscount($subtotal, $userId);

        if (! $result['valid']) {
            return response()->json([
                'valid' => false,
                'coupon_id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => 0,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => (float) $coupon->discount_value,
            'discount_amount' => $result['discount_amount'],
            'message' => $result['message'],
        ]);
    }
}
