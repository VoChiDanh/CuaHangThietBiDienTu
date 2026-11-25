@extends('layouts.client')

@section('title', 'Kho Voucher & Mã giảm giá độc quyền - SmartTech')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="relative z-10 max-w-xl space-y-2">
            <span class="inline-block px-2.5 py-1 bg-white/10 border border-white/20 text-[11px] font-mono font-bold uppercase tracking-widest text-slate-200">Khuyến mãi hệ thống</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kho Voucher Giảm Giá SmartTech</h1>
            <p class="text-xs sm:text-sm text-slate-300">Sao chép mã voucher và nhập tại màn hình giỏ hàng / thanh toán để được chiết khấu trực tiếp trên đơn hàng.</p>
        </div>
    </div>

    <!-- Voucher Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($coupons as $coupon)
            <div class="bg-white border border-slate-200 hover:border-slate-400 transition-colors flex flex-col justify-between">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3 border-b border-slate-100 pb-3">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-500">Mã ưu đãi</span>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold text-[10px] uppercase tracking-wider">
                            Đang áp dụng
                        </span>
                    </div>

                    <h3 class="font-extrabold text-slate-900 text-xl font-mono">
                        @if($coupon->discount_type === 'percentage')
                            Giảm {{ $coupon->discount_value }}%
                        @else
                            Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                        @endif
                    </h3>

                    <div class="text-xs text-slate-600 space-y-1.5 mt-3 pt-3 border-t border-slate-100 font-mono">
                        <p class="flex justify-between">
                            <span class="text-slate-500 font-sans">Đơn tối thiểu:</span>
                            <strong class="text-slate-900">{{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ</strong>
                        </p>
                        @if($coupon->max_discount_amount)
                            <p class="flex justify-between">
                                <span class="text-slate-500 font-sans">Mức giảm tối đa:</span>
                                <strong class="text-slate-900">{{ number_format($coupon->max_discount_amount, 0, ',', '.') }}đ</strong>
                            </p>
                        @endif
                        <p class="flex justify-between">
                            <span class="text-slate-500 font-sans">Hạn sử dụng:</span>
                            <strong class="text-slate-900">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn' }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Footer with code and Copy Button -->
                <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-wider">Mã nhập:</span>
                        <span class="font-mono font-bold text-slate-900 text-sm tracking-wider">{{ $coupon->code }}</span>
                    </div>
                    <button type="button" onclick="copyVoucher('{{ $coupon->code }}', this)" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors">
                        Sao chép mã
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white border border-slate-200 p-8">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Chưa có mã giảm giá nào đang phát hành</h3>
                <p class="text-xs text-slate-500 mt-1">Các chương trình ưu đãi mới sẽ liên tục được cập nhật tại trang này.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function copyVoucher(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const originalText = btn.textContent;
            btn.textContent = 'ĐÃ CHÉP!';
            btn.classList.remove('bg-slate-900', 'hover:bg-slate-800');
            btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
                btn.classList.add('bg-slate-900', 'hover:bg-slate-800');
            }, 2000);
        });
    }
</script>
@endsection