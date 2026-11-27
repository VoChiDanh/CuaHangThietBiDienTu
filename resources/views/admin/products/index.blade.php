@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Sản phẩm</h1>
            <p class="mt-1 text-xs text-slate-500">Quản lý thông tin, giá, tồn kho, biến thể và thông số kỹ thuật.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.specifications.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Quản lý thông số</a>
            <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">+ Thêm sản phẩm</a>
        </div>
    </div>

    <form method="GET" class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 sm:flex-row">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc SKU..." class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
        <select name="brand_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <option value="">Tất cả thương hiệu</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
        <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Lọc</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr>
                    <th class="px-4 py-3">Sản phẩm</th><th class="px-4 py-3">SKU</th><th class="px-4 py-3">Danh mục / Thương hiệu</th><th class="px-4 py-3">Giá</th><th class="px-4 py-3">Biến thể</th><th class="px-4 py-3">Trạng thái</th><th class="px-4 py-3 text-right">Thao tác</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="align-middle hover:bg-slate-50">
                            <td class="px-4 py-3"><div class="font-semibold text-slate-900">{{ $product->name }}</div><div class="mt-0.5 text-xs text-slate-500">Đã bán {{ number_format($product->total_sold) }}</div></td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $product->sku_master }}</td>
                            <td class="px-4 py-3"><div>{{ $product->category?->name }}</div><div class="text-xs text-slate-500">{{ $product->brand?->name }}</div></td>
                            <td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $product->sale_price ?: (float) $product->base_price) }}₫</td>
                            <td class="px-4 py-3">{{ $product->variants_count }}</td>
                            <td class="px-4 py-3"><span class="rounded-full px-2 py-1 text-xs {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $product->is_active ? 'Đang bán' : 'Đang ẩn' }}</span></td>
                            <td class="px-4 py-3"><div class="flex justify-end gap-2"><a href="{{ route('admin.products.edit', $product) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold hover:bg-slate-100">Sửa</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Xóa sản phẩm này?')">@csrf @method('DELETE')<button class="rounded-md border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Xóa</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">Chưa có sản phẩm phù hợp.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 p-3">{{ $products->links() }}</div>
    </div>
</div>
@endsection
