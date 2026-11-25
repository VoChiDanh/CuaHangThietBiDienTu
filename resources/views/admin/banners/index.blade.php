@extends('layouts.admin')

@section('page_title', 'Quản lý Banner & Tiếp thị')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Banner Tiếp thị & Quảng cáo</h2>
            <p class="text-xs text-slate-500 mt-0.5">Quản lý các hình ảnh trình chiếu nổi bật và banner chiến dịch trên trang chủ</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors inline-flex items-center space-x-1.5 shadow-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Thêm Banner mới</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Hình ảnh</th>
                        <th class="py-3 px-4">Tiêu đề Banner</th>
                        <th class="py-3 px-4">Liên kết đích (Link URL)</th>
                        <th class="py-3 px-4 text-center">Thứ tự</th>
                        <th class="py-3 px-4 text-center">Trạng thái</th>
                        <th class="py-3 px-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-28 h-12 object-cover border border-slate-200">
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $banner->title }}
                            </td>
                            <td class="py-3 px-4 text-indigo-600 font-mono text-[11px] truncate max-w-xs">
                                <a href="{{ $banner->link_url ?: '#' }}" target="_blank" class="hover:underline">{{ $banner->link_url ?: 'Không có link' }}</a>
                            </td>
                            <td class="py-3 px-4 text-center font-bold font-mono text-slate-700">
                                {{ $banner->sort_order }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-block px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider border {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                        {{ $banner->is_active ? 'Đang bật' : 'Tắt' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex px-2.5 py-1 border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition-colors">
                                    Sửa
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex px-2.5 py-1 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 font-semibold text-xs transition-colors">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">Chưa có banner nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($banners->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection