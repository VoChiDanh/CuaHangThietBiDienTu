@extends('layouts.client')

@section('title', 'Quên mật khẩu - SmartTech')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="bg-white border border-slate-200 p-8 space-y-6">
        <div class="border-b border-slate-200 pb-4">
            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-500 block">Khôi phục quyền truy cập</span>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight mt-1">Quên mật khẩu</h2>
            <p class="text-xs text-slate-500 mt-1">Nhập địa chỉ email đăng ký để nhận liên kết đặt lại mật khẩu</p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email tài khoản *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs font-mono text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="name@example.com">
            </div>

            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider transition-colors shadow-none">
                Gửi liên kết đặt lại mật khẩu
            </button>
        </form>

        <div class="pt-4 border-t border-slate-200 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 uppercase tracking-wider">
                ← Quay lại đăng nhập
            </a>
        </div>
    </div>
</div>
@endsection