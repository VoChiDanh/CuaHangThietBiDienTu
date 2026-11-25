@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu mới - SmartTech')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <div class="bg-white border border-slate-200 p-8 space-y-6">
        <div class="border-b border-slate-200 pb-4">
            <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-slate-500 block">Bảo mật tài khoản</span>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight mt-1">Thiết lập mật khẩu mới</h2>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Địa chỉ Email</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="w-full px-3 py-2 border border-slate-200 bg-slate-50 text-slate-600 text-xs font-mono cursor-not-allowed">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mật khẩu mới *</label>
                <input type="password" id="password" name="password" required autofocus class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="••••••••">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Xác nhận mật khẩu *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs uppercase tracking-wider transition-colors shadow-none">
                Lưu mật khẩu mới
            </button>
        </form>
    </div>
</div>
@endsection