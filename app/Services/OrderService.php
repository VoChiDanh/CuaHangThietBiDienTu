<?php

namespace App\Services;

use App\DTOs\CouponCalculationResult;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CouponService $couponService,
    ) {}

    /**
     * @param array{
     *     address_id?: int|null,
     *     receiver_name?: string,
     *     receiver_phone?: string,
     *     province?: string,
     *     district?: string,
     *     ward?: string,
     *     street_address?: string,
     *     note?: string|null,
     *     shipping_method: string,
     *     coupon_code?: string|null
     * } $checkoutData
     */
    public function placeOrder(User $user, array $checkoutData): Order
    {
        return DB::transaction(function () use ($user, $checkoutData): Order {
            $cart = $user->cart()->lockForUpdate()->first();

            if ($cart === null) {
                throw ValidationException::withMessages([
                    'cart' => 'Giỏ hàng đang trống.',
                ]);
            }

            $cartItems = $cart->items()
                ->orderBy('product_variant_id')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Giỏ hàng đang trống.',
                ]);
            }

            $address = $this->shippingAddress($user, $checkoutData);
            $lockedItems = [];
            $subtotalMinorUnits = 0;

            foreach ($cartItems as $cartItem) {
                $variant = ProductVariant::query()
                    ->with('product')
                    ->lockForUpdate()
                    ->findOrFail($cartItem->product_variant_id);

                if (! $variant->is_active || ! $variant->product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => "{$variant->product->name} hiện không thể đặt hàng.",
                    ]);
                }

                if ($cartItem->quantity > $variant->stock_quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "{$variant->product->name} chỉ còn {$variant->stock_quantity} sản phẩm.",
                    ]);
                }

                $unitPrice = $variant->currentPrice();
                $subtotalMinorUnits += $this->toMinorUnits($unitPrice) * $cartItem->quantity;
                $lockedItems[] = [$cartItem, $variant, $unitPrice];
            }

            $couponResult = null;
            $discountMinorUnits = 0;

            if (isset($checkoutData['coupon_code']) && $checkoutData['coupon_code'] !== '') {
                $couponResult = $this->couponService->validateAndCalculate(
                    $checkoutData['coupon_code'],
                    $this->fromMinorUnits($subtotalMinorUnits),
                    (int) $user->getKey(),
                );

                if (! $couponResult->isValid || $couponResult->couponId === null) {
                    throw ValidationException::withMessages([
                        'coupon_code' => $couponResult->message,
                    ]);
                }

                $discountMinorUnits = $this->toMinorUnits(
                    number_format($couponResult->discountAmount, 2, '.', ''),
                );
            }

            $shippingMethods = config('store.shipping_methods', []);
            $shippingMethod = $shippingMethods[$checkoutData['shipping_method']] ?? null;

            if (! is_array($shippingMethod)) {
                throw ValidationException::withMessages([
                    'shipping_method' => 'Vui lòng chọn một đơn vị vận chuyển hợp lệ.',
                ]);
            }

            $shippingFeeMinorUnits = (int) $shippingMethod['fee'] * 100;
            $order = Order::query()->create([
                'order_code' => 'ST'.now()->format('ymd').Str::upper(Str::random(8)),
                'user_id' => $user->getKey(),
                'receiver_name' => $address['receiver_name'],
                'receiver_phone' => $address['receiver_phone'],
                'shipping_address' => $address['shipping_address'],
                'shipping_method' => $shippingMethod['label'],
                'coupon_id' => $couponResult?->couponId,
                'note' => $checkoutData['note'] ?? null,
                'subtotal' => $this->fromMinorUnits($subtotalMinorUnits),
                'discount_amount' => $this->fromMinorUnits($discountMinorUnits),
                'shipping_fee' => $this->fromMinorUnits($shippingFeeMinorUnits),
                'total_amount' => $this->fromMinorUnits(
                    $subtotalMinorUnits - $discountMinorUnits + $shippingFeeMinorUnits,
                ),
                'status' => 'pending',
                'payment_method' => $checkoutData['payment_method'] ?? 'cod',
                'payment_status' => 'unpaid',
            ]);

            if ($couponResult instanceof CouponCalculationResult && $couponResult->couponId !== null) {
                $this->couponService->consumeCoupon(
                    $couponResult->couponId,
                    (int) $user->getKey(),
                    (int) $order->getKey(),
                );
            }

            if ($order->payment_method === 'vnpay') {
                $order->paymentTransactions()->create([
                    'transaction_ref' => 'PAY'.Str::upper(Str::random(17)),
                    'gateway' => 'vnpay',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                ]);
            }

            foreach ($lockedItems as [$cartItem, $variant, $unitPrice]) {
                $variant->decrement('stock_quantity', $cartItem->quantity);
                $variant->forceFill(['last_sold_at' => now()])->save();

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_info' => $this->variantDescription($variant),
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $this->fromMinorUnits($this->toMinorUnits($unitPrice) * $cartItem->quantity),
                    'warranty_expires_at' => null,
                ]);
            }

            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'status' => 'pending',
                'description' => 'Đơn hàng được tạo.',
            ]);

            $this->cartService->clear($user);

            return $order->load(['items', 'statusHistories', 'paymentTransactions']);
        }, attempts: 3);
    }

    /**
     * @param  array<string, mixed>  $checkoutData
     * @return array{receiver_name: string, receiver_phone: string, shipping_address: string}
     */
    private function shippingAddress(User $user, array $checkoutData): array
    {
        if (isset($checkoutData['address_id'])) {
            $address = UserAddress::query()
                ->where('user_id', $user->getKey())
                ->findOrFail($checkoutData['address_id']);

            return [
                'receiver_name' => $address->receiver_name,
                'receiver_phone' => $address->receiver_phone,
                'shipping_address' => implode(', ', [
                    $address->street_address,
                    $address->ward,
                    $address->district,
                    $address->province,
                ]),
            ];
        }

        return [
            'receiver_name' => $checkoutData['receiver_name'],
            'receiver_phone' => $checkoutData['receiver_phone'],
            'shipping_address' => implode(', ', [
                $checkoutData['street_address'],
                $checkoutData['ward'],
                $checkoutData['district'],
                $checkoutData['province'],
            ]),
        ];
    }

    private function variantDescription(ProductVariant $variant): ?string
    {
        $details = array_filter([
            $variant->color_name,
            $variant->ram,
            $variant->storage,
        ]);

        return $details === [] ? null : implode(' / ', $details);
    }

    private function toMinorUnits(string $amount): int
    {
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/D', $amount)) {
            throw new \UnexpectedValueException('Product variant price must be a non-negative decimal amount.');
        }

        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '0');

        return ((int) $whole * 100) + (int) str_pad(substr($fraction, 0, 2), 2, '0');
    }

    private function fromMinorUnits(int $amount): string
    {
        return intdiv($amount, 100).'.'.str_pad((string) ($amount % 100), 2, '0', STR_PAD_LEFT);
    }
}
