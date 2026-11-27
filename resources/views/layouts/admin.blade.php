<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống') - D&D Admin</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles & Bundled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .admin-sidebar {
            background-color: #ffffff !important;
            border-right: 1px solid #e2e8f0 !important;
        }
        .admin-nav-link {
            color: #475569 !important;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .admin-nav-link:hover {
            color: #1d4ed8 !important;
            background-color: #f8fafc !important;
        }
        .admin-nav-link.active {
            color: #ffffff !important;
            background-color: #2563eb !important;
            font-weight: 600;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .admin-nav-link.active svg {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-full flex bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar (Clean Light White - Cohesive with Store Theme) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="admin-sidebar fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col justify-between transition-transform duration-200 ease-in-out lg:static shrink-0 shadow-sm lg:shadow-none">
        
        <!-- Brand & Workspace Header -->
        <div>
            <div class="h-16 px-5 border-b border-slate-200 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <span class="text-xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                        D&D<span class="text-blue-600 font-extrabold text-2xl leading-none">.</span>
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-50 text-blue-600 border border-blue-200 uppercase tracking-wider">
                        ADMIN
                    </span>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700 p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Sections -->
            <nav class="p-3.5 space-y-5 overflow-y-auto max-h-[calc(100vh-10rem)]">
                
                <!-- Group: CORE / TỔNG QUAN -->
                @if(auth()->user()->hasPermission('view_dashboard'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-wider text-slate-400 font-bold">TỔNG QUAN</span>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dashboard Báo cáo</span>
                        </a>
                    </div>
                @endif

                <!-- Group: BẢO MẬT & PHÂN QUYỀN (RBAC) -->
                @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_users'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-wider text-slate-400 font-bold">BẢO MẬT &amp; NHÂN SỰ</span>

                        @if(auth()->user()->hasPermission('manage_roles'))
                            <a href="{{ route('admin.roles.index') }}" 
                               class="admin-nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.roles.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Ma trận Phân quyền (RBAC)</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_users'))
                            <a href="{{ route('admin.staff.index') }}" 
                               class="admin-nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.staff.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Quản lý Nhân sự</span>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Group: CÁC MODULE CHUYÊN MÔN -->
                @if(auth()->user()->hasPermission('manage_products') || auth()->user()->hasPermission('manage_categories') || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory') || auth()->user()->hasPermission('manage_coupons') || auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-wider text-slate-400 font-bold">NGHIỆP VỤ CỬA HÀNG</span>

                        @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory') || auth()->user()->isStaff())
                            <a href="{{ route('admin.orders.index') }}" 
                               class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-emerald-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Đơn hàng &amp; Vận chuyển</span>
                            </a>

                            <a href="{{ route('admin.inventory.low-stock') }}" 
                               class="admin-nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.inventory.*') ? 'text-white' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Cảnh báo tồn kho</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_coupons'))
                            <a href="{{ route('admin.coupons.index') }}" 
                               class="admin-nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }} justify-between">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->routeIs('admin.coupons.*') ? 'text-white' : 'text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span>Voucher &amp; Giảm giá</span>
                                </div>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold {{ request()->routeIs('admin.coupons.*') ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-700' }}">
                                    Mới
                                </span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_products'))
                            <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span>Quản lý sản phẩm</span>
                            </a>
                            <a href="{{ route('admin.specifications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.specifications.*', 'admin.specification-groups.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h10M4 18h16M18 10v4m-2-2h4"/></svg>
                                <span>Thông số kỹ thuật</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_categories'))
                            <a href="{{ route('admin.brands.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M3 3h7l11 11-7 7L3 10V3z"/></svg>
                                <span>Thương hiệu</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                            <a href="#reviews" class="admin-nav-link">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span>Đánh giá &amp; Banners</span>
                            </a>
                        @endif

                    </div>
                @endif

            </nav>
        </div>

        <!-- User Profile Quick Bar (Bottom) -->
        <div class="p-3 border-t border-slate-200 bg-slate-50">
            <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-200 shadow-2xs">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-mono text-slate-500 uppercase leading-none">
                            {{ auth()->user()->roleModel->display_name ?? auth()->user()->role }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('home') }}" title="Xem website" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>

    </aside>

    <!-- Main Workspace Area (Clean Light Canvas #f8fafc) -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        
        <!-- Top Operational Header (Pure White, Crisp Border) -->
        <header class="h-16 px-4 sm:px-6 lg:px-8 border-b border-slate-200/80 bg-white/95 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between gap-4 shadow-xs">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-900 font-semibold">@yield('title', 'Bảng điều khiển')</span>
                </div>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Về Cửa Hàng</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold transition-colors">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="p-4 sm:px-6 lg:px-8 pb-0 w-full">
            @if(session('success'))
                <div class="flex items-center gap-3 p-3.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-medium shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 p-3.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-xs font-medium shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-xs space-y-1">
                    <p class="font-bold">Vui lòng kiểm tra lại thông tin:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Main Workspace Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @hasSection('admin-content')
                @yield('admin-content')
            @else
                @yield('content')
            @endif
        </main>

        <!-- Clean Footer -->
        <footer class="h-12 px-6 border-t border-slate-200 bg-white text-slate-500 text-xs flex items-center justify-between">
            <div>D&D. Store &bull; Phân hệ Quản trị 2026</div>
            <div class="font-mono text-[11px] text-slate-400">STATUS: READY</div>
        </footer>

    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
