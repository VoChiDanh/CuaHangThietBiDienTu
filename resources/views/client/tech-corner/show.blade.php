@extends('layouts.client')

@section('title', $article['title'] . ' - Góc Công Nghệ - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-10">
    <!-- Breadcrumb -->
    <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-400 font-medium">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
        <span>/</span>
        <a href="{{ route('tech-corner.index') }}" class="hover:text-blue-600 transition-colors">Góc công nghệ</a>
        <span>/</span>
        <a href="{{ route('tech-corner.index', ['topic' => $article['topic_key']]) }}" class="hover:text-blue-600 transition-colors">{{ $article['category_label'] }}</a>
        <span>/</span>
        <span class="text-slate-700 font-semibold line-clamp-1">{{ $article['title'] }}</span>
    </nav>

    <!-- Article Header & Layout -->
    <article class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Reading Column -->
        <div class="lg:col-span-8 space-y-7">
            <header class="space-y-4">
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <a
                        href="{{ route('tech-corner.index', ['topic' => $article['topic_key']]) }}"
                        class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px] uppercase tracking-wider hover:bg-blue-100 transition-colors"
                    >
                        {{ $article['category_label'] }}
                    </a>
                    <span class="text-slate-400 font-medium">{{ $article['read_time'] }} &bull; {{ $article['tag_label'] }}</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-400">Cập nhật {{ $article['published_at'] }}</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-slate-900 leading-tight">
                    {{ $article['title'] }}
                </h1>

                <p class="text-sm sm:text-base text-slate-500 leading-relaxed">
                    {{ $article['excerpt'] }}
                </p>

                <div class="flex items-center justify-between pt-1 text-xs text-slate-400">
                    <span>Biên tập bởi <strong class="text-slate-700">{{ $article['author'] }}</strong></span>
                    <a href="{{ route('tech-corner.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                        <span>&larr;</span>
                        <span>Tất cả bài viết</span>
                    </a>
                </div>
            </header>

            <!-- Hero Cover Image -->
            <div class="rounded-3xl overflow-hidden bg-[#F8FAFC] border border-slate-200/80 shadow-2xs">
                <img
                    src="{{ asset($article['image']) }}"
                    alt="{{ $article['title'] }}"
                    class="w-full h-64 sm:h-[400px] object-cover"
                    onerror="this.src='/images/placeholder.svg'"
                >
            </div>

            <!-- Article Sections -->
            <div class="space-y-6">
                @foreach($article['sections'] as $section)
                    <section class="rounded-2xl bg-white border border-slate-200/80 p-6 sm:p-7 space-y-4 shadow-2xs">
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                            {{ $section['heading'] }}
                        </h2>

                        <div class="space-y-3 text-sm text-slate-600 leading-relaxed">
                            @foreach($section['paragraphs'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>

                        @if(! empty($section['tips']))
                            <div class="rounded-2xl bg-[#F8FAFC] border border-slate-200/70 p-4 space-y-2">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-lightbulb"></i>
                                    <span>Ghi nhớ nhanh</span>
                                </div>
                                <ul class="space-y-1.5 text-xs sm:text-sm text-slate-700">
                                    @foreach($section['tips'] as $tip)
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-check text-blue-600 text-[10px] mt-1.5 shrink-0"></i>
                                            <span>{{ $tip }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>

        <!-- Sticky Reading Sidebar -->
        <aside class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">
            <!-- Key Takeaways -->
            <div class="rounded-2xl bg-[#F8FAFC] border border-slate-200/80 p-6 space-y-4">
                <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-slate-900">
                    <span class="w-7 h-7 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <span>Điểm cốt lõi cần nhớ</span>
                </div>
                <ul class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    @foreach($article['highlights'] as $index => $highlight)
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-white border border-slate-200 text-slate-800 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <span>{{ $highlight }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Action Box -->
            <div class="rounded-2xl bg-slate-900 text-white p-6 space-y-4 shadow-sm">
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400 block">HỖ TRỢ CHỌN THIẾT BỊ</span>
                    <h3 class="text-base font-bold leading-snug">Cần gợi ý theo đúng ngân sách &amp; nhu cầu?</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Trợ lý D&amp;D AI có thể lọc nhanh các mẫu phù hợp hoặc mở bảng so sánh trực tiếp cho bạn.
                    </p>
                </div>
                <div class="space-y-2.5">
                    <a
                        href="{{ route('shop.index', $article['shop_route_params']) }}"
                        class="w-full py-2.5 px-4 rounded-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-colors flex items-center justify-center gap-1.5"
                    >
                        <span>{{ $article['shop_cta_label'] }}</span>
                        <span>&rarr;</span>
                    </a>
                    <button
                        type="button"
                        onclick="window.DndAI && window.DndAI.openChat()"
                        class="w-full py-2.5 px-4 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-colors flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="fa-solid fa-wand-magic-sparkles text-blue-300"></i>
                        <span>Hỏi Trợ lý D&amp;D AI</span>
                    </button>
                    <a
                        href="{{ route('compare.index') }}"
                        class="w-full py-2 px-4 rounded-full text-slate-300 hover:text-white text-xs font-medium transition-colors flex items-center justify-center gap-1.5"
                    >
                        <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                        <span>Mở công cụ so sánh thiết bị</span>
                    </a>
                </div>
            </div>
        </aside>
    </article>

    <!-- Recommended Products Matching the Article -->
    @if($recommendedProducts->isNotEmpty())
        <section class="pt-4 border-t border-slate-200/70 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block">GỢI Ý LIÊN QUAN</span>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $article['recommended_heading'] }}</h2>
                </div>
                <a href="{{ route('shop.index', $article['shop_route_params']) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <span>Xem thêm tại cửa hàng</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($recommendedProducts as $product)
                    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block h-44 rounded-xl bg-[#F8FAFC] overflow-hidden mb-3">
                                <img
                                    src="{{ $product->images->first()?->image_url ?? '/images/placeholder.svg' }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                    onerror="this.src='/images/placeholder.svg'"
                                >
                            </a>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-blue-600 mb-1">
                                {{ $product->brand?->name ?? 'D&D.' }}
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block mb-2">
                                <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $product->name }}
                                </h3>
                            </a>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                <div class="text-sm font-black text-slate-900">
                                    {{ number_format($product->current_price, 0, ',', '.') }} đ
                                </div>
                                @if($product->is_on_sale)
                                    <div class="text-[11px] text-slate-400 line-through">
                                        {{ number_format($product->base_price, 0, ',', '.') }} đ
                                    </div>
                                @endif
                            </div>
                            <a
                                href="{{ route('products.show', $product->slug) }}"
                                class="px-3 py-1.5 rounded-full bg-slate-900 hover:bg-blue-600 text-white text-[11px] font-bold transition-colors shrink-0"
                            >
                                Xem máy
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Related Articles -->
    @if(count($relatedArticles) > 0)
        <section class="pt-4 border-t border-slate-200/70 space-y-5">
            <div class="flex items-end justify-between gap-3">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Đọc tiếp từ Góc công nghệ</h2>
                    <p class="text-xs text-slate-400">Những gợi ý khác giúp bạn dùng công nghệ nhẹ nhàng hơn.</p>
                </div>
                <a href="{{ route('tech-corner.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <span>Xem tất cả</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($relatedArticles as $related)
                    @include('client.tech-corner._card', ['article' => $related, 'showExcerpt' => true])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
