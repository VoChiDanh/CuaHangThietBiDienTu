@extends('layouts.client')

@section('title', 'Sổ địa chỉ nhận hàng - SmartTech')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Sổ địa chỉ giao nhận</h1>
            <p class="text-xs text-slate-500 mt-0.5">Quản lý các địa chỉ nhận hàng cá nhân để thanh toán nhanh hơn</p>
        </div>
        <a href="{{ route('addresses.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors inline-flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Thêm địa chỉ</span>
        </a>
    </div>

    <!-- Address Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($addresses as $addr)
            <div class="bg-white border {{ $addr->is_default ? 'border-slate-900 ring-1 ring-slate-900' : 'border-slate-200 hover:border-slate-300' }} p-5 flex flex-col justify-between transition-colors">
                <div>
                    <div class="flex justify-between items-start mb-3 border-b border-slate-100 pb-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-900 text-sm">{{ $addr->receiver_name }}</span>
                            @if($addr->is_default)
                                <span class="px-1.5 py-0.5 bg-slate-900 text-white font-mono text-[9px] font-bold uppercase tracking-wider">
                                    Mặc định
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-600 mb-4 font-mono">
                        <p class="flex items-center space-x-2">
                            <span class="text-slate-400 font-sans">Điện thoại:</span>
                            <span class="text-slate-900 font-semibold">{{ $addr->receiver_phone }}</span>
                        </p>
                        <p class="flex items-start space-x-2 font-sans">
                            <span class="text-slate-400">Địa chỉ:</span>
                            <span class="text-slate-800">{{ $addr->full_address }}</span>
                        </p>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-3 flex items-center justify-between text-xs">
                    <div>
                        @if(!$addr->is_default)
                            <form action="{{ route('addresses.default', $addr) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs uppercase tracking-wider">
                                    Đặt làm mặc định
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('addresses.edit', $addr) }}" class="text-slate-600 hover:text-slate-900 font-semibold text-xs uppercase tracking-wider">
                            Sửa
                        </a>
                        <form action="{{ route('addresses.destroy', $addr) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs uppercase tracking-wider">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 py-12 text-center bg-white border border-dashed border-slate-300 p-8">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Chưa có địa chỉ nhận hàng nào</p>
                <p class="text-xs text-slate-500 mt-1 mb-4">Hãy thêm địa chỉ giao hàng để tiện cho việc mua sắm sau này.</p>
                <a href="{{ route('addresses.create') }}" class="inline-flex px-4 py-2 bg-slate-900 text-white text-xs font-semibold uppercase tracking-wider hover:bg-slate-800 transition-colors">
                    Thêm địa chỉ ngay
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection