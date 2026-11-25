@extends('layouts.client')

@section('title', 'Hồ sơ cá nhân - SmartTech')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6 border-b border-slate-200 pb-4">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Hồ sơ cá nhân</h1>
        <p class="text-xs text-slate-500 mt-0.5">Quản lý thông tin tài khoản và bảo mật mật khẩu của bạn</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info Summary Card -->
        <div class="md:col-span-1">
            <div class="bg-white border border-slate-200 p-6 space-y-4">
                <div class="w-16 h-16 bg-slate-900 text-white font-mono font-bold text-xl flex items-center justify-center">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 font-mono truncate">{{ $user->email }}</p>
                </div>

                <div>
                    @if($user->isAdmin())
                        <span class="inline-block px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">Quản trị viên (Admin)</span>
                    @elseif($user->isStaff())
                        <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold uppercase tracking-wider">Nhân viên (Staff)</span>
                    @else
                        <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">Khách hàng thành viên</span>
                    @endif
                </div>

                <div class="border-t border-slate-200 pt-3 text-xs space-y-2 text-slate-600 font-mono">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-sans">Số điện thoại:</span>
                        <strong class="text-slate-900">{{ $user->phone ?: 'Chưa cập nhật' }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-sans">Ngày tham gia:</span>
                        <strong class="text-slate-900">{{ $user->created_at?->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <a href="{{ route('addresses.index') }}" class="block w-full py-2 px-3 bg-slate-50 hover:bg-slate-100 text-slate-800 font-semibold text-xs border border-slate-300 text-center uppercase tracking-wider transition-colors">
                    Sổ địa chỉ nhận hàng ({{ $user->addresses()->count() }})
                </a>
            </div>
        </div>

        <!-- Forms: Profile and Password -->
        <div class="md:col-span-2 space-y-6">
            <!-- Update Info Form -->
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-200 pb-3 mb-5">
                    Thông tin định danh
                </h3>
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Địa chỉ Email</label>
                        <input type="email" value="{{ $user->email }}" readonly disabled class="w-full px-3 py-2 border border-slate-200 bg-slate-50 text-slate-500 text-xs font-mono cursor-not-allowed">
                        <span class="text-[11px] text-slate-400 mt-1 block">Email dùng để đăng nhập hệ thống và không thể thay đổi.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Họ và tên *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Số điện thoại liên hệ</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="0912 345 678">
                    </div>

                    <div class="flex justify-end pt-3 border-t border-slate-200">
                        <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors">
                            Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password Form -->
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-200 pb-3 mb-5">
                    Đổi mật khẩu bảo mật
                </h3>
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mật khẩu hiện tại *</label>
                        <input type="password" name="current_password" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="••••••••">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mật khẩu mới *</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Tối thiểu 8 ký tự">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Xác nhận mật khẩu *</label>
                            <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-slate-200">
                        <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors">
                            Lưu mật khẩu mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection