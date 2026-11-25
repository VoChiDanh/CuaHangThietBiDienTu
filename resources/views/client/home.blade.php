@extends('layouts.client')

@section('title', 'SmartTech - Cửa hàng Thiết bị Công nghệ Thông minh 2026')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 py-4">
    <!-- Hero Banners Slider (Sharp geometric high-tech banner) -->
    @php
        $banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->get();
        if ($banners->isEmpty()) {
            $banners = collect([
                (object)[
                    'title' => 'Laptop AI & Smartphone Thế hệ mới 2026',
                    'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1200&q=80',
                    'link_url' => url('/coupons')
                ]
            ]);
        }
        $featuredProducts = \App\Models\Product::where('is_active', true)->with(['category', 'brand'])->latest()->limit(8)->get();
        $activeCoupons = \App\Models\Coupon::where('is_active', true)->latest()->limit(4)->get();
    @endphp

    <div class="relative border border-slate-800 bg-slate-950 aspect-[21/9] sm:aspect-[24/8] overflow-hidden">
        @foreach($banners as $idx => $b)
            <div class="absolute inset-0 transition-opacity duration-700 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
                <img src="{{ $b->image_url }}" alt="{{ $b->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/60 to-transparent flex items-center">
                    <div class="p-6 sm:p-12 max-w-xl text-white space-y-4">
                        <span class="inline-block px-2.5 py-1 bg-white/10 border border-white/20 text-[11px] font-mono font-bold uppercase tracking-widest text-slate-200">Kỷ nguyên AI 2026</span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">{{ $b->title }}</h2>
                        <p class="text-xs sm:text-sm text-slate-300 line-clamp-2">Trải nghiệm tương tác 3D 360 độ siêu nét và tư vấn chọn cấu hình chuyên sâu cùng Trợ lý kỹ thuật AI.</p>
                        <div class="pt-2 flex items-center space-x-3">
                            <a href="{{ $b->link_url ?: url('/coupons') }}" class="px-5 py-2.5 bg-white text-slate-950 hover:bg-slate-100 font-bold text-xs uppercase tracking-wider transition-colors">
                                Khám phá thiết bị
                            </a>
                            <a href="{{ route('coupons.public') }}" class="px-5 py-2.5 bg-slate-900/80 border border-slate-700 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider transition-colors">
                                Lấy mã Voucher
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick Voucher Strip (Engineering tech bar) -->
    @if($activeCoupons->isNotEmpty())
        <div class="bg-slate-900 border border-slate-800 p-4 sm:p-5 text-white flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 border border-indigo-500/40 bg-indigo-950/50 flex items-center justify-center flex-shrink-0 text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide text-white uppercase">Mã giảm giá đang phát hành</h3>
                    <p class="text-xs text-slate-400">Áp dụng trực tiếp tại bước đặt mua thiết bị</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach($activeCoupons as $c)
                    <div class="flex items-center bg-slate-800 border border-slate-700 px-3 py-1.5 text-xs space-x-2">
                        <span class="font-mono font-bold text-amber-400 tracking-wider">{{ $c->code }}</span>
                        <span class="text-[11px] text-slate-300">({{ $c->discount_type === 'percentage' ? '-' . $c->discount_value . '%' : '-' . number_format($c->discount_value, 0, ',', '.') . 'đ' }})</span>
                    </div>
                @endforeach
                <a href="{{ route('coupons.public') }}" class="text-xs font-semibold uppercase tracking-wider text-indigo-400 hover:text-indigo-300 ml-2">
                    Tất cả mã →
                </a>
            </div>
        </div>
    @endif

    <!-- Featured Products Showcase -->
    <div class="space-y-4">
        <div class="flex justify-between items-end border-b border-slate-200 pb-3">
            <div>
                <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest">Danh mục tiêu biểu</span>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Sản phẩm công nghệ nổi bật</h2>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($featuredProducts as $product)
                <div class="bg-white border border-slate-200 hover:border-slate-400 transition-colors flex flex-col justify-between group">
                    <div class="p-4">
                        <div class="aspect-square bg-slate-50 border border-slate-100 mb-3 overflow-hidden flex items-center justify-center relative">
                            <span class="text-4xl text-slate-300 font-extrabold select-none">💻</span>
                            @if($product->sale_price && $product->sale_price < $product->base_price)
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 bg-rose-600 text-white font-mono font-bold text-[10px] uppercase tracking-wider">
                                    -{{ round((($product->base_price - $product->sale_price) / $product->base_price) * 100) }}%
                                </span>
                            @endif
                        </div>

                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block">{{ $product->brand?->name ?? 'Chính hãng' }}</span>
                        <h3 class="font-semibold text-xs sm:text-sm text-slate-900 line-clamp-2 mt-1 group-hover:text-indigo-600 transition-colors">
                            {{ $product->name }}
                        </h3>

                        <div class="mt-3">
                            @if($product->sale_price && $product->sale_price < $product->base_price)
                                <div class="text-sm font-bold font-mono text-rose-600">
                                    {{ number_format($product->sale_price, 0, ',', '.') }}đ
                                </div>
                                <div class="text-[11px] font-mono text-slate-400 line-through">
                                    {{ number_format($product->base_price, 0, ',', '.') }}đ
                                </div>
                            @else
                                <div class="text-sm font-bold font-mono text-slate-900">
                                    {{ number_format($product->base_price, 0, ',', '.') }}đ
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 pt-0">
                        <button type="button" class="w-full py-2 bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                            Xem cấu hình
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 border border-dashed border-slate-300">
                    Đang tải danh sách thiết bị công nghệ...
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection