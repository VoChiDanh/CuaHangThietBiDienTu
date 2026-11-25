@extends('layouts.admin')

@section('page_title', 'Tạo Voucher Khuyến mãi mới')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between bg-white p-4 border border-slate-200">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Thiết lập mã giảm giá mới</h2>
            <p class="text-xs text-slate-500">Cấu hình điều kiện chiết khấu và hạn mức áp dụng</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs text-blue-600 hover:underline font-semibold">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white p-6 border border-slate-200 space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mã Voucher (In hoa) *</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-3 py-2 border border-slate-300 font-mono text-xs uppercase focus:outline-none focus:border-blue-600" placeholder="SMARTTECH50K">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Loại giảm giá *</label>
                <select name="discount_type" class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600 bg-white">
                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    <option value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Giá trị giảm *</label>
                <input type="number" step="any" name="discount_value" value="{{ old('discount_value') }}" required class="w-full px-3 py-2 border border-slate-300 font-mono text-xs focus:outline-none focus:border-blue-600" placeholder="10 hoặc 50000">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mức giảm tối đa (khi giảm %)</label>
                <input type="number" step="any" name="max_discount_amount" value="{{ old('max_discount_amount') }}" class="w-full px-3 py-2 border border-slate-300 font-mono text-xs focus:outline-none focus:border-blue-600" placeholder="vd: 200000">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Giá trị đơn tối thiểu *</label>
                <input type="number" step="any" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" required class="w-full px-3 py-2 border border-slate-300 font-mono text-xs focus:outline-none focus:border-blue-600" placeholder="0">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lượt dùng tối đa toàn sàn</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="w-full px-3 py-2 border border-slate-300 font-mono text-xs focus:outline-none focus:border-blue-600" placeholder="Bỏ trống nếu không giới hạn">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lượt dùng mỗi tài khoản *</label>
                <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', 1) }}" required min="1" class="w-full px-3 py-2 border border-slate-300 font-mono text-xs focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Trạng thái *</label>
                <select name="is_active" class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600 bg-white">
                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Thời gian bắt đầu</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Thời gian hết hạn</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Hủy bỏ
            </a>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-colors">
                Lưu mã giảm giá
            </button>
        </div>
    </form>
</div>
@endsection
