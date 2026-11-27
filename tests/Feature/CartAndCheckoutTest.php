<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CartAndCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_a_variant_to_the_session_cart(): void
    {
        $variant = $this->createVariant(stock: 5);

        $this->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ])
            ->assertOk()
            ->assertJsonPath('cart_count', 2);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee($variant->product->name)
            ->assertSee('24.000.000');
    }

    public function test_guest_can_update_and_remove_a_session_cart_item_with_ajax(): void
    {
        $variant = $this->createVariant(stock: 5);

        $this->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ])->assertOk();

        $this->patchJson(route('cart.items.update', $variant->id), [
            'quantity' => 3,
        ])
            ->assertOk()
            ->assertJsonPath('cart_count', 3)
            ->assertJsonPath('subtotal', '36000000.00')
            ->assertJsonPath('line_totals.'.$variant->id, '36000000.00');

        $this->deleteJson(route('cart.items.destroy', $variant->id))
            ->assertOk()
            ->assertJsonPath('cart_count', 0)
            ->assertJsonPath('subtotal', '0.00');
    }

    public function test_guest_cart_is_merged_into_the_signed_in_users_database_cart(): void
    {
        $variant = $this->createVariant(stock: 5);
        $user = User::factory()->create();

        $this->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ])->assertOk();

        $this->actingAs($user)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee($variant->product->name);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => Cart::query()->where('user_id', $user->id)->value('id'),
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }

    public function test_checkout_places_order_and_decrements_stock_transactionally(): void
    {
        Mail::fake();

        $variant = $this->createVariant(stock: 5);
        $user = User::factory()->create();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'receiver_name' => 'Nguyen Van A',
            'receiver_phone' => '0900000000',
            'province' => 'TP HCM',
            'district' => 'Quan 1',
            'ward' => 'Ben Nghe',
            'street_address' => '1 Nguyen Hue',
            'shipping_method' => 'express',
            'payment_method' => 'cod',
        ]);

        $order = Order::query()->firstOrFail();

        $response->assertRedirect(route('orders.show', ['orderCode' => $order->order_code]));
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 3,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'total_price' => 24000000,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'shipping_method' => 'Nhanh',
            'shipping_fee' => 50000,
            'total_amount' => 24050000,
        ]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
        Mail::assertQueued(OrderConfirmationMail::class);
    }

    public function test_checkout_displays_the_available_shipping_methods_and_fees(): void
    {
        $variant = $this->createVariant(stock: 5);
        $user = User::factory()->create();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('checkout.create'))
            ->assertOk()
            ->assertSee('Tiêu chuẩn · 30.000 ₫')
            ->assertSee('Nhanh · 50.000 ₫')
            ->assertSee('Hỏa tốc · 80.000 ₫');
    }

    public function test_checkout_rejects_an_unavailable_shipping_method(): void
    {
        $variant = $this->createVariant(stock: 5);
        $user = User::factory()->create();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->from(route('checkout.create'))
            ->post(route('checkout.store'), [
                'receiver_name' => 'Nguyen Van A',
                'receiver_phone' => '0900000000',
                'province' => 'TP HCM',
                'district' => 'Quan 1',
                'ward' => 'Ben Nghe',
                'street_address' => '1 Nguyen Hue',
                'shipping_method' => 'free',
                'payment_method' => 'cod',
            ])
            ->assertRedirect(route('checkout.create'))
            ->assertSessionHasErrors('shipping_method');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 5,
        ]);
    }

    public function test_checkout_does_not_create_order_or_decrement_stock_when_quantity_exceeds_stock(): void
    {
        $variant = $this->createVariant(stock: 1);
        $user = User::factory()->create();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->from(route('checkout.create'))
            ->post(route('checkout.store'), [
                'receiver_name' => 'Nguyen Van A',
                'receiver_phone' => '0900000000',
                'province' => 'TP HCM',
                'district' => 'Quan 1',
                'ward' => 'Ben Nghe',
                'street_address' => '1 Nguyen Hue',
                'shipping_method' => 'standard',
                'payment_method' => 'cod',
            ])
            ->assertRedirect(route('checkout.create'))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock_quantity' => 1,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }

    private function createVariant(int $stock): ProductVariant
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'SmartTech Phone',
        ]);

        return ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 12000000,
            'stock_quantity' => $stock,
        ]);
    }

    public function test_checkout_returns_vietnamese_validation_messages_when_address_fields_are_missing(): void
    {
        $variant = $this->createVariant(stock: 5);
        $user = User::factory()->create();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('checkout.create'))
            ->post(route('checkout.store'), [
                'receiver_name' => 'Nguyen Van A',
                'receiver_phone' => '0900000000',
                'province' => '',
                'district' => '',
                'ward' => '',
                'street_address' => '',
                'shipping_method' => 'standard',
                'payment_method' => 'cod',
            ]);

        $response->assertRedirect(route('checkout.create'))
            ->assertSessionHasErrors([
                'street_address' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
                'ward' => 'Vui lòng nhập phường / xã.',
                'district' => 'Vui lòng nhập quận / huyện.',
                'province' => 'Vui lòng nhập tỉnh / thành phố.',
            ]);
    }
}
