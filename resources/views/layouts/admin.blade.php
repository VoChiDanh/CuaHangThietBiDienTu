<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống') - SmartTech Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full font-sans text-slate-800 antialiased flex flex-col md:flex-row">
    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-950 text-slate-300 shrink-0 flex flex-col justify-between border-r border-slate-800">
        <div>
            <!-- Admin Brand Header -->
            <div class="h-16 flex items-center px-5 bg-slate-900 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-blue-600 text-white flex items-center justify-center font-mono font-bold text-sm">
                        ST
                    </div>
                    <div>
                        <span class="text-sm font-bold tracking-tight text-white">SMARTTECH</span>
                        <span class="block text-[9px] uppercase tracking-widest text-slate-400 font-mono -mt-0.5">Admin Console</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1 text-xs">
                <div class="px-3 pt-3 pb-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                    Tổng quan
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.dashboard*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard & Thống kê</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                    Vận hành kinh doanh
                </div>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.orders.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Quản lý Đơn hàng</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                    Catalog & Sản phẩm
                </div>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.categories.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Danh mục</span>
                </a>
                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.brands.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Thương hiệu</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.products.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Sản phẩm & Biến thể</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                    Khuyến mãi & Tương tác
                </div>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.coupons.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Mã giảm giá (Vouchers)</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.reviews.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span>Kiểm duyệt Đánh giá</span>
                </a>
                <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.banners.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Banner Quảng cáo</span>
                </a>

                @if(auth()->user()->isAdmin())
                    <div class="px-3 pt-4 pb-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        Phân quyền & Hệ thống
                    </div>
                    <a href="{{ route('admin.staffs.index') }}" class="flex items-center gap-2.5 px-3 py-2 font-medium transition-colors border-l-2 {{ request()->routeIs('admin.staffs.*') ? 'border-blue-500 bg-slate-900 text-white' : 'border-transparent text-slate-300 hover:text-white hover:bg-slate-900/60' }}">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Nhân sự & Phân quyền</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-2.5 px-2 py-1.5">
                <div class="w-7 h-7 bg-blue-600 text-white font-mono font-bold text-xs flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-mono uppercase">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-800/80 flex items-center justify-between text-xs px-2">
                <a href="{{ url('/') }}" target="_blank" class="text-slate-400 hover:text-white transition-colors">
                    Trang chủ ↗
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 font-medium">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-30">
            <h1 class="text-base font-bold text-slate-900 tracking-tight">@yield('page_title', 'Bảng điều khiển')</h1>
            <div class="text-xs text-slate-500 font-mono">
                {{ now()->format('d/m/Y') }}
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="p-3 mb-3 text-xs text-emerald-900 bg-emerald-50 border border-emerald-300 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 mb-3 text-xs text-red-900 bg-red-50 border border-red-300 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('info'))
                <div class="p-3 mb-3 text-xs text-blue-900 bg-blue-50 border border-blue-300 flex items-center justify-between">
                    <span>{{ session('info') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-blue-700 font-bold">&times;</button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
