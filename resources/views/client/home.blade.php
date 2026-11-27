@extends('layouts.client')

@section('title', 'D&D - Tinh Chọn Công Nghệ, Trọn Vẹn Trải Nghiệm')

@section('content')
<div class="space-y-16 py-6 sm:py-8 bg-white overflow-hidden">

    <!-- ==================== 1. HERO SECTION SLIDER (MAY TINH -> DIEN THOAI -> BAN PHIM -> MAY TINH BANG) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $heroSlides = [
                [
                    'key' => 'laptop',
                    'label' => 'Máy tính',
                    'eyebrow' => 'Máy tính thế hệ mới. Thiết kế từ tương lai.',
                    'title_line1' => 'Nâng tầm công việc.',
                    'title_line2' => 'Khởi nguồn cảm hứng.',
                    'desc_line1' => 'Thiết bị bạn yêu. Hiệu năng bạn cần.',
                    'desc_line2' => 'Khám phá lựa chọn phù hợp cho mỗi ngày.',
                    'cta_text' => 'Khám phá máy tính',
                    'cta_url' => route('shop.index', ['category' => 'laptop']),
                    'price' => '18.990.000đ',
                    'note' => 'Trả góp 0% • Bảo hành chính hãng 12-24 tháng',
                    'image' => asset('images/hero-macbook-2026.jpg'),
                    'alt' => 'Máy tính xách tay thế hệ mới tại D&D.',
                ],
                [
                    'key' => 'phone',
                    'label' => 'Điện thoại',
                    'eyebrow' => 'Điện thoại thế hệ mới. Thiết kế đẳng cấp.',
                    'title_line1' => 'Chuẩn mực nhiếp ảnh.',
                    'title_line2' => 'Sức mạnh trong tay.',
                    'desc_line1' => 'Màn hình OLED sắc nét, tần số quét mượt mà.',
                    'desc_line2' => 'Hiệu năng chip flagship vượt trội cho từng khoảnh khắc.',
                    'cta_text' => 'Khám phá điện thoại',
                    'cta_url' => route('shop.index', ['category' => 'dien-thoai']),
                    'price' => '21.990.000đ',
                    'note' => 'Thu cũ đổi mới trợ giá cao • Bảo hành chính hãng 12 tháng',
                    'image' => asset('images/catalog/phone-flagship.jpg'),
                    'alt' => 'Điện thoại thông minh chính hãng tại D&D.',
                ],
                [
                    'key' => 'keyboard',
                    'label' => 'Bàn phím',
                    'eyebrow' => 'Bàn phím cơ không dây. Gõ êm chuẩn studio.',
                    'title_line1' => 'Cảm giác gõ tinh tế.',
                    'title_line2' => 'Tối giản góc làm việc.',
                    'desc_line1' => 'Khung nhôm nguyên khối kết nối đa thiết bị.',
                    'desc_line2' => 'Phản hồi mượt mà, pin bền bỉ nhiều tuần liên tục.',
                    'cta_text' => 'Khám phá bàn phím',
                    'cta_url' => route('shop.index', ['category' => 'phu-kien']),
                    'price' => '1.890.000đ',
                    'note' => 'Bảo hành 1 đổi 1 chính hãng • Giao nhanh 2 giờ',
                    'image' => asset('images/catalog/accessories-kit.jpg'),
                    'alt' => 'Bàn phím & chuột không dây cao cấp tại D&D.',
                ],
                [
                    'key' => 'tablet',
                    'label' => 'Máy tính bảng',
                    'eyebrow' => 'Máy tính bảng thế hệ mới. Siêu mỏng, siêu mạnh.',
                    'title_line1' => 'Sáng tạo mọi lúc.',
                    'title_line2' => 'Mỏng nhẹ từng đường nét.',
                    'desc_line1' => 'Màn hình sắc nét chuẩn màu chuyên nghiệp.',
                    'desc_line2' => 'Linh hoạt biến hóa cho học tập, thiết kế và giải trí.',
                    'cta_text' => 'Khám phá máy tính bảng',
                    'cta_url' => route('shop.index', ['category' => 'may-tinh-bang']),
                    'price' => '14.990.000đ',
                    'note' => 'Tặng ưu đãi bút & bàn phím rời • Trả góp 0%',
                    'image' => asset('images/catalog/tablet-pro.jpg'),
                    'alt' => 'Máy tính bảng chính hãng tại D&D.',
                ],
            ];
        @endphp

        <div id="hero-banner-container" class="relative overflow-hidden rounded-3xl bg-[#F5F7FA] border border-slate-200/60 shadow-xs">
            <!-- Subtle background ambient glow -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-100/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch relative z-10">
                <!-- Left text content (synced dynamically with active slide) -->
                <div class="lg:col-span-6 p-8 sm:p-12 lg:p-14 flex flex-col justify-between space-y-6">
                    <div id="hero-text-content" class="space-y-5 transition-all duration-300 ease-out">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50/90 border border-blue-100/80 text-[11px] font-bold uppercase tracking-wider text-blue-600 w-fit">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                            <span id="hero-eyebrow">{{ $heroSlides[0]['eyebrow'] }}</span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-[38px] xl:text-[42px] font-black tracking-tight leading-[1.15] text-slate-900">
                            <span id="hero-title-1" class="block">{{ $heroSlides[0]['title_line1'] }}</span>
                            <span id="hero-title-2" class="block">{{ $heroSlides[0]['title_line2'] }}</span>
                        </h1>

                        <p class="text-sm sm:text-base text-slate-500 max-w-md leading-relaxed font-normal">
                            <span id="hero-desc-1" class="block">{{ $heroSlides[0]['desc_line1'] }}</span>
                            <span id="hero-desc-2" class="block">{{ $heroSlides[0]['desc_line2'] }}</span>
                        </p>

                        <div class="flex flex-wrap items-center gap-3.5 pt-2">
                            <a 
                                id="hero-cta-link"
                                href="{{ $heroSlides[0]['cta_url'] }}" 
                                class="px-7 py-3.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md shadow-blue-600/20 flex items-center gap-2 hover:-translate-y-0.5"
                            >
                                <span id="hero-cta-text">{{ $heroSlides[0]['cta_text'] }}</span>
                                <span class="text-xs">&rarr;</span>
                            </a>

                            <button 
                                type="button" 
                                onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                                class="px-6 py-3.5 rounded-full bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200/90 shadow-2xs transition-all flex items-center gap-2 cursor-pointer hover:-translate-y-0.5"
                            >
                                <i class="fa-solid fa-headset text-slate-400 text-xs"></i>
                                <span>Tư vấn chọn máy</span>
                            </button>
                        </div>

                        <!-- Price highlight -->
                        <div class="pt-5 border-t border-slate-200/70">
                            <div class="text-xs text-slate-400">
                                Từ <strong id="hero-price" class="text-slate-900 text-base font-black">{{ $heroSlides[0]['price'] }}</strong>
                            </div>
                            <div id="hero-note" class="text-[11px] text-slate-400 mt-0.5">
                                {{ $heroSlides[0]['note'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Slide Navigation Pills (MacBook | iPhone | Bàn phím | Máy tính bảng) -->
                    <div class="flex flex-wrap items-center gap-2 pt-2" role="tablist" aria-label="Sản phẩm nổi bật">
                        @foreach($heroSlides as $idx => $slide)
                            <button
                                type="button"
                                onclick="window.goToHeroSlide({{ $idx }})"
                                data-hero-dot="{{ $idx }}"
                                class="hero-slide-pill px-3.5 py-1.5 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $idx === 0 ? 'bg-slate-900 text-white shadow-xs' : 'bg-white/80 hover:bg-white text-slate-600 border border-slate-200/80' }}"
                            >
                                {{ $slide['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Right visual element: HORIZONTAL PRODUCT SHOWCASE SLIDER -->
                <div class="lg:col-span-6 relative h-[320px] sm:h-[390px] lg:h-auto lg:min-h-[500px] overflow-hidden group">
                    <!-- Sliding Track -->
                    <div id="hero-slide-track" class="absolute inset-0 flex w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]">
                        @foreach($heroSlides as $idx => $slide)
                            <div class="w-full h-full shrink-0 relative overflow-hidden">
                                <img
                                    src="{{ $slide['image'] }}"
                                    alt="{{ $slide['alt'] }}"
                                    class="hero-parallax-img w-full h-full object-cover object-center transition-transform duration-500 ease-out scale-[1.03] will-change-transform"
                                >
                            </div>
                        @endforeach
                    </div>

                    <!-- Soft gradient seam blend into hero left panel -->
                    <div class="hidden lg:block absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-[#F5F7FA] via-[#F5F7FA]/45 to-transparent pointer-events-none z-10"></div>
                    <div class="lg:hidden absolute inset-x-0 top-0 h-14 bg-gradient-to-b from-[#F5F7FA] to-transparent pointer-events-none z-10"></div>

                    <!-- Compact Prev / Next Glass Controls (Top Right) -->
                    <div class="absolute top-4 right-4 z-20 flex items-center gap-2">
                        <button
                            type="button"
                            onclick="window.prevHeroSlide()"
                            class="w-9 h-9 rounded-full dnd-glass hover:bg-white text-slate-700 hover:text-blue-600 flex items-center justify-center transition-all shadow-xs cursor-pointer"
                            aria-label="Slide trước"
                        >
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button
                            type="button"
                            onclick="window.nextHeroSlide()"
                            class="w-9 h-9 rounded-full dnd-glass hover:bg-white text-slate-700 hover:text-blue-600 flex items-center justify-center transition-all shadow-xs cursor-pointer"
                            aria-label="Slide tiếp theo"
                        >
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>

                    <!-- Floating Glassmorphic Pill Badge -->
                    <div class="absolute bottom-5 right-5 left-5 sm:left-auto sm:w-80 p-3.5 rounded-2xl dnd-glass-pill animate-dnd-float flex items-center gap-3.5 z-20">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100/50">
                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Right visual element with floating AI badge -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <div class="rounded-2xl overflow-hidden bg-white/60 p-4 border border-white/80 shadow-md">
                            <img 
                                src="/storage/products/macbook-pro-14-inch-m4-pro-main.jpg" 
                                alt="Laptop tinh tế" 
                                class="w-full h-64 sm:h-72 object-contain"
                                onerror="this.src='/images/placeholder.svg'"
                            >
                            
                            <!-- Floating AI pill card on bottom right -->
                            <div class="mt-2 p-3 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-100 shadow-md flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                                </div>
                                <div class="text-xs">
                                    <div class="font-bold text-slate-900">Lựa chọn thông minh hơn</div>
                                    <div class="text-slate-400 text-[11px]">Có trợ lý AI đồng hành cùng bạn</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 2. VALUE PROPOSITIONS (4 USPs) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-4 border-y border-slate-100">
            <div class="flex items-center gap-3.5 p-2">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm shadow-2xs">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">100% chính hãng</div>
                    <div class="text-[11px] text-slate-400">An tâm từ lần chạm đầu tiên</div>
                </div>
            </div>

            <div class="flex items-center gap-3.5 p-2">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm shadow-2xs">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Giao nhanh toàn quốc</div>
                    <div class="text-[11px] text-slate-400">Miễn phí với đơn từ 2 triệu</div>
                </div>
            </div>

            <div class="flex items-center gap-3.5 p-2">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm shadow-2xs">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Đổi mới 30 ngày</div>
                    <div class="text-[11px] text-slate-400">Nếu có lỗi từ nhà sản xuất</div>
                </div>
            </div>

            <div class="flex items-center gap-3.5 p-2">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm shadow-2xs">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Tư vấn tận tâm</div>
                    <div class="text-[11px] text-slate-400">Đúng nhu cầu, đúng ngân sách</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 3. BẠN ĐANG TÌM GÌ? (6 CATEGORIES) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Bạn đang tìm gì?</h2>
            <p class="text-xs text-slate-400">Công nghệ phù hợp với mọi nhu cầu.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Máy tính xách tay -->
            <a 
                href="{{ route('shop.index', ['category' => 'laptop']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Máy tính xách tay
                </h3>
            </a>

            <!-- Điện thoại -->
            <a 
                href="{{ route('shop.index', ['category' => 'dien-thoai']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Điện thoại
                </h3>
            </a>

            <!-- Máy tính bảng -->
            <a 
                href="{{ route('shop.index', ['category' => 'may-tinh-bang']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-tablet-screen-button"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Máy tính bảng
                </h3>
            </a>

            <!-- Màn hình -->
            <a 
                href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Màn hình
                </h3>
            </a>

            <!-- Âm thanh -->
            <a 
                href="{{ route('shop.index', ['category' => 'am-thanh']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-headphones"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Âm thanh
                </h3>
            </a>

            <!-- Phụ kiện -->
            <a 
                href="{{ route('shop.index', ['category' => 'phu-kien']) }}"
                class="tilt-card group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-300 shadow-2xs transition-all flex flex-col items-center text-center"
                data-tilt
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-2xs">
                    <i class="fa-solid fa-computer-mouse"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Phụ kiện
                </h3>
            </a>
        </div>
    </section>

    <!-- ==================== 4. ĐƯỢC YÊU THÍCH. ĐÁNG ĐỂ SỞ HỮU. ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Được yêu thích. Đáng để sở hữu.</h2>
                <p class="text-xs text-slate-400">Những lựa chọn nổi bật, sẵn sàng cho hành trình mới của bạn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Xem tất cả sản phẩm</span>
                <span class="text-xs group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </a>
        </div>

        <!-- Filter Tabs matching screenshot: Nổi bật | Học tập & làm việc | Sáng tạo nội dung | Giải trí (Pill shape) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-6">
            <button 
                type="button" 
                onclick="switchFeaturedTab('featured', this)"
                class="tab-btn px-5 py-2 rounded-full text-xs font-bold transition-all bg-slate-900 text-white shadow-xs cursor-pointer"
                data-tab="featured"
            >
                Nổi bật
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('study', this)"
                class="tab-btn px-5 py-2 rounded-full text-xs font-medium transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="study"
            >
                Học tập &amp; làm việc
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('creative', this)"
                class="tab-btn px-5 py-2 rounded-full text-xs font-medium transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="creative"
            >
                Sáng tạo nội dung
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('entertainment', this)"
                class="tab-btn px-5 py-2 rounded-full text-xs font-medium transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="entertainment"
            >
                Giải trí
            </button>
        </div>

        <!-- Featured Products Grid (4 columns with 3D Tilt micro-interaction) -->
        <div id="featured-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $badges = ['Bán chạy', 'Tiết kiệm 2 triệu', 'Giảm 15%', 'Lựa chọn của D&D'];
                $badgeColors = [
                    'bg-blue-50 text-blue-600 border-blue-100',
                    'bg-amber-50 text-amber-700 border-amber-100',
                    'bg-orange-50 text-orange-600 border-orange-100',
                    'bg-teal-50 text-teal-700 border-teal-100',
                ];
            @endphp

            @foreach($featuredProducts as $index => $product)
            @php
                $badgeText = $badges[$index % count($badges)];
                $badgeColor = $badgeColors[$index % count($badgeColors)];
            @endphp
            <div class="tilt-card group relative rounded-2xl bg-white p-4 border border-slate-200/80 hover:border-slate-300 shadow-2xs transition-all flex flex-col justify-between" data-tilt>
                
                <!-- Badge on top-left -->
                <div class="absolute top-4 left-4 z-10">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }} shadow-2xs">
                        {{ $badgeText }}
                    </span>
                </div>

                <!-- Product Thumbnail linked to PDP -->
                <a href="{{ route('products.show', $product['slug']) }}" class="w-full h-48 rounded-2xl bg-[#F1F5F9] overflow-hidden mb-3.5 block relative">
                    <img 
                        src="{{ $product['image_url'] }}" 
                        alt="{{ $product['name'] }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </a>

                <!-- Info -->
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-1.5 text-[11px] text-amber-500 font-semibold">
                        <i class="fa-solid fa-star text-[10px]"></i>
                        <span>4.9 (1.2k đánh giá)</span>
                    </div>

                    <a href="{{ route('products.show', $product['slug']) }}" class="block">
                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 leading-snug">
                            {{ $product['name'] }}
                        </h3>
                    </a>

                    <!-- Specs details matching screenshot: 16GB bộ nhớ · 512GB lưu trữ -->
                    <div class="text-[11px] text-slate-400 font-medium truncate">
                        @if(!empty($product['screen_size']))
                            <span>{{ $product['screen_size'] }}" &bull; </span>
                        @endif
                        @if(!empty($product['weight_grams']))
                            <span>{{ $product['weight_grams'] < 1000 ? $product['weight_grams'].'g' : number_format($product['weight_grams']/1000, 1).'kg' }} &bull; </span>
                        @endif
                        <span>Chính hãng 100%</span>
                    </div>
                </div>

                <!-- Price & Action -->
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <div class="flex items-baseline justify-between gap-2 mb-3">
                        <div>
                            <span class="text-sm font-extrabold text-slate-900">
                                {{ $product['price_formatted'] }}
                            </span>
                            @if($product['is_on_sale'])
                                <span class="text-[11px] text-slate-400 line-through ml-1.5">
                                    {{ $product['base_price_formatted'] }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Còn hàng
                        </span>
                    </div>

                    <!-- Button: + Thêm vào giỏ (Pill shape matching mockup) -->
                    <button 
                        type="button"
                        onclick="addToCart({{ $product['default_variant_id'] ?? $product['id'] }}, 1)"
                        class="w-full py-2.5 px-4 rounded-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs hover:shadow-xs"
                    >
                        <span>Thêm vào giỏ</span>
                        <span class="font-normal text-sm leading-none">+</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ==================== 5. TRỢ LÝ AI (KHÔNG RÀNH CÔNG NGHỆ? ĐÃ CÓ D&D HIỂU BẠN) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-[#F5F3FF] via-[#F3EEFF] to-[#EDE9FE] p-8 sm:p-12 border border-purple-100 shadow-xs relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <!-- Left promo -->
                <div class="lg:col-span-6 space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100/90 text-purple-700 font-bold text-[10px] tracking-wider uppercase border border-purple-200/60">
                        <i class="fa-solid fa-sparkles text-[10px]"></i>
                        TRỢ LÝ AI D&D 2026
                    </span>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        Không rành công nghệ?<br>
                        Đã có D&D hiểu bạn.
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-md">
                        Chia sẻ nhu cầu và ngân sách. Trợ lý AI sẽ giúp bạn tìm thiết bị phù hợp, chỉ trong vài phút.
                    </p>

                    <div class="pt-2">
                        <button 
                            type="button" 
                            onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="px-6 py-3 rounded-full bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-bold shadow-md shadow-purple-600/20 transition-all flex items-center gap-2 cursor-pointer hover:-translate-y-0.5"
                        >
                            <span>Tìm thiết bị phù hợp</span>
                            <span class="text-xs">&rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Right interactive suggestion card matching screenshot chat simulation -->
                <div class="lg:col-span-6">
                    <div class="rounded-2xl bg-white p-5 border border-slate-100 shadow-sm space-y-3.5 max-w-md ml-auto">
                        <div class="flex items-center gap-1.5 text-emerald-600 font-semibold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Luôn sẵn sàng lắng nghe</span>
                        </div>

                        <!-- User query bubble -->
                        <div class="p-3.5 rounded-2xl bg-slate-100/90 text-xs text-slate-800 font-medium">
                            Tôi cần máy tính học thiết kế, ngân sách 25 triệu.
                        </div>

                        <!-- AI Response bubble -->
                        <div class="p-3.5 rounded-2xl bg-purple-50/70 border border-purple-100/70 text-xs text-slate-800 space-y-2.5">
                            <div class="flex items-start gap-1.5">
                                <span class="text-purple-600 font-bold text-xs mt-0.5">✦</span>
                                <p class="text-[11px] leading-relaxed text-slate-600">
                                    Mình gợi ý máy có bộ nhớ từ 16GB và màn hình chuẩn màu để bạn sáng tạo thoải mái hơn.
                                </p>
                            </div>

                            <!-- Clickable Filter Chips (Kết nối trực tiếp vào bộ lọc shop) -->
                            <div class="flex flex-wrap gap-1.5 pt-0.5">
                                <a 
                                    href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" 
                                    class="px-3 py-1 rounded-full bg-white hover:bg-purple-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Màn hình chuẩn màu
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['category' => 'laptop']) }}" 
                                    class="px-3 py-1 rounded-full bg-white hover:bg-purple-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Bộ nhớ 16GB
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['weight' => 'ultralight']) }}" 
                                    class="px-3 py-1 rounded-full bg-white hover:bg-purple-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Mỏng nhẹ
                                </a>
                            </div>
                        </div>

                        <div class="pt-1 text-right">
                            <a href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" class="text-xs font-semibold text-purple-700 hover:text-purple-800 flex items-center justify-end gap-1">
                                <span>Khám phá 3 lựa chọn dành cho bạn</span>
                                <span class="text-xs">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 6. MỘT CHÚT CẢM HỨNG CÔNG NGHỆ (GÓC CÔNG NGHỆ) ==================== -->
    <section id="goc-cong-nghe" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Một chút cảm hứng công nghệ.</h2>
                <p class="text-xs text-slate-400">Gợi ý hữu ích để bạn chọn đúng và dùng thích hơn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Khám phá góc công nghệ</span>
                <span class="text-xs group-hover:translate-x-0.5 transition-transform">&nearr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Article 1: Gọn một góc bàn -->
            <a href="{{ route('shop.index') }}" class="tilt-card rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block" data-tilt>
                <div class="h-48 bg-[#F8FAFC] overflow-hidden relative">
                    <img 
                        src="/storage/products/macbook-air-13-inch-m4-2025-main.jpg" 
                        alt="Góc làm việc" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        GÓC LÀM VIỆC
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Gọn một góc bàn.<br>Rộng một khoảng sáng tạo.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>5 phút đọc &bull; Cảm hứng mỗi ngày</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>

            <!-- Article 2: Chọn máy tính đầu tiên -->
            <a href="{{ route('shop.index', ['category' => 'laptop']) }}" class="tilt-card rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block" data-tilt>
                <div class="h-48 bg-[#F8FAFC] overflow-hidden relative">
                    <img 
                        src="/storage/products/asus-expertbook-b9-oled-sieu-nhe-990g-main.jpg" 
                        alt="Chọn máy tính" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        TƯ VẤN CHỌN MUA
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Chọn máy tính đầu tiên:<br>Bắt đầu từ điều bạn cần.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>7 phút đọc &bull; Hướng dẫn chọn mua</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>

            <!-- Article 3: Pin bền hơn -->
            <a href="{{ route('shop.index', ['battery' => 'high']) }}" class="tilt-card rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block" data-tilt>
                <div class="h-48 bg-[#F8FAFC] overflow-hidden relative">
                    <img 
                        src="/storage/products/airpods-max-usb-c-2024-main.jpg" 
                        alt="Pin bền" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        MẸO HAY MỖI NGÀY
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Pin bền hơn từ những<br>thói quen nhỏ mỗi ngày.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>4 phút đọc &bull; Sử dụng thông minh</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- ==================== 7. ĐIỀU HAY TIẾP THEO, GỬI RIÊNG BẠN (NEWSLETTER) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-[#F8FAFC] border border-slate-200/70 p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xs">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Điều hay tiếp theo, gửi riêng bạn.</h3>
                <p class="text-xs text-slate-400">Nhận ưu đãi mới và những gợi ý công nghệ đáng đọc. Vừa đủ hữu ích, không làm phiền.</p>
            </div>

            <div class="w-full md:w-auto max-w-md shrink-0">
                <form onsubmit="event.preventDefault(); window.showToast('Cảm ơn bạn đã đăng ký nhận tin từ D&D.!'); this.reset();" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="email" 
                        placeholder="Địa chỉ thư điện tử của bạn" 
                        required
                        class="px-5 py-3 rounded-full bg-white border border-slate-200 text-slate-800 placeholder-slate-400 text-xs focus:outline-hidden focus:border-blue-500 w-full sm:w-64 shadow-2xs"
                    >
                    <button 
                        type="submit" 
                        class="px-6 py-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors whitespace-nowrap shadow-xs cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span>Đăng ký nhận tin</span>
                        <span class="text-xs">&rarr;</span>
                    </button>
                </form>
                <p class="text-[10px] text-slate-400 mt-2 text-center md:text-left">Bằng việc đăng ký, bạn đồng ý với Chính sách bảo mật của D&D</p>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
// ==================== HERO SLIDER + PARALLAX MICRO-INTERACTION ====================
(function initHeroSlider() {
    const slides = @json($heroSlides);
    const track = document.getElementById('hero-slide-track');
    const textBox = document.getElementById('hero-text-content');
    const banner = document.getElementById('hero-banner-container');
    if (!track || !slides.length) return;

    const eyebrowEl = document.getElementById('hero-eyebrow');
    const title1El = document.getElementById('hero-title-1');
    const title2El = document.getElementById('hero-title-2');
    const desc1El = document.getElementById('hero-desc-1');
    const desc2El = document.getElementById('hero-desc-2');
    const ctaLinkEl = document.getElementById('hero-cta-link');
    const ctaTextEl = document.getElementById('hero-cta-text');
    const priceEl = document.getElementById('hero-price');
    const noteEl = document.getElementById('hero-note');
    const pills = document.querySelectorAll('.hero-slide-pill');
    const parallaxImgs = document.querySelectorAll('.hero-parallax-img');

    let currentIndex = 0;
    let autoplayTimer = null;
    let textSwapTimer = null;

    function updateSlidePills(index) {
        pills.forEach((pill, i) => {
            if (i === index) {
                pill.className = 'hero-slide-pill px-3.5 py-1.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-slate-900 text-white shadow-xs';
            } else {
                pill.className = 'hero-slide-pill px-3.5 py-1.5 rounded-full text-[11px] font-bold transition-all cursor-pointer bg-white/80 hover:bg-white text-slate-600 border border-slate-200/80';
            }
        });
    }

    function applySlideText(slide) {
        if (eyebrowEl) eyebrowEl.textContent = slide.eyebrow;
        if (title1El) title1El.textContent = slide.title_line1;
        if (title2El) title2El.textContent = slide.title_line2;
        if (desc1El) desc1El.textContent = slide.desc_line1;
        if (desc2El) desc2El.textContent = slide.desc_line2;
        if (ctaLinkEl) ctaLinkEl.href = slide.cta_url;
        if (ctaTextEl) ctaTextEl.textContent = slide.cta_text;
        if (priceEl) priceEl.textContent = slide.price;
        if (noteEl) noteEl.textContent = slide.note;
    }

    function goToSlide(index, resetTimer = true) {
        const targetIndex = ((index % slides.length) + slides.length) % slides.length;
        if (targetIndex === currentIndex && resetTimer) return;
        currentIndex = targetIndex;

        track.style.transform = `translate3d(-${currentIndex * 100}%, 0, 0)`;
        updateSlidePills(currentIndex);

        if (textBox) {
            clearTimeout(textSwapTimer);
            textBox.style.opacity = '0';
            textBox.style.transform = 'translateY(6px)';
            textSwapTimer = setTimeout(() => {
                applySlideText(slides[currentIndex]);
                textBox.style.opacity = '1';
                textBox.style.transform = 'translateY(0)';
            }, 160);
        } else {
            applySlideText(slides[currentIndex]);
        }

        if (resetTimer) {
            startAutoplay();
        }
    }

    function startAutoplay() {
        stopAutoplay();
        autoplayTimer = setInterval(() => {
            goToSlide(currentIndex + 1, false);
        }, 5000);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    window.goToHeroSlide = (idx) => goToSlide(idx, true);
    window.nextHeroSlide = () => goToSlide(currentIndex + 1, true);
    window.prevHeroSlide = () => goToSlide(currentIndex - 1, true);

    if (banner) {
        banner.addEventListener('mouseenter', stopAutoplay);
        banner.addEventListener('mouseleave', () => {
            startAutoplay();
            parallaxImgs.forEach((img) => {
                img.style.transform = 'scale(1.03) translate3d(0, 0, 0)';
            });
        });

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            banner.addEventListener('mousemove', (e) => {
                const activeImg = parallaxImgs[currentIndex];
                if (!activeImg) return;
                const rect = banner.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width - 0.5) * -14;
                const y = ((e.clientY - rect.top) / rect.height - 0.5) * -10;
                activeImg.style.transform = `scale(1.05) translate3d(${x.toFixed(2)}px, ${y.toFixed(2)}px, 0)`;
            });
        }

        let touchStartX = 0;
        banner.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0]?.screenX || 0;
        }, { passive: true });
        banner.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0]?.screenX || 0;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 45) {
                if (diff > 0) {
                    window.nextHeroSlide();
                } else {
                    window.prevHeroSlide();
                }
            }
        }, { passive: true });
    }

    startAutoplay();
})();

// ==================== FEATURED PRODUCTS TAB SWITCHER ====================
async function switchFeaturedTab(tabName, btn) {
    // 1. Cập nhật giao diện nút tab
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn px-5 py-2 rounded-full text-xs font-medium transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer';
    });
    btn.className = 'tab-btn px-5 py-2 rounded-full text-xs font-bold transition-all bg-slate-900 text-white shadow-xs cursor-pointer';

    // 2. Loading skeleton
    const grid = document.getElementById('featured-products-grid');
    grid.innerHTML = Array(4).fill(0).map(() => `
        <div class="rounded-2xl bg-white p-4 border border-slate-200/80 animate-pulse space-y-3">
            <div class="w-full h-44 bg-slate-100 rounded-2xl"></div>
            <div class="h-3 bg-slate-100 rounded-sm w-1/3"></div>
            <div class="h-4 bg-slate-100 rounded-sm w-3/4"></div>
            <div class="h-4 bg-slate-100 rounded-sm w-1/2"></div>
        </div>
    `).join('');

    const badges = ['Bán chạy', 'Tiết kiệm 2 triệu', 'Giảm 15%', 'Lựa chọn của D&D'];
    const badgeColors = [
        'bg-blue-50 text-blue-600 border-blue-100',
        'bg-amber-50 text-amber-700 border-amber-100',
        'bg-orange-50 text-orange-600 border-orange-100',
        'bg-teal-50 text-teal-700 border-teal-100',
    ];

    // 3. Gọi API lấy sản phẩm theo tab
    try {
        const res = await fetch(`/api/products/featured-tabs?tab=${encodeURIComponent(tabName)}`);
        const data = await res.json();

        if (data.success && data.products) {
            grid.innerHTML = data.products.map((p, idx) => `
                <div class="tilt-card group relative rounded-2xl bg-white p-4 border border-slate-200/80 hover:border-slate-300 shadow-2xs transition-all flex flex-col justify-between" data-tilt>
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${badgeColors[idx % badgeColors.length]} shadow-2xs">
                            ${badges[idx % badges.length]}
                        </span>
                    </div>

                    <a href="/san-pham/${p.slug}" class="w-full h-48 rounded-2xl bg-[#F1F5F9] overflow-hidden mb-3.5 block relative">
                        <img 
                            src="${p.image_url}" 
                            alt="${p.name}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            onerror="this.src='/images/placeholder.svg'"
                        >
                    </a>

                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-1.5 text-[11px] text-amber-500 font-semibold">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span>4.9 (1.2k đánh giá)</span>
                        </div>

                        <a href="/san-pham/${p.slug}" class="block">
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 leading-snug">
                                ${p.name}
                            </h3>
                        </a>

                        <div class="text-[11px] text-slate-400 font-medium truncate">
                            ${p.screen_size ? `<span>${p.screen_size}" &bull; </span>` : ''}
                            ${p.weight_grams ? `<span>${p.weight_grams < 1000 ? p.weight_grams + 'g' : (p.weight_grams/1000).toFixed(1) + 'kg'} &bull; </span>` : ''}
                            <span>Chính hãng 100%</span>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <div class="flex items-baseline justify-between gap-2 mb-3">
                            <div>
                                <span class="text-sm font-extrabold text-slate-900">${p.price_formatted}</span>
                                ${p.is_on_sale ? `<span class="text-[11px] text-slate-400 line-through ml-1.5">${p.base_price_formatted}</span>` : ''}
                            </div>
                            <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Còn hàng
                            </span>
                        </div>

                        <button 
                            type="button"
                            onclick="addToCart(${p.default_variant_id || p.id}, 1)"
                            class="w-full py-2.5 px-4 rounded-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs hover:shadow-xs"
                        >
                            <span>Thêm vào giỏ</span>
                            <span class="font-normal text-sm leading-none">+</span>
                        </button>
                    </div>
                </div>
            `).join('');

            // Re-bind 3D tilt effects
            if (window.initTiltEffects) window.initTiltEffects();
        }
    } catch (e) {
        console.error('Lỗi khi chuyển tab sản phẩm:', e);
    }
}
</script>
@endpush
@endsection
