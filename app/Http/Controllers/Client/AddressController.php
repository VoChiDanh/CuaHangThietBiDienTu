<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->latest()->get();

        return view('client.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        return view('client.addresses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn Phường/Xã.',
            'street_address.required' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
        ]);

        $user = auth()->user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'receiver_name' => $validated['receiver_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'street_address' => $validated['street_address'],
            'is_default' => $isDefault,
        ]);

        return redirect()->route('addresses.index')->with('success', 'Thêm địa chỉ giao hàng mới thành công!');
    }

    public function edit(UserAddress $address): View|RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        return view('client.addresses.edit', compact('address'));
    }

    public function update(Request $request, UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update(array_merge($validated, ['is_default' => $isDefault]));

        return redirect()->route('addresses.index')->with('success', 'Cập nhật địa chỉ nhận hàng thành công!');
    }

    public function setDefault(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt địa chỉ này làm mặc định khi thanh toán!');
    }

    public function destroy(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // Nếu xóa địa chỉ mặc định, tự động gán địa chỉ đầu tiên còn lại làm mặc định
        if ($wasDefault) {
            $next = auth()->user()->addresses()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Đã xóa địa chỉ thành công.');
    }
}
