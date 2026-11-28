@extends('layouts.client')

@section('title', 'Góc Công Nghệ - Cảm Hứng & Tư Vấn Chọn Thiết Bị - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Góc công nghệ</span>
    </nav>

    <!-- Minimalist Hero Header -->
    <section class="relative overflow-hidden rounded-3xl bg-[#F5F7FA] border border-slate-200/70 p-7 sm:p-10">
        <div class="max-w-2xl space-y-3">
            <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-blue-600">
                <i class="fa-solid fa-sparkles"></i> GÓC CÔNG NGHỆ D&amp;D.
            </span>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-slate-900 leading-tight">
                Một chút cảm hứng công nghệ.
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Những chia sẻ gọn gàng, thực tế giúp bạn sắp xếp góc làm việc tinh tế hơn, chọn đúng máy tính phù hợp và giữ thiết bị bền bỉ mỗi ngày.
            </p>
        </div>
    </section>

    <!-- Topic Filter Pills -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            @foreach($topics as $topicKey => $topicLabel)
                <a
                    href="{{ route('tech-corner.index', $topicKey === 'all' ? [] : ['topic' => $topicKey]) }}"
                    class="px-4 py-2 rounded-full text-xs font-bold transition-all border {{ $activeTopic === $topicKey ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400 hover:text-blue-600' }}"
                >
                    {{ $topicLabel }}
                </a>
            @endforeach
        </div>
        <span class="text-xs text-slate-400 font-medium">
            Hiển thị <strong class="text-slate-700">{{ count($articles) }}</strong> bài viết
        </span>
    </div>

    <!-- Article Showcase -->
    @if(count($articles) === 1)
        @php $featured = $articles[0]; @endphp
        <a
            href="{{ route('tech-corner.show', $featured['slug']) }}"
            class="grid grid-cols-1 lg:grid-cols-12 rounded-3xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group"
        >
            <div class="lg:col-span-6 h-64 lg:h-auto min-h-[280px] bg-[#F8FAFC] overflow-hidden">
                <img
                    src="{{ asset($featured['image']) }}"
                    alt="{{ $featured['title'] }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                    onerror="this.src='/images/placeholder.svg'"
                >
            </div>
            <div class="lg:col-span-6 p-7 sm:p-10 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider">
                        {{ $featured['category_label'] }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $featured['title'] }}
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        {{ $featured['excerpt'] }}
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">{{ $featured['read_time'] }} &bull; {{ $featured['tag_label'] }}</span>
                    <span class="font-bold text-blue-600 flex items-center gap-1">
                        <span>Đọc bài viết</span>
                        <span>&rarr;</span>
                    </span>
                </div>
            </div>
        </a>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($articles as $article)
                @include('client.tech-corner._card', ['article' => $article, 'showExcerpt' => true])
            @endforeach
        </div>
    @endif

    <!-- Assistant & Store CTA -->
    <section class="rounded-3xl bg-[#F8FAFC] border border-slate-200/70 p-7 sm:p-9 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <h2 class="text-lg sm:text-xl font-black text-slate-900">Vẫn phân vân chưa biết chọn thiết bị nào?</h2>
            <p class="text-xs sm:text-sm text-slate-500">Trò chuyện nhanh với Trợ lý AI D&amp;D hoặc xem ngay bộ sưu tập sản phẩm đã được chọn lọc sẵn.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button
                type="button"
                onclick="window.DndAI && window.DndAI.openChat()"
                class="px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors cursor-pointer flex items-center gap-2 shadow-xs"
            >
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Hỏi D&amp;D AI ngay</span>
            </button>
            <a
                href="{{ route('shop.index') }}"
                class="px-5 py-2.5 rounded-full bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold transition-colors flex items-center gap-1.5"
            >
                <span>Khám phá cửa hàng</span>
                <span>&rarr;</span>
            </a>
        </div>
    </section>
</div>
@endsection
