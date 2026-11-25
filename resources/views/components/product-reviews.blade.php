@props(['productId'])

@php
    $product = \App\Models\Product::with(['reviews' => function($q) {
        $q->where('status', 'approved')->with('user')->latest();
    }])->find($productId);

    $reviews = $product ? $product->reviews : collect();
    $totalReviews = $reviews->count();
    $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 5.0;

    $starsCount = [
        5 => $reviews->where('rating', 5)->count(),
        4 => $reviews->where('rating', 4)->count(),
        3 => $reviews->where('rating', 3)->count(),
        2 => $reviews->where('rating', 2)->count(),
        1 => $reviews->where('rating', 1)->count(),
    ];

    $canReview = false;
    if (auth()->check()) {
        $canReview = \App\Models\OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id())
                  ->whereIn('status', ['completed', 'delivered']);
        })->whereHas('variant', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->whereDoesntHave('review')->exists();
    }
@endphp

<div class="bg-white border border-slate-200 p-6 sm:p-8 space-y-6" id="productReviewsSection">
    <div class="border-b border-slate-200 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div>
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Đánh giá & Nhận xét xác thực</h3>
            <p class="text-xs text-slate-500 mt-0.5">Hệ thống đánh giá được xác thực từ khách hàng đã đặt mua và nhận sản phẩm thành công</p>
        </div>
        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono text-[10px] font-bold uppercase tracking-wider">
            Verified Purchase
        </span>
    </div>

    <!-- Rating Summary Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center bg-slate-50 border border-slate-200 p-6">
        <!-- Score -->
        <div class="text-center md:border-r md:border-slate-200 md:pr-6">
            <div class="text-5xl font-extrabold text-slate-900 font-mono">{{ $avgRating }}<span class="text-xl text-slate-400">/5</span></div>
            <div class="flex justify-center items-center space-x-1 my-2 text-amber-500">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current' : 'text-slate-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
            <p class="text-xs text-slate-500 font-mono">Dựa trên <strong>{{ $totalReviews }}</strong> đánh giá</p>
        </div>

        <!-- Rating Progress Bars -->
        <div class="md:col-span-2 space-y-2">
            @foreach([5, 4, 3, 2, 1] as $star)
                @php
                    $count = $starsCount[$star];
                    $pct = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                @endphp
                <div class="flex items-center text-xs space-x-3 font-mono">
                    <span class="w-8 font-bold text-slate-700 flex items-center space-x-1">
                        <span>{{ $star }}★</span>
                    </span>
                    <div class="flex-1 h-2 bg-slate-200 overflow-hidden">
                        <div class="h-full bg-slate-900 transition-all duration-300" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-16 text-right text-slate-500 text-[11px]">{{ $count }} ({{ $pct }}%)</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Review Form for Verified Buyers -->
    @if($canReview)
        <div class="p-6 bg-slate-50 border border-slate-300">
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-900 mb-1">
                Gửi nhận xét từ người mua thực tế
            </h4>
            <p class="text-xs text-slate-500 mb-4">Chia sẻ trải nghiệm sử dụng thiết bị (hiệu năng, nhiệt độ, pin...)</p>

            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productId }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Số sao đánh giá *</label>
                    <div class="flex items-center space-x-2">
                        @for($s = 1; $s <= 5; $s++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $s }}" {{ $s === 5 ? 'checked' : '' }} class="sr-only">
                                <span class="text-xl text-amber-500 hover:opacity-80 inline-block">★</span>
                            </label>
                        @endfor
                        <span class="text-xs font-mono font-semibold text-slate-600 ml-2">5/5 sao - Hài lòng</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nội dung nhận xét *</label>
                    <textarea name="comment" rows="3" required class="w-full p-3 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Viết đánh giá thực tế của bạn..."></textarea>
                </div>

                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors">
                    Gửi đánh giá
                </button>
            </form>
        </div>
    @endif

    <!-- Reviews List -->
    <div class="space-y-4 pt-2">
        @forelse($reviews as $rev)
            <div class="p-5 bg-white border border-slate-200 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-slate-900 text-white font-mono font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr($rev->user?->name ?? 'K', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-slate-900 text-xs">{{ $rev->user?->name ?? 'Khách hàng' }}</span>
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Đã mua
                                </span>
                            </div>
                            <div class="flex items-center space-x-1 text-amber-500 text-xs mt-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                                <span class="text-[10px] text-slate-400 font-mono ml-2">{{ $rev->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-700 leading-relaxed pl-11">
                    {{ $rev->comment }}
                </p>

                @if($rev->admin_reply)
                    <div class="ml-11 p-3 bg-slate-50 border border-slate-200 text-xs space-y-1">
                        <p class="font-bold text-indigo-700 uppercase tracking-wider text-[11px]">
                            Phản hồi từ Cửa hàng SmartTech:
                        </p>
                        <p class="text-slate-700 text-[11px] leading-relaxed">{{ $rev->admin_reply }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-10 text-slate-400 text-xs border border-dashed border-slate-200">
                Chưa có đánh giá nào cho sản phẩm này.
            </div>
        @endforelse
    </div>
</div>