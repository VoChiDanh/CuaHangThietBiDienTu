<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\VnpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const COUPON_SESSION_KEY = 'checkout.coupon_code';

    public function __construct(
        private readonly CartService $cartService,
        private readonly CouponService $couponService,
        private readonly OrderService $orderService,
        private readonly VnpayService $vnpayService,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        $items = $this->cartService->contents($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Giỏ hàng đang trống.',
            ]);
        }

        $addresses = UserAddress::query()
            ->where('user_id', $request->user()->getKey())
            ->orderByDesc('is_default')
            ->get();
        $shippingMethods = config('store.shipping_methods');
        $selectedShippingMethod = (string) $request->old('shipping_method', 'standard');

        if (! array_key_exists($selectedShippingMethod, $shippingMethods)) {
            $selectedShippingMethod = 'standard';
        }

        $subtotal = (float) $items->sum('lineTotal');
        $couponCode = $request->session()->get(self::COUPON_SESSION_KEY);
        $coupon = null;

        if (is_string($couponCode) && $couponCode !== '') {
            $coupon = $this->couponService->validateAndCalculate(
                $couponCode,
                $subtotal,
                (int) $request->user()->getKey(),
            );

            if (! $coupon->isValid) {
                $request->session()->forget(self::COUPON_SESSION_KEY);
                $request->session()->flash('coupon_warning', $coupon->message);
                $coupon = null;
            }
        }

        return view('store.checkout.create', [
            'items' => $items,
            'subtotal' => $subtotal,
            'coupon' => $coupon,
            'discountAmount' => $coupon?->discountAmount ?? 0,
            'shippingMethods' => $shippingMethods,
            'selectedShippingMethod' => $selectedShippingMethod,
            'shippingFee' => $shippingMethods[$selectedShippingMethod]['fee'],
            'addresses' => $addresses,
            'vnpayEnabled' => $this->vnpayService->isConfigured(),
        ]);
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);
        $items = $this->cartService->contents($request);

        if ($items->isEmpty()) {
            $request->session()->forget(self::COUPON_SESSION_KEY);

            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Giỏ hàng đang trống.',
            ]);
        }

        $result = $this->couponService->validateAndCalculate(
            $validated['coupon_code'],
            (float) $items->sum('lineTotal'),
            (int) $request->user()->getKey(),
        );

        if (! $result->isValid) {
            $request->session()->forget(self::COUPON_SESSION_KEY);

            return redirect()
                ->route('checkout.create')
                ->withErrors(['coupon_code' => $result->message])
                ->withInput();
        }

        $request->session()->put(self::COUPON_SESSION_KEY, $result->code);

        return redirect()
            ->route('checkout.create')
            ->with('status', $result->message);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'address_id' => ['nullable', 'integer', 'exists:user_addresses,id'],
            'receiver_name' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'receiver_phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'province' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'district' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'ward' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'street_address' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
            'shipping_method' => ['required', 'string', Rule::in(array_keys(config('store.shipping_methods', [])))],
            'payment_method' => ['required', 'in:cod,vnpay'],
        ], [
            'address_id.integer' => 'Địa chỉ nhận hàng đã chọn không hợp lệ.',
            'address_id.exists' => 'Địa chỉ nhận hàng đã chọn không tồn tại.',
            'receiver_name.required_without' => 'Vui lòng nhập họ tên người nhận.',
            'receiver_name.max' => 'Tên người nhận không được vượt quá 255 ký tự.',
            'receiver_phone.required_without' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'receiver_phone.max' => 'Số điện thoại nhận hàng không được vượt quá 20 ký tự.',
            'street_address.required_without' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
            'street_address.max' => 'Địa chỉ cụ thể không được vượt quá 255 ký tự.',
            'ward.required_without' => 'Vui lòng nhập phường / xã.',
            'ward.max' => 'Phường / xã không được vượt quá 100 ký tự.',
            'district.required_without' => 'Vui lòng nhập quận / huyện.',
            'district.max' => 'Quận / huyện không được vượt quá 100 ký tự.',
            'province.required_without' => 'Vui lòng nhập tỉnh / thành phố.',
            'province.max' => 'Tỉnh / thành phố không được vượt quá 100 ký tự.',
            'note.max' => 'Ghi chú đơn hàng không được vượt quá 2.000 ký tự.',
            'shipping_method.required' => 'Vui lòng chọn đơn vị vận chuyển.',
            'shipping_method.in' => 'Đơn vị vận chuyển đã chọn không hợp lệ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán đã chọn không hợp lệ.',
        ]);

        $couponCode = $request->session()->get(self::COUPON_SESSION_KEY);

        if (is_string($couponCode) && $couponCode !== '') {
            $validated['coupon_code'] = $couponCode;
        }

        if ($validated['payment_method'] === 'vnpay' && ! $this->vnpayService->isConfigured()) {
            return back()->withErrors([
                'payment_method' => 'Cổng VNPay chưa được cấu hình thông tin Sandbox.',
            ])->withInput();
        }

        $order = $this->orderService->placeOrder($request->user(), $validated);
        $request->session()->forget(self::COUPON_SESSION_KEY);
        Mail::to($request->user()->email)->queue(new OrderConfirmationMail($order));

        if ($order->payment_method === 'vnpay') {
            $transaction = $order->paymentTransactions->last();

            return redirect()->away($this->vnpayService->paymentUrl($order, $transaction, (string) $request->ip()));
        }

        return redirect()
            ->route('orders.show', ['orderCode' => $order->order_code])
            ->with('status', 'Đặt hàng thành công.');
    }
}
