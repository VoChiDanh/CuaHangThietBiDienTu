@extends('layouts.admin')

@section('page_title', 'Quản lý Mã giảm giá (Vouchers)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 border border-slate-200">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Danh sách Voucher khuyến mãi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Cấu hình điều kiện chiết khấu phần trăm (%) hoặc số tiền cố định</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-colors flex items-center gap-1.5 border border-slate-900">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Thêm Voucher mới</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-4">Mã Voucher</th>
                        <th class="py-2.5 px-4">Mức giảm giá</th>
                        <th class="py-2.5 px-4 text-right">Đơn tối thiểu</th>
                        <th class="py-2.5 px-4 text-center">Lượt dùng</th>
                        <th class="py-2.5 px-4">Thời hạn</th>
                        <th class="py-2.5 px-4 text-center">Trạng thái</th>
                        <th class="py-2.5 px-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-2.5 px-4 font-mono font-bold text-blue-700">
                                <span class="px-2 py-0.5 bg-blue-50 border border-blue-200">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4">
                                @if($coupon->discount_type === 'percentage')
                                    <span class="font-bold text-amber-700">Giảm {{ $coupon->discount_value }}%</span>
                                    @if($coupon->max_discount_amount)
                                        <p class="text-[10px] text-slate-400 font-mono">Tối đa: {{ number_format($coupon->max_discount_amount, 0, ',', '.') }}đ</p>
                                    @endif
                                @else
                                    <span class="font-bold text-emerald-700">Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}đ</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono text-slate-700">
                                {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
                            </td>
                            <td class="py-2.5 px-4 text-center font-mono">
                                <span class="font-bold text-slate-900">{{ $coupon->used_count }}</span>
                                <span class="text-slate-400">/ {{ $coupon->usage_limit ?: '∞' }}</span>
                                <p class="text-[10px] text-slate-400 font-sans">{{ $coupon->usage_limit_per_user }} lượt/user</p>
                            </td>
                            <td class="py-2.5 px-4 text-slate-600 font-mono text-[11px]">
                                @if($coupon->expires_at)
                                    <div>{{ $coupon->expires_at->format('d/m/Y') }}</div>
                                    @if($coupon->expires_at->isPast())
                                        <span class="text-red-600 font-sans font-bold text-[10px]">Đã hết hạn</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-sans italic">Không thời hạn</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2 py-0.5 text-[10px] font-bold border {{ $coupon->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                                        {{ $coupon->is_active ? 'Bật' : 'Khóa' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-2.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="inline-flex px-2 py-1 border border-slate-200 hover:border-blue-600 text-slate-700 hover:text-blue-600 text-xs font-medium">
                                    Sửa
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex px-2 py-1 border border-red-200 hover:bg-red-50 text-red-600 text-xs font-medium">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">Chưa có mã giảm giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
            <div class="p-3 border-t border-slate-200 bg-slate-50">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
