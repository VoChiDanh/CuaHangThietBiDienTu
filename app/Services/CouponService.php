<?php

namespace App\Services;

use App\DTOs\CouponCalculationResult;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponService
{
    /**
     * Thẩm định mã giảm giá qua 6 tầng và tính toán số tiền giảm chính xác.
     */
    public function validateAndCalculate(string $code, float $subtotal, ?int $userId = null): CouponCalculationResult
    {
        $normalizedCode = strtoupper(trim($code));

        if (empty($normalizedCode)) {
            return new CouponCalculationResult(
                isValid: false,
                message: 'Vui lòng nhập mã giảm giá.',
                errorCode: 'COUPON_EMPTY',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        // TẦNG 1: Kiểm tra tồn tại & trạng thái hoạt động
        $coupon = Coupon::where('code', $normalizedCode)->first();

        if (! $coupon) {
            return new CouponCalculationResult(
                isValid: false,
                message: "Mã giảm giá [{$normalizedCode}] không tồn tại trong hệ thống.",
                errorCode: 'COUPON_NOT_FOUND',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        if (! $coupon->is_active) {
            return new CouponCalculationResult(
                isValid: false,
                message: "Chương trình khuyến mãi cho mã [{$normalizedCode}] đã tạm ngừng.",
                couponId: $coupon->id,
                code: $coupon->code,
                errorCode: 'COUPON_INACTIVE',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        // TẦNG 2: Kiểm tra thời hạn hiệu lực
        $now = now();

        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return new CouponCalculationResult(
                isValid: false,
                message: "Chương trình khuyến mãi [{$coupon->code}] chưa bắt đầu (bắt đầu lúc {$coupon->starts_at->format('d/m/Y H:i')}).",
                couponId: $coupon->id,
                code: $coupon->code,
                errorCode: 'COUPON_NOT_STARTED',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            return new CouponCalculationResult(
                isValid: false,
                message: "Mã giảm giá [{$coupon->code}] đã hết hạn sử dụng vào ngày {$coupon->expires_at->format('d/m/Y H:i')}.",
                couponId: $coupon->id,
                code: $coupon->code,
                errorCode: 'COUPON_EXPIRED',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        // TẦNG 3: Kiểm tra giá trị đơn hàng tối thiểu
        $minOrder = (float) $coupon->min_order_amount;
        if ($minOrder > 0 && $subtotal < $minOrder) {
            $diff = $minOrder - $subtotal;
            $formattedMin = number_format($minOrder, 0, ',', '.').' đ';
            $formattedDiff = number_format($diff, 0, ',', '.').' đ';

            return new CouponCalculationResult(
                isValid: false,
                message: "Đơn hàng tối thiểu phải từ {$formattedMin} để áp dụng mã {$coupon->code}. Bạn cần mua thêm {$formattedDiff}.",
                couponId: $coupon->id,
                code: $coupon->code,
                errorCode: 'MIN_ORDER_NOT_MET',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        // TẦNG 4: Kiểm tra giới hạn lượt dùng toàn hệ thống
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return new CouponCalculationResult(
                isValid: false,
                message: "Rất tiếc! Mã giảm giá [{$coupon->code}] đã hết lượt sử dụng trên hệ thống.",
                couponId: $coupon->id,
                code: $coupon->code,
                errorCode: 'GLOBAL_LIMIT_REACHED',
                subtotal: $subtotal,
                finalTotal: $subtotal,
            );
        }

        // TẦNG 5: Kiểm tra giới hạn lượt dùng của từng người dùng
        if ($userId) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return new CouponCalculationResult(
                    isValid: false,
                    message: "Bạn đã sử dụng hết số lần cho phép ({$coupon->usage_limit_per_user} lần) của mã [{$coupon->code}].",
                    couponId: $coupon->id,
                    code: $coupon->code,
                    errorCode: 'USER_LIMIT_REACHED',
                    subtotal: $subtotal,
                    finalTotal: $subtotal,
                );
            }
        }

        // TẦNG 6: TÍNH TOÁN CHIẾT KHẤU & TRẦN GIẢM GIÁ (Capping)
        $discountAmount = 0.0;

        if ($coupon->discount_type === 'fixed_amount') {
            $discountAmount = min((float) $coupon->discount_value, $subtotal);
        } else {
            // Loại phần trăm
            $percent = (float) $coupon->discount_value;
            $calculatedDiscount = $subtotal * ($percent / 100);

            // Kiểm tra trần giảm giá tối đa
            if ($coupon->max_discount_amount !== null && (float) $coupon->max_discount_amount > 0) {
                $calculatedDiscount = min($calculatedDiscount, (float) $coupon->max_discount_amount);
            }

            // Làm tròn số nguyên VNĐ
            $discountAmount = min(round($calculatedDiscount, 0), $subtotal);
        }

        $finalTotal = max(0.0, $subtotal - $discountAmount);
        $formattedDiscount = number_format($discountAmount, 0, ',', '.').' đ';

        return new CouponCalculationResult(
            isValid: true,
            message: "Áp dụng mã giảm giá [{$coupon->code}] thành công! Bạn được giảm {$formattedDiscount}.",
            couponId: $coupon->id,
            code: $coupon->code,
            discountType: $coupon->discount_type,
            discountValue: (float) $coupon->discount_value,
            discountAmount: $discountAmount,
            subtotal: $subtotal,
            finalTotal: $finalTotal,
        );
    }

    /**
     * Ghi nhận sử dụng voucher an toàn Concurrency (Khóa bi quan lockForUpdate).
     */
    public function consumeCoupon(int $couponId, int $userId, ?int $orderId = null): void
    {
        DB::transaction(function () use ($couponId, $userId, $orderId): void {
            $coupon = Coupon::query()->lockForUpdate()->findOrFail($couponId);

            if (! $coupon->is_active || ! $coupon->isValidTime()) {
                throw ValidationException::withMessages([
                    'coupon_code' => 'Mã giảm giá không còn hiệu lực.',
                ]);
            }

            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                throw ValidationException::withMessages([
                    'coupon_code' => 'Mã giảm giá vừa hết lượt sử dụng trên hệ thống.',
                ]);
            }

            $userUsageCount = CouponUsage::query()
                ->where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                throw ValidationException::withMessages([
                    'coupon_code' => 'Bạn đã sử dụng hết số lần cho phép của mã giảm giá này.',
                ]);
            }

            $coupon->increment('used_count');

            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $userId,
                'order_id' => $orderId,
                'used_at' => now(),
            ]);
        });
    }

    /**
     * Hoàn trả lượt dùng khi đơn hàng bị hủy bỏ (Voucher Rollback).
     */
    public function rollbackCoupon(Order $order): void
    {
        if (! $order->coupon_id) {
            return;
        }

        DB::transaction(function () use ($order): void {
            $coupon = Coupon::query()->lockForUpdate()->find($order->coupon_id);

            if ($coupon && $coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }

            CouponUsage::where('order_id', $order->id)
                ->orWhere(function ($q) use ($order) {
                    $q->where('coupon_id', $order->coupon_id)
                        ->where('user_id', $order->user_id);
                })
                ->delete();
        });
    }
}
