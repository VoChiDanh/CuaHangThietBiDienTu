<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartTech - Hệ thống Bán lẻ Thiết bị Công nghệ Thông minh')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-blue-600 selection:text-white">
    <!-- Top Utility Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-1.5 py-0.5 text-[10px] font-semibold bg-blue-600 text-white uppercase tracking-wider">Thông báo</span>
                <span class="text-slate-200">Khai trương phân hệ công nghệ mới: Trợ lý AI & Trực quan hóa 3D cho Laptop & Smartphone.</span>
            </div>
            <div class="hidden md:flex items-center gap-6 text-slate-400">
                <span>Hotline: <strong class="text-slate-100 font-mono">1900 6868</strong></span>
                <span>Hỗ trợ kỹ thuật 24/7</span>
                <span>Giao hàng toàn quốc</span>
            </div>
        </div>
    </div>

    <!-- Main Store Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-6">
                <!-- Brand Logo -->
                <div class="flex items-center gap-6 shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-slate-900 text-white flex items-center justify-center font-bold font-mono text-base tracking-tighter border border-slate-800">
                            ST
                        </div>
                        <div class="leading-none">
                            <span class="text-xl font-black tracking-tight text-slate-900">SMART<span class="text-blue-600">TECH</span></span>
                            <span class="block text-[9px] uppercase font-semibold text-slate-400 tracking-widest mt-0.5">E-Commerce System</span>
                        </div>
                    </a>
                </div>

                <!-- Search Input Bar -->
                <div class="hidden lg:flex flex-1 max-w-xl">
                    <form action="{{ route('products.search', [], false) ?: url('/') }}" method="GET" class="relative w-full">
                        <input type="text" name="q" placeholder="Tìm kiếm máy tính xách tay, điện thoại, mã SKU..." class="w-full pl-10 pr-24 py-2 border border-slate-300 bg-slate-50/50 text-slate-900 text-xs focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 bg-slate-900 text-white text-xs font-semibold hover:bg-blue-600 transition-colors">
                            Tìm
                        </button>
                    </form>
                </div>

                <!-- Navigation Actions -->
                <div class="flex items-center gap-4">
                    <!-- Voucher Portal Link -->
                    <a href="{{ route('coupons.public') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 px-2.5 py-1.5 border border-slate-200 hover:border-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        <span>Mã giảm giá</span>
                    </a>

                    <!-- User Account / Auth Actions -->
                    @auth
                        <div class="relative" id="userMenuContainer">
                            <button type="button" id="userMenuButton" class="flex items-center gap-2 p-1.5 border border-slate-200 hover:border-slate-400 bg-white text-xs text-left focus:outline-none transition-colors">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 object-cover border border-slate-200">
                                @else
                                    <div class="w-6 h-6 bg-slate-900 text-white font-bold font-mono text-[11px] flex items-center justify-center">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden md:inline-block font-semibold text-slate-800 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="hidden absolute right-0 mt-1 w-60 bg-white border border-slate-200 shadow-lg py-1 z-50">
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-[10px] uppercase font-semibold text-slate-400">Tài khoản</p>
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <div class="mt-1">
                                        @if(auth()->user()->isAdmin())
                                            <span class="inline-block px-1.5 py-0.5 bg-red-50 text-red-700 font-bold text-[10px] border border-red-200">Quản trị viên</span>
                                        @elseif(auth()->user()->isStaff())
                                            <span class="inline-block px-1.5 py-0.5 bg-blue-50 text-blue-700 font-bold text-[10px] border border-blue-200">Nhân viên</span>
                                        @else
                                            <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] border border-slate-200">Khách hàng</span>
                                        @endif
                                    </div>
                                </div>

                                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 border-b border-slate-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Bảng điều khiển Quản trị</span>
                                    </a>
                                @endif

                                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Hồ sơ cá nhân</span>
                                </a>

                                <a href="{{ route('addresses.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Sổ địa chỉ nhận hàng</span>
                                </a>

                                <div class="border-t border-slate-100 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 text-left">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white">
                                Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="px-3 py-1.5 text-xs font-semibold bg-slate-900 text-white hover:bg-blue-600 transition-colors">
                                Đăng ký
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Notification Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-3 mb-3 text-xs text-emerald-900 bg-emerald-50 border border-emerald-300 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 mb-3 text-xs text-red-900 bg-red-50 border border-red-300 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 mb-3 text-xs text-red-900 bg-red-50 border border-red-300">
                <p class="font-bold mb-1">Vui lòng kiểm tra lại thông tin:</p>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="grow py-6">
        @yield('content')
    </main>

    <!-- Clean Corporate Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 text-xs mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-white text-slate-900 font-bold font-mono text-sm flex items-center justify-center">ST</div>
                        <span class="text-base font-bold text-white tracking-tight">SMARTTECH</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        Nền tảng thương mại điện tử chuyên sâu thiết bị công nghệ cao với mô hình tương tác 3D và Trợ lý AI.
                    </p>
                    <p class="mt-4 text-[11px] text-slate-500">© {{ date('Y') }} SmartTech. All rights reserved.</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3">Chính sách & Hỗ trợ</h4>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">Chính sách bảo hành 1:1</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Quy trình giao nhận</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3">Phân hệ hệ thống</h4>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="{{ route('coupons.public') }}" class="hover:text-white transition-colors">Trung tâm Khuyến mãi</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Cổng Quản trị viên</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tài liệu API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3">Liên hệ kỹ thuật</h4>
                    <p class="text-xs">Đường dây nóng: <strong class="text-white font-mono">1900 6868</strong></p>
                    <p class="text-xs mt-1">Email: <a href="mailto:support@smarttech.vn" class="text-blue-400 hover:underline">support@smarttech.vn</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Dropdown Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('userMenuButton');
            const dropdown = document.getElementById('userDropdown');
            if (btn && dropdown) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', function () {
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
