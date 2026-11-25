@extends('layouts.admin')

@section('page_title', 'Chỉnh sửa Banner Quảng cáo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Cập nhật Banner: <span class="text-indigo-600">{{ $banner->title }}</span></h2>
            <p class="text-xs text-slate-500 mt-0.5">Sửa đổi hình ảnh chiến dịch và đường dẫn điều hướng</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tiêu đề Banner *</label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Đường dẫn hình ảnh (Image URL) *</label>
            <input type="url" name="image_url" value="{{ old('image_url', $banner->image_url) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            @if($banner->image_url)
                <div class="mt-3">
                    <img src="{{ $banner->image_url }}" alt="Preview" class="h-28 object-cover border border-slate-200">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Đường dẫn điều hướng (Link URL)</label>
            <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Thứ tự hiển thị *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Trạng thái phát hành *</label>
                <select name="is_active" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    <option value="1" {{ old('is_active', $banner->is_active) ? 'selected' : '' }}>Hiển thị công khai</option>
                    <option value="0" {{ !old('is_active', $banner->is_active) ? 'selected' : '' }}>Tạm ẩn</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-5 border-t border-slate-200">
            <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                Hủy bỏ
            </a>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-none">
                Lưu cập nhật
            </button>
        </div>
    </form>
</div>
@endsection