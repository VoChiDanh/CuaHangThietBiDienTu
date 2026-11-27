<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Request $request, string $orderCode): View
    {
        $order = Order::query()
            ->with(['items.variant.product', 'statusHistories'])
            ->where('order_code', $orderCode)
            ->where('user_id', $request->user()->getKey())
            ->firstOrFail();

        return view('store.orders.show', ['order' => $order]);
    }

    public function trackingForm(): View
    {
        return view('store.orders.track');
    }

    public function warrantyForm(): View
    {
        return view('store.warranty.lookup');
    }

    public function track(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:50'],
            'receiver_phone' => ['required', 'string', 'max:20'],
        ], [
            'order_code.required' => 'Vui lòng nhập mã đơn hàng.',
            'order_code.max' => 'Mã đơn hàng không được vượt quá 50 ký tự.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'receiver_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        ]);

        $order = Order::query()
            ->with(['items', 'statusHistories'])
            ->where('order_code', strtoupper($validated['order_code']))
            ->where('receiver_phone', $validated['receiver_phone'])
            ->first();

        if ($order === null) {
            return back()->withErrors([
                'tracking' => 'Không tìm thấy đơn hàng với thông tin đã nhập.',
            ])->withInput($request->only('order_code'));
        }

        return view('store.orders.tracking-result', ['order' => $order]);
    }

    public function warrantyLookup(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'order_code' => ['required', 'string', 'max:50'],
            'receiver_phone' => ['required', 'string', 'max:20'],
        ], [
            'order_code.required' => 'Vui lòng nhập mã đơn hàng.',
            'order_code.max' => 'Mã đơn hàng không được vượt quá 50 ký tự.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'receiver_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        ]);

        $order = Order::query()
            ->with('items')
            ->where('order_code', strtoupper($validated['order_code']))
            ->where('receiver_phone', $validated['receiver_phone'])
            ->first();

        if ($order === null) {
            return back()->withErrors([
                'warranty' => 'Không tìm thấy đơn hàng với thông tin đã nhập.',
            ])->withInput($request->only('order_code'));
        }

        return view('store.warranty.result', ['order' => $order]);
    }
}
