<!DOCTYPE html>
<html lang="vi" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ request()->hasSession() ? csrf_token() : '' }}">

    <title>@yield('title', 'D&D - Tinh Chọn Công Nghệ, Trọn Vẹn Trải Nghiệm')</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-white text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- TOP ANNOUNCEMENT BAR (MATCHING MOCKUP: #0F172A) -->
    <div class="bg-[#0F172A] text-slate-300 text-xs font-medium py-2 px-4 border-b border-slate-900">
        <div class="max-w-7xl mx-auto flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>Giao hàng miễn phí cho đơn từ 2 triệu &bull; Đổi mới trong 30 ngày</span>
            </div>
            <a href="{{ route('shop.index') }}" class="text-white hover:text-blue-300 font-semibold transition-colors flex items-center gap-1">
                <span>Khám phá ngay</span>
                <span class="text-xs">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo D&D. -->
                <a href="{{ route('home') }}" class="flex items-center gap-1 shrink-0 group">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>

                <div class="flex-1 max-w-xl relative" id="autocomplete-wrapper">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                                <input
                                    type="text"
                                    name="q"
                                    id="autocomplete-input"
                                    value="{{ request('q') }}"
                                    placeholder="Bạn đang tìm thiết bị nào?"
                                    autocomplete="off"
                                    class="w-full pl-4 pr-16 py-2 text-sm bg-slate-100 hover:bg-slate-100/90 focus:bg-white text-slate-900
                                    rounded-full border border-transparent focus:border-slate-300 focus:outline-hidden focus:ring-2
                                    focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                                >

                                <!-- Nút xoá chữ -->
                                <button type="button" id="clear-search-btn" class="hidden absolute right-10 text-slate-400
                                hover:text-slate-600 text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>

                                <!-- Nút kính lúp có thể click để tìm kiếm -->
                                <button type="submit" class="absolute right-3 text-slate-400 hover:text-blue-600 transition-colors p-1 flex
                                items-center justify-center cursor-pointer">
                                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                </button>
                            </div>
                    </form>

                    <!-- Autocomplete Dropdown Popup -->
                    <div id="autocomplete-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                        <div class="p-3 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                            <span><i class="fa-solid fa-bolt text-amber-500 mr-1.5"></i>Gợi ý sản phẩm nhanh</span>
                            <span id="autocomplete-count" class="text-slate-400 font-normal"></span>
                        </div>
                        <div id="autocomplete-results" class="max-h-96 overflow-y-auto divide-y divide-slate-50">
                            <!-- Injected by JavaScript -->
                        </div>
                        <div class="p-2.5 bg-slate-50 text-center border-t border-slate-100">
                            <a href="#" id="view-all-search" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Xem tất cả kết quả &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links & Actions matching screenshot: Tư vấn | Tài khoản | Giỏ hàng -->
                <nav class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                    <!-- Tư vấn Button -->
                    <button 
                        type="button" 
                        onclick="window.SmartTechAI && window.SmartTechAI.openChat()" 
                        class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 rounded-full hover:bg-slate-100 transition-colors flex items-center gap-1.5 cursor-pointer"
                        title="Tư vấn AI & CSKH"
                    >
                        <i class="fa-solid fa-headset text-slate-500"></i>
                        <span class="hidden sm:inline">Tư vấn</span>
                    </button>

                    <!-- Tài khoản Dropdown Menu -->
                    <div class="relative" id="user-menu-wrapper">
                        @guest
                            <button
                                type="button"
                                id="user-menu-btn"
                                onclick="toggleUserDropdown()" 
                                class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 rounded-full hover:bg-slate-100 transition-colors flex items-center gap-1.5 cursor-pointer"
                                title="Tài khoản"
                            >
                                <i class="fa-regular fa-user text-slate-500"></i>
                                <span class="hidden sm:inline">Tài khoản</span>
                            </button>

                            <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 text-xs">
                                <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-semibold transition-colors">
                                    <i class="fa-solid fa-arrow-right-to-bracket text-blue-600 w-4"></i>
                                    <span>Đăng nhập</span>
                                </a>
                                <a href="{{ route('register') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-semibold transition-colors">
                                    <i class="fa-solid fa-user-plus text-emerald-600 w-4"></i>
                                    <span>Đăng ký tài khoản</span>
                                </a>
                                <div class="my-1 border-t border-slate-100"></div>
                                <a href="{{ route('orders.track.form') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                                    <i class="fa-solid fa-truck-fast text-slate-400 w-4"></i>
                                    <span>Tra cứu đơn hàng</span>
                                </a>
                                <a href="{{ route('warranty.form') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                                    <i class="fa-solid fa-shield-halved text-slate-400 w-4"></i>
                                    <span>Tra cứu bảo hành</span>
                                </a>
                            </div>
                        @else
                            <button
                                type="button"
                                id="user-menu-btn"
                                onclick="toggleUserDropdown()"
                                class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition-colors flex items-center gap-1.5 cursor-pointer"
                            >
                                @if(Auth::user()->avatar)
                                    <img src="{{ str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-5 h-5 rounded-full object-cover">
                                @else
                                    <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center uppercase">
                                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                            </button>

                            <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 text-xs">
                                <div class="px-3 py-2 border-b border-slate-100 mb-1 flex items-center gap-2.5">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center uppercase shrink-0">
                                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium transition-colors">
                                    <i class="fa-solid fa-id-card text-blue-600 w-4"></i>
                                    <span>Hồ sơ & Địa chỉ</span>
                                </a>
                                <a href="{{ route('orders.track.form') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium transition-colors">
                                    <i class="fa-solid fa-truck-fast text-emerald-600 w-4"></i>
                                    <span>Tra cứu đơn hàng</span>
                                </a>
                                <a href="{{ route('warranty.form') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-100 font-medium transition-colors">
                                    <i class="fa-solid fa-shield-halved text-amber-500 w-4"></i>
                                    <span>Tra cứu bảo hành</span>
                                </a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                    <div class="my-1 border-t border-slate-100"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-indigo-700 hover:bg-indigo-50 font-bold transition-colors">
                                        <i class="fa-solid fa-gauge-high text-indigo-600 w-4"></i>
                                        <span>Bảng quản trị Admin</span>
                                    </a>
                                @endif
                                <div class="my-1 border-t border-slate-100"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold transition-colors cursor-pointer text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        @endguest
                    </div>

                    <!-- Giỏ hàng Button with live badge -->
                    <a href="{{ route('cart.index') }}" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 rounded-full hover:bg-slate-100 transition-colors flex items-center gap-1.5 relative" title="Giỏ hàng">
                        <i class="fa-solid fa-cart-shopping text-slate-500"></i>
                        <span class="hidden sm:inline">Giỏ hàng</span>
                        <span class="min-w-[18px] h-[18px] px-1 bg-blue-600 text-[10px] font-bold text-white rounded-full flex items-center justify-center ml-0.5" data-cart-count>{{ $cartCount ?? 0 }}</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- SECONDARY CATEGORY BAR (QUICK FILTERS) matching screenshot:
             Máy tính xách tay | Điện thoại | Máy tính bảng | Màn hình | Phụ kiện || Ưu đãi hôm nay | Góc công nghệ ↗ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-100 py-2.5">
            <div class="flex items-center justify-between overflow-x-auto scrollbar-none text-xs font-medium text-slate-600 gap-6 whitespace-nowrap">
                <div class="flex items-center gap-6">
                    <a href="{{ route('shop.index', ['category' => 'laptop']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'laptop' ? 'text-blue-600 font-bold' : '' }}">
                        <span>Máy tính xách tay</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'dien-thoai']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'dien-thoai' ? 'text-blue-600 font-bold' : '' }}">
                        <span>Điện thoại</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'may-tinh-bang']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'may-tinh-bang' ? 'text-blue-600 font-bold' : '' }}">
                        <span>Máy tính bảng</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'laptop-do-hoa' ? 'text-blue-600 font-bold' : '' }}">
                        <span>Màn hình</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'phu-kien']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'phu-kien' ? 'text-blue-600 font-bold' : '' }}">
                        <span>Phụ kiện</span>
                    </a>
                </div>

                <div class="flex items-center gap-5 text-slate-500 pl-4 border-l border-slate-200">
                    <a href="{{ route('shop.index', ['sort' => 'sale']) }}" class="hover:text-blue-600 font-semibold transition-colors flex items-center gap-1">
                        <span>Ưu đãi hôm nay</span>
                    </a>
                    <a href="{{ route('tech-corner.index') }}" class="hover:text-blue-600 font-semibold transition-colors flex items-center gap-0.5 {{ request()->routeIs('tech-corner.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                        <span>Góc công nghệ</span>
                        <span class="text-[10px]">↗</span>
                    </a>
                    <a href="{{ route('compare.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1 text-[11px] text-slate-400">
                        <i class="fa-solid fa-scale-balanced text-[10px]"></i>
                        <span>So sánh</span>
                    </a>
                    <button type="button" onclick="window.openWishlistDrawer && window.openWishlistDrawer()" class="hover:text-red-500 transition-colors flex items-center gap-1 text-[11px] text-slate-400 cursor-pointer" title="Yêu thích">
                        <i class="fa-regular fa-heart text-[10px]"></i>
                        <span id="wishlist-badge" class="hidden px-1 bg-red-500 text-[8px] font-bold text-white rounded-full"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- FOOTER matching mockup clean aesthetic -->
    <footer class="bg-white text-slate-600 text-sm mt-16 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Brand Column -->
                <div class="space-y-3">
                    <div class="flex items-center gap-1">
                        <span class="text-2xl font-black tracking-tight text-slate-900">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Tinh chọn công nghệ.<br>Trọn vẹn trải nghiệm.
                    </p>
                    <p class="text-xs text-slate-400">
                        Cùng bạn chọn đúng điều mình cần.
                    </p>
                </div>

                <!-- Column: Khám phá D&D -->
                <div>
                    <h4 class="text-slate-900 text-xs font-bold tracking-wider mb-4">Khám phá D&D</h4>
                    <ul class="space-y-2.5 text-xs text-slate-500">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition-colors">Về chúng tôi</a></li>
                        <li><a href="{{ route('tech-corner.index') }}" class="hover:text-blue-600 transition-colors">Góc công nghệ</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition-colors">Hệ thống cửa hàng</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition-colors">Cơ hội nghề nghiệp</a></li>
                    </ul>
                </div>

                <!-- Column: Hỗ trợ khách hàng -->
                <div>
                    <h4 class="text-slate-900 text-xs font-bold tracking-wider mb-4">Hỗ trợ khách hàng</h4>
                    <ul class="space-y-2.5 text-xs text-slate-500">
                        <li><a href="{{ route('orders.track.form') }}" class="hover:text-blue-600 transition-colors">Hướng dẫn mua hàng</a></li>
                        <li><a href="{{ route('orders.track.form') }}" class="hover:text-blue-600 transition-colors">Giao hàng &amp; thanh toán</a></li>
                        <li><a href="{{ route('warranty.form') }}" class="hover:text-blue-600 transition-colors">Đổi trả &amp; bảo hành</a></li>
                        <li><a href="{{ route('warranty.form') }}" class="hover:text-blue-600 transition-colors">Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <!-- Column: Luôn ở đây vì bạn -->
                <div>
                    <h4 class="text-slate-900 text-xs font-bold tracking-wider mb-4">Luôn ở đây vì bạn</h4>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li>Tư vấn: <span class="font-bold text-slate-800">1800 0000</span></li>
                        <li>Hỗ trợ: <span class="font-bold text-slate-800">1800 0001</span></li>
                        <li class="text-slate-400">8:00 - 21:00, mỗi ngày</li>
                        <li class="pt-1">
                            <button type="button" onclick="window.SmartTechAI && window.SmartTechAI.openChat()" class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1 cursor-pointer">
                                <span>Kết nối với D&D</span>
                                <span>&nearr;</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar with Copyright & Payment Methods -->
            <div class="border-t border-slate-100 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <div>&copy; 2026 D&D Studio. Công nghệ cho cuộc sống hiện đại.</div>
                <div class="flex items-center gap-6 text-[11px] text-slate-400">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-lock text-slate-300"></i> Thanh toán an toàn</span>
                    <span>Thẻ ngân hàng</span>
                    <span>Chuyển khoản</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- FLOATING AI CHATBOT COMPONENT (Thành viên 3) -->
    @include('components.ai-chat')

    <!-- AUTOCOMPLETE JAVASCRIPT LOGIC -->
    <script>
        (function() {
            const input = document.getElementById('autocomplete-input');
            const dropdown = document.getElementById('autocomplete-dropdown');
            const resultsContainer = document.getElementById('autocomplete-results');
            const countLabel = document.getElementById('autocomplete-count');
            const viewAllLink = document.getElementById('view-all-search');
            const clearBtn = document.getElementById('clear-search-btn');
            let debounceTimer = null;

            if (!input || !dropdown) return;

            input.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                    dropdown.classList.add('hidden');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchAutocomplete(query);
                }, 280);
            });

            clearBtn.addEventListener('click', function() {
                input.value = '';
                clearBtn.classList.add('hidden');
                dropdown.classList.add('hidden');
                input.focus();
            });

            async function fetchAutocomplete(query) {
                try {
                    const res = await fetch(`/api/products/autocomplete?q=${encodeURIComponent(query)}`);
                    const json = await res.json();

                    if (!json.success || !json.results || json.results.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="p-6 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-face-frown text-slate-300 text-2xl mb-2 block"></i>
                                Không tìm thấy thiết bị nào khớp với "<strong>${escapeHtml(query)}</strong>"
                            </div>
                        `;
                        countLabel.textContent = '0 kết quả';
                        viewAllLink.href = `/shop?q=${encodeURIComponent(query)}`;
                        dropdown.classList.remove('hidden');
                        return;
                    }

                    countLabel.textContent = `${json.results.length} gợi ý hàng đầu`;
                    viewAllLink.href = `/shop?q=${encodeURIComponent(query)}`;

                    resultsContainer.innerHTML = json.results.map(item => `
                        <a href="/san-pham/${encodeURIComponent(item.slug)}" class="flex items-center gap-3 p-3 hover:bg-slate-50 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-[#F1F5F9] flex items-center justify-center overflow-hidden shrink-0">
                                <img src="${item.image_url}" alt="${escapeHtml(item.name)}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='/images/placeholder.svg'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">${escapeHtml(item.brand || '')}</span>
                                    <span class="text-[10px] text-slate-400">• ${escapeHtml(item.sku)}</span>
                                </div>
                                <h4 class="text-xs font-semibold text-slate-900 truncate group-hover:text-blue-600 transition-colors">${escapeHtml(item.name)}</h4>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-xs font-bold text-slate-900">${item.sale_price ? item.sale_price : item.price}</span>
                                    ${item.sale_price ? `<span class="text-[10px] text-slate-400 line-through">${item.price}</span>` : ''}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 text-xs group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all"></i>
                        </a>
                    `).join('');

                    dropdown.classList.remove('hidden');
                } catch (e) {
                    console.error('Lỗi tìm kiếm gợi ý:', e);
                }
            }

            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!document.getElementById('autocomplete-wrapper').contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            function escapeHtml(str) {
                return (str || '').replace(/[&<>"']/g, function(m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
                });
            }
        })();
    </script>

    <!-- DRAWER: DANH SÁCH YÊU THÍCH & ĐÃ XEM GẦN ĐÂY (THÀNH VIÊN 3) -->
    <div id="wishlist-backdrop" onclick="closeWishlistDrawer()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden transition-opacity opacity-0" style="display: none;"></div>
    <div id="wishlist-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 flex flex-col hidden" style="display: none; transform: translateX(100%);">
        <!-- Drawer Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-sm shadow-xs border border-red-100">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-800">Bộ Sưu Tập Của Bạn</h3>
                    <p class="text-[11px] text-slate-400">Yêu thích & Sản phẩm vừa xem</p>
                </div>
            </div>
            <button type="button" onclick="closeWishlistDrawer()" class="w-8 h-8 rounded-xl hover:bg-slate-200/60 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Drawer Tabs -->
        <div class="flex p-2 bg-slate-100/80 border-b border-slate-200/60 text-xs font-semibold gap-1">
            <button
                type="button"
                id="tab-btn-wishlist"
                onclick="switchWishlistTab('wishlist')"
                class="flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <i class="fa-solid fa-heart text-red-500 text-xs"></i>
                <span>Yêu thích</span>
                <span id="drawer-wishlist-count" class="ml-1 px-1.5 py-0.2 bg-red-100 text-red-700 text-[10px] font-bold rounded-full">0</span>
            </button>
            <button
                type="button"
                id="tab-btn-recent"
                onclick="switchWishlistTab('recent')"
                class="flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs"></i>
                <span>Đã xem gần đây</span>
            </button>
        </div>

        <!-- Drawer Content List -->
        <div id="drawer-items-list" class="flex-1 overflow-y-auto p-4 space-y-3">
            <div class="py-12 text-center text-slate-400 text-xs">
                <i class="fa-regular fa-heart text-4xl text-slate-300 mb-3 block"></i>
                <p class="font-bold text-slate-600 mb-1">Chưa có sản phẩm yêu thích</p>
                <p class="text-slate-400 mb-4">Hãy bấm vào biểu tượng trái tim ở các sản phẩm bạn ưng ý nhé.</p>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                    Khám phá thiết bị ngay
                </a>
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <span>Khám phá thêm sản phẩm</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
            <a href="{{ route('compare.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <i class="fa-solid fa-scale-balanced text-xs text-slate-400"></i>
                <span>Đến So Sánh</span>
            </a>
        </div>
    </div>

    <script>
        let currentDrawerTab = 'wishlist';

        function openWishlistDrawer(tab = 'wishlist') {
            const backdrop = document.getElementById('wishlist-backdrop');
            const drawer = document.getElementById('wishlist-drawer');
            if (!drawer || !backdrop) return;

            backdrop.style.display = 'block';
            backdrop.classList.remove('hidden');
            drawer.style.display = 'flex';
            drawer.classList.remove('hidden');

            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
                drawer.style.transform = 'translateX(0)';
            }, 10);

            switchWishlistTab(tab);
        }

        function closeWishlistDrawer() {
            const backdrop = document.getElementById('wishlist-backdrop');
            const drawer = document.getElementById('wishlist-drawer');
            if (!drawer || !backdrop) return;

            backdrop.classList.add('opacity-0');
            drawer.classList.add('translate-x-full');
            drawer.style.transform = 'translateX(100%)';

            setTimeout(() => {
                backdrop.classList.add('hidden');
                backdrop.style.display = 'none';
                drawer.classList.add('hidden');
                drawer.style.display = 'none';
            }, 300);
        }

        function switchWishlistTab(tab) {
            currentDrawerTab = tab;
            const tabWishlist = document.getElementById('tab-btn-wishlist');
            const tabRecent = document.getElementById('tab-btn-recent');

            if (tab === 'wishlist') {
                tabWishlist.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5';
                tabRecent.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5';
                loadWishlistItems();
            } else {
                tabRecent.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5';
                tabWishlist.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5';
                loadRecentlyViewedItems();
            }
        }

        async function loadWishlistItems() {
            const listEl = document.getElementById('drawer-items-list');
            listEl.innerHTML = `
                <div class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-red-500 mb-2 block"></i>
                    Đang tải danh sách yêu thích...
                </div>
            `;

            try {
                const res = await fetch('/api/wishlist');
                const data = await res.json();
                const items = data.items || [];

                // Cập nhật badge
                const badge = document.getElementById('wishlist-badge');
                const countLabel = document.getElementById('drawer-wishlist-count');
                countLabel.textContent = items.length;
                if (items.length > 0) {
                    badge.textContent = items.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                if (items.length === 0) {
                    listEl.innerHTML = `
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i class="fa-regular fa-heart text-4xl text-slate-300 mb-3 block"></i>
                            <p class="font-bold text-slate-600 mb-1">Chưa có sản phẩm yêu thích</p>
                            <p class="text-slate-400 mb-4">Hãy bấm vào biểu tượng trái tim ở các sản phẩm bạn ưng ý nhé.</p>
                            <a href="/shop" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                                Khám phá thiết bị ngay
                            </a>
                        </div>
                    `;
                    return;
                }

                listEl.innerHTML = items.map(item => `
                    <div class="p-3 rounded-2xl border border-slate-100 hover:border-slate-200 bg-white shadow-2xs flex items-center gap-3 transition-all group">
                        <div class="w-16 h-16 rounded-xl bg-[#F1F5F9] overflow-hidden flex items-center justify-center shrink-0">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" onerror="this.src='/images/placeholder.svg'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">${item.brand || ''}</span>
                            <h4 class="text-xs font-bold text-slate-900 truncate mb-1">${item.name}</h4>
                            <div class="text-xs font-black text-slate-900">${item.price}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <a href="/shop?q=${encodeURIComponent(item.name)}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                    Xem hàng
                                </a>
                                <span class="text-slate-200 text-xs">•</span>
                                <button type="button" onclick="addToCompareFromDrawer(${item.id}, ${item.root_category_id || 0}, '${escapeHtml(item.root_category_name || '')}')" class="text-[11px] text-slate-500 hover:text-slate-800 cursor-pointer">
                                    + So sánh
                                </button>
                            </div>
                        </div>
                        <button
                            type="button"
                            onclick="removeWishlistItem(${item.id})"
                            class="w-7 h-7 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 flex items-center justify-center transition-colors shrink-0"
                            title="Xóa khỏi yêu thích"
                        >
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `).join('');
            } catch (e) {
                listEl.innerHTML = `<div class="p-6 text-center text-xs text-red-500">Lỗi khi tải danh sách: ${e.message}</div>`;
            }
        }

        async function loadRecentlyViewedItems() {
            const listEl = document.getElementById('drawer-items-list');
            listEl.innerHTML = `
                <div class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-blue-500 mb-2 block"></i>
                    Đang tải sản phẩm vừa xem...
                </div>
            `;

            try {
                const res = await fetch('/api/recently-viewed');
                const data = await res.json();
                const items = data.items || [];

                if (items.length === 0) {
                    listEl.innerHTML = `
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-4xl text-slate-300 mb-3 block"></i>
                            <p class="font-bold text-slate-600 mb-1">Chưa có lịch sử xem</p>
                            <p class="text-slate-400 mb-4">Các thiết bị bạn xem qua sẽ tự động lưu lại ở đây.</p>
                            <a href="/shop" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                                Khám phá thiết bị ngay
                            </a>
                        </div>
                    `;
                    return;
                }

                listEl.innerHTML = items.map(item => `
                    <div class="p-3 rounded-2xl border border-slate-100 hover:border-slate-200 bg-white shadow-2xs flex items-center gap-3 transition-all group">
                        <div class="w-16 h-16 rounded-xl bg-[#F1F5F9] overflow-hidden flex items-center justify-center shrink-0">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" onerror="this.src='/images/placeholder.svg'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">${item.brand || ''}</span>
                            <h4 class="text-xs font-bold text-slate-900 truncate mb-1">${item.name}</h4>
                            <div class="text-xs font-black text-slate-900">${item.price}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <a href="/shop?q=${encodeURIComponent(item.name)}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                    Xem hàng
                                </a>
                                <span class="text-slate-200 text-xs">•</span>
                                <button type="button" onclick="addToCompareFromDrawer(${item.id}, ${item.root_category_id || 0}, '${escapeHtml(item.root_category_name || '')}')" class="text-[11px] text-slate-500 hover:text-slate-800 cursor-pointer">
                                    + So sánh
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (e) {
                listEl.innerHTML = `<div class="p-6 text-center text-xs text-red-500">Lỗi khi tải lịch sử: ${e.message}</div>`;
            }
        }

        async function removeWishlistItem(productId) {
            try {
                const res = await fetch('/api/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                loadWishlistItems();
            } catch (e) {
                console.error(e);
            }
        }

        function addToCompareFromDrawer(productId, rootCatId, rootCatName) {
            productId = parseInt(productId, 10);
            if (isNaN(productId) || productId <= 0) return;

            let ids = [];
            const compareCatId = localStorage.getItem('smarttech_compare_root_id');
            const compareCatName = localStorage.getItem('smarttech_compare_root_name') || 'khác';

            try {
                ids = JSON.parse(localStorage.getItem('smarttech_compare_ids') || '[]');
                if (!Array.isArray(ids)) ids = [];
            } catch (e) {
                ids = [];
            }

            ids = ids.map(id => parseInt(id, 10)).filter(id => !isNaN(id) && id > 0);

            if (ids.length > 0 && compareCatId && rootCatId && parseInt(compareCatId, 10) !== parseInt(rootCatId, 10)) {
                const confirmChange = confirm(
                    `Bảng so sánh hiện đang có thiết bị thuộc nhóm "${compareCatName}".\n\n` +
                    `Hệ thống chỉ hỗ trợ so sánh giữa các thiết bị cùng nhóm danh mục (ví dụ: Máy tính với Máy tính, Điện thoại với Điện thoại).\n\n` +
                    `Bạn có muốn tạo bảng so sánh mới cho nhóm "${rootCatName}" với thiết bị này không?`
                );

                if (!confirmChange) {
                    return;
                }

                ids = [productId];
                localStorage.setItem('smarttech_compare_ids', JSON.stringify(ids));
                if (rootCatId) {
                    localStorage.setItem('smarttech_compare_root_id', rootCatId);
                    localStorage.setItem('smarttech_compare_root_name', rootCatName);
                }
                window.location.href = `/compare?ids=${productId}`;
                return;
            }

            if (!ids.includes(productId)) {
                if (ids.length >= 3) {
                    alert('Bảng so sánh đã đủ 3 thiết bị. Chuyển sang bảng so sánh để quản lý.');
                    window.location.href = `/compare?ids=${ids.join(',')}`;
                    return;
                }
                ids.push(productId);
                localStorage.setItem('smarttech_compare_ids', JSON.stringify(ids));
                if (rootCatId) {
                    localStorage.setItem('smarttech_compare_root_id', rootCatId);
                    localStorage.setItem('smarttech_compare_root_name', rootCatName);
                }
            }
            window.location.href = `/compare?ids=${ids.join(',')}`;
        }

        // Đóng User Dropdown khi click ra ngoài
        document.addEventListener('click', function(e) {
            const container = document.getElementById('user-menu-container');
            const dropdown = document.getElementById('user-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Tự động kiểm tra badge yêu thích khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/wishlist')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('wishlist-badge');
                    if (data && data.items && data.items.length > 0) {
                        badge.textContent = data.items.length;
                        badge.classList.remove('hidden');
                    }
                })
                .catch(() => {});
        });

        // Toggle User Account Dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('user-menu-wrapper');
            const dropdown = document.getElementById('user-dropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // ⌘K / Ctrl+K shortcut to focus search input
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('autocomplete-input');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        // 3D Tilt Micro-interactions
        function initTiltEffects() {
            const tiltElements = document.querySelectorAll('.tilt-card, [data-tilt]');
            tiltElements.forEach(card => {
                if (card.dataset.tiltInitialized) return;
                card.dataset.tiltInitialized = 'true';
                
                card.addEventListener('mousemove', e => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = ((y - centerY) / centerY) * -5;
                    const rotateY = ((x - centerX) / centerX) * 5;
                    card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-4px)`;
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
                });
            });
        }
        document.addEventListener('DOMContentLoaded', initTiltEffects);
        window.initTiltEffects = initTiltEffects;

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            toast.className = `flex items-center gap-2.5 px-4 py-3 rounded-2xl shadow-xl text-xs font-semibold text-white transition-all transform duration-300 translate-y-2 opacity-0 ${isSuccess ? 'bg-zinc-900 border border-zinc-700' : 'bg-rose-600 border border-rose-500'}`;
            toast.innerHTML = `
                <i class="fa-solid ${isSuccess ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-white'} text-sm"></i>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        window.showToast = showToast;

        // AJAX Add to Cart Handler
        async function addToCart(variantId, quantity = 1) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        variant_id: variantId,
                        quantity: quantity
                    })
                });

                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const msg = Object.values(data.errors || {}).flat()[0] || data.message || 'Không thể thêm sản phẩm vào giỏ hàng.';
                    showToast(msg, 'error');
                    return;
                }

                const badges = document.querySelectorAll('[data-cart-count]');
                badges.forEach(badge => {
                    if (typeof data.cart_count !== 'undefined') {
                        badge.textContent = data.cart_count;
                    } else {
                        const current = parseInt(badge.textContent.trim(), 10) || 0;
                        badge.textContent = current + quantity;
                    }
                });

                showToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
            } catch (err) {
                window.location.href = `/cart`;
            }
        }
        window.addToCart = addToCart;
    </script>

    <!-- Global Toast Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 max-w-sm pointer-events-none"></div>

    @stack('scripts')
</body>
</html>
