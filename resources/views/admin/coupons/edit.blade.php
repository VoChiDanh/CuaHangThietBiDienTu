@extends('layouts.admin')

@section('page_title', 'Chỉnh sửa Voucher Khuyến mãi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Cập nhật Voucher: <span class="font-mono text-indigo-600">{{ $coupon->code }}</span></h2>
            <p class="text-xs text-slate-500 mt-0.5">Chỉnh sửa thông số điều kiện, hạn mức và thời gian áp dụng</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mã Voucher *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs font-mono font-bold uppercase text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Loại giảm giá *</label>
                <select name="discount_type" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    <option value="fixed_amount" {{ old('discount_type', $coupon->discount_type) === 'fixed_amount' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Giá trị giảm *</label>
                <input type="number" step="any" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mức giảm tối đa (VNĐ)</label>
                <input type="number" step="any" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Đơn hàng tối thiểu *</label>
                <input type="number" step="any" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tổng số lượt dùng</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Giới hạn / Khách hàng *</label>
                <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" required min="1" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Trạng thái phát hành *</label>
                <select name="is_active" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    <option value="1" {{ old('is_active', $coupon->is_active) ? 'selected' : '' }}>Kích hoạt cho phép sử dụng</option>
                    <option value="0" {{ !old('is_active', $coupon->is_active) ? 'selected' : '' }}>Tạm khóa mã</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Thời gian bắt đầu</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Thời gian hết hạn</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-5 border-t border-slate-200">
            <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                Hủy bỏ
            </a>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-none">
                Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
