<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Tests\TestCase;

class CouponApiTest extends TestCase
{
    public function test_validate_coupon_api_returns_correct_discount(): void
    {
        // Tạo hoặc lấy voucher giảm 10% tối đa 100k, min 500k
        $coupon = Coupon::updateOrCreate(
            ['code' => 'TEST10PCT'],
            [
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_amount' => 500000,
                'max_discount_amount' => 100000,
                'usage_limit' => 100,
                'usage_limit_per_user' => 2,
                'is_active' => true,
            ]
        );

        $response = $this->postJson('/api/coupons/validate', [
            'coupon_code' => 'TEST10PCT',
            'subtotal' => 800000,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'code' => 'TEST10PCT',
                'discount_amount' => 80000,
            ]);
    }

    public function test_validate_coupon_api_rejects_below_minimum_order(): void
    {
        $coupon = Coupon::updateOrCreate(
            ['code' => 'MIN1M'],
            [
                'discount_type' => 'fixed_amount',
                'discount_value' => 100000,
                'min_order_amount' => 1000000,
                'is_active' => true,
            ]
        );

        $response = $this->postJson('/api/coupons/validate', [
            'coupon_code' => 'MIN1M',
            'subtotal' => 500000,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'discount_amount' => 0,
            ]);
    }

    public function test_validate_coupon_api_rejects_invalid_code(): void
    {
        $response = $this->postJson('/api/coupons/validate', [
            'coupon_code' => 'NONEXISTENT999',
            'subtotal' => 1000000,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'valid' => false,
            ]);
    }
}
