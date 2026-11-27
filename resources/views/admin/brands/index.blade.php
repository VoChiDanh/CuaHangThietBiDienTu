@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="space-y-5">
    <div><h1 class="text-xl font-bold text-slate-900">Thương hiệu</h1><p class="mt-1 text-xs text-slate-500">Thêm, sửa, ẩn/hiện thương hiệu dùng trong danh mục sản phẩm.</p></div>
    <form method="POST" action="{{ route('admin.brands.store') }}" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 md:grid-cols-4">
        @csrf
        <label class="text-xs font-semibold">Tên thương hiệu<input name="name" required value="{{ old('name') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
        <label class="text-xs font-semibold">Slug (tùy chọn)<input name="slug" value="{{ old('slug') }}" placeholder="Tạo tự động nếu để trống" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
        <label class="text-xs font-semibold">Đường dẫn / URL logo<input type="text" name="logo_url" value="{{ old('logo_url') }}" placeholder="/uploads/brands/logo.png hoặc https://..." class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
        <div class="flex items-end justify-between gap-3"><label class="flex items-center gap-2 pb-2 text-xs"><input type="checkbox" name="is_visible" value="1" checked class="rounded border-slate-300"> Đang hiển thị</label><button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white">Thêm thương hiệu</button></div>
    </form>

    <div class="space-y-3">
        @forelse($brands as $brand)
            <div class="grid gap-4 rounded-xl border border-slate-200 bg-white p-4 lg:grid-cols-[1fr_auto]">
                <form method="POST" action="{{ route('admin.brands.update', $brand) }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @csrf @method('PUT')
                    <label class="text-xs font-semibold">Tên thương hiệu<input name="name" required value="{{ $brand->name }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                    <label class="text-xs font-semibold">Slug<input name="slug" required value="{{ $brand->slug }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                    <label class="text-xs font-semibold">Đường dẫn / URL logo<input type="text" name="logo_url" value="{{ $brand->logo_url }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                    <div class="flex items-end justify-between gap-2"><label class="flex items-center gap-2 pb-2 text-xs"><input type="checkbox" name="is_visible" value="1" @checked($brand->is_visible) class="rounded border-slate-300"> Hiển thị</label><button class="mb-1 rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-bold text-blue-700">Lưu</button></div>
                </form>
                <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-3 lg:border-0 lg:pt-0"><span class="text-xs text-slate-500">{{ $brand->products_count }} sản phẩm</span><form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Xóa thương hiệu này?')">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Xóa</button></form></div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">Chưa có thương hiệu.</div>
        @endforelse
    </div>
    {{ $brands->links() }}
</div>
@endsection
