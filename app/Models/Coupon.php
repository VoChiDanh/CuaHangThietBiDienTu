<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Kiểm tra tính hợp lệ của mã giảm giá và tính số tiền được giảm
     *
     * @return array{valid: bool, discount_amount: float, message: string}
     */
    public function calculateDiscount(float $subtotal, ?int $userId = null): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'discount_amount' => 0, 'message' => 'Mã giảm giá hiện đang bị tạm khóa.'];
        }

        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'discount_amount' => 0, 'message' => 'Chương trình khuyến mãi chưa bắt đầu.'];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'discount_amount' => 0, 'message' => 'Mã giảm giá đã hết hạn sử dụng.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'discount_amount' => 0, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($subtotal < $this->min_order_amount) {
            $formattedMin = number_format($this->min_order_amount, 0, ',', '.');

            return [
                'valid' => false,
                'discount_amount' => 0,
                'message' => "Đơn hàng phải đạt tối thiểu {$formattedMin}đ để áp dụng mã này.",
            ];
        }

        if ($userId && $this->usage_limit_per_user > 0) {
            $userUsage = $this->usages()->where('user_id', $userId)->count();
            if ($userUsage >= $this->usage_limit_per_user) {
                return ['valid' => false, 'discount_amount' => 0, 'message' => 'Bạn đã sử dụng hết lượt cho phép của mã giảm giá này.'];
            }
        }

        $discount = 0.0;
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }
        } else {
            $discount = (float) $this->discount_value;
        }

        $discount = min($discount, $subtotal);

        return [
            'valid' => true,
            'discount_amount' => round($discount, 2),
            'message' => 'Áp dụng mã giảm giá thành công!',
        ];
    }
}
