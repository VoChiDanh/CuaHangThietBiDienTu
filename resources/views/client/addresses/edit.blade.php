@extends('layouts.client')

@section('title', 'Chỉnh sửa địa chỉ nhận hàng - SmartTech')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <div class="bg-white border border-slate-200 p-6 sm:p-8">
        <div class="mb-6 border-b border-slate-200 pb-4 flex justify-between items-center">
            <div>
                <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">Cập nhật địa chỉ nhận hàng</h1>
                <p class="text-xs text-slate-500 mt-0.5">Sửa đổi thông tin giao nhận</p>
            </div>
            <a href="{{ route('addresses.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold uppercase tracking-wider">
                ← Quay lại
            </a>
        </div>

        <form action="{{ route('addresses.update', $address) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Họ tên người nhận *</label>
                    <input type="text" name="receiver_name" value="{{ old('receiver_name', $address->receiver_name) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Số điện thoại *</label>
                    <input type="tel" name="receiver_phone" value="{{ old('receiver_phone', $address->receiver_phone) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tỉnh / Thành phố *</label>
                    <input type="text" name="province" value="{{ old('province', $address->province) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Quận / Huyện *</label>
                    <input type="text" name="district" value="{{ old('district', $address->district) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phường / Xã *</label>
                    <input type="text" name="ward" value="{{ old('ward', $address->ward) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Địa chỉ chi tiết (Số nhà, tên đường) *</label>
                <input type="text" name="street_address" value="{{ old('street_address', $address->street_address) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }} class="w-4 h-4 text-slate-900 rounded-none border-slate-300 focus:ring-0 focus:ring-offset-0">
                <label for="is_default" class="ml-2 block text-xs text-slate-700 font-semibold cursor-pointer">
                    Đặt làm địa chỉ nhận hàng mặc định
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-200">
                <a href="{{ route('addresses.index') }}" class="px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-none">
                    Cập nhật địa chỉ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection