@extends('layouts.admin')

@php
    $isEditing = $product->exists;
    $savedSpecifications = $product->productSpecifications->keyBy('specification_id');
    $displayVariants = old('variants') !== null
        ? collect(old('variants'))->map(function ($data) {
            $variant = new \App\Models\ProductVariant($data);
            $variant->id = $data['id'] ?? null;

            return $variant;
        })
        : $formVariants;
@endphp

@section('title', $isEditing ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('admin.products.update', $product) : route('admin.products.store') }}" class="space-y-5" id="product-form">
    @csrf
    @if($isEditing) @method('PUT') @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div><a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-blue-600">← Danh sách sản phẩm</a><h1 class="mt-1 text-xl font-bold text-slate-900">{{ $isEditing ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm mới' }}</h1></div>
        <div class="flex gap-2"><a href="{{ route('admin.specifications.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold">Cấu hình danh sách thông số</a><button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white">Lưu sản phẩm</button></div>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6">
        <h2 class="mb-4 text-base font-bold text-slate-900">Thông tin cơ bản</h2>
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-xs font-semibold text-slate-700 md:col-span-2">Tên sản phẩm <input name="name" required value="{{ old('name', $product->name) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Slug <input name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Để trống để tạo tự động" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">SKU sản phẩm <input name="sku_master" required value="{{ old('sku_master', $product->sku_master) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Danh mục <select name="category_id" id="category-select" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"><option value="">Chọn danh mục</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></label>
            <label class="text-xs font-semibold text-slate-700">Thương hiệu <select name="brand_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"><option value="">Chọn thương hiệu</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></label>
            <label class="text-xs font-semibold text-slate-700">Giá niêm yết (₫) <input type="number" id="base-price-input" name="base_price" required min="0" step="1" value="{{ old('base_price', $product->base_price) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Giá khuyến mãi (₫) <input type="number" name="sale_price" min="0" step="1" value="{{ old('sale_price', $product->sale_price) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Bảo hành (tháng) <input type="number" name="warranty_months" required min="0" value="{{ old('warranty_months', $product->warranty_months ?? 12) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Khối lượng (gram) <input type="number" name="weight_grams" min="0" value="{{ old('weight_grams', $product->weight_grams) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Dung lượng pin (mAh) <input type="number" name="battery_mah" min="0" value="{{ old('battery_mah', $product->battery_mah) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Dung lượng pin (Wh) <input type="number" name="battery_wh" min="0" step="0.01" value="{{ old('battery_wh', $product->battery_wh) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Kích thước màn hình (inch) <input type="number" name="screen_size" min="0" step="0.01" value="{{ old('screen_size', $product->screen_size) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Tổng đã bán <input type="number" name="total_sold" min="0" required value="{{ old('total_sold', $product->total_sold ?? 0) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="flex items-center gap-2 self-end pb-2 text-sm font-semibold text-slate-700"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded border-slate-300 text-blue-600"> Đang hiển thị / kinh doanh</label>
            <label class="text-xs font-semibold text-slate-700 md:col-span-2">Mô tả <textarea name="description" rows="5" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal">{{ old('description', $product->description) }}</textarea></label>
            <label class="text-xs font-semibold text-slate-700 md:col-span-2">Tóm tắt AI (JSON) <textarea name="ai_summary_json" rows="4" placeholder='{"pros":[],"cons":[],"target_users":""}' class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-xs font-normal">{{ old('ai_summary_json', $product->ai_summary ? json_encode($product->ai_summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea><span class="mt-1 block font-normal text-slate-500">Để trống nếu chưa có. Nội dung phải là JSON hợp lệ.</span></label>
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6">
        <div class="mb-4 flex items-center justify-between gap-3"><div><h2 class="text-base font-bold text-slate-900">Biến thể, giá và tồn kho</h2><p class="mt-1 text-xs text-slate-500">Mỗi biến thể có SKU, màu/cấu hình, giá bán, tồn kho và trạng thái riêng.</p></div><button type="button" id="add-variant" class="shrink-0 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700">+ Thêm biến thể</button></div>
        <div id="variant-list" class="space-y-4">
            @foreach($displayVariants as $index => $variant)
                @php $variantImages = $variant->exists ? ($existingImages->get($variant->id, collect())->keyBy('angle_type')) : collect(); @endphp
                <div class="variant-card rounded-xl border border-slate-200 bg-slate-50 p-4" data-variant-index="{{ $index }}">
                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                    <div class="mb-3 flex items-center justify-between"><h3 class="variant-title text-sm font-bold text-slate-800">Biến thể {{ $loop->iteration }}</h3><button type="button" class="remove-variant text-xs font-semibold text-rose-600">Xóa biến thể</button></div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <label class="text-xs font-semibold text-slate-700">SKU <input name="variants[{{ $index }}][sku]" required value="{{ old("variants.{$index}.sku", $variant->sku) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-xs font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Tên màu <input name="variants[{{ $index }}][color_name]" value="{{ old("variants.{$index}.color_name", $variant->color_name) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Mã màu (#RRGGBB) <input name="variants[{{ $index }}][color_hex]" value="{{ old("variants.{$index}.color_hex", $variant->color_hex) }}" pattern="#[0-9A-Fa-f]{6}" placeholder="#FFFFFF" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">RAM <input name="variants[{{ $index }}][ram]" value="{{ old("variants.{$index}.ram", $variant->ram) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Dung lượng lưu trữ <input name="variants[{{ $index }}][storage]" value="{{ old("variants.{$index}.storage", $variant->storage) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Giá bán (₫) <input type="number" name="variants[{{ $index }}][price]" min="0" step="1" required value="{{ old("variants.{$index}.price", $variant->price ?: $product->base_price) }}" data-auto-fill="{{ $variant->exists || old("variants.{$index}.price") ? 'false' : 'true' }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Giá khuyến mãi (₫) <input type="number" name="variants[{{ $index }}][sale_price]" min="0" step="1" value="{{ old("variants.{$index}.sale_price", $variant->sale_price) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Tồn kho <input type="number" name="variants[{{ $index }}][stock_quantity]" min="0" required value="{{ old("variants.{$index}.stock_quantity", $variant->stock_quantity ?? 0) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="text-xs font-semibold text-slate-700">Ngưỡng cảnh báo tồn <input type="number" name="variants[{{ $index }}][low_stock_threshold]" min="0" required value="{{ old("variants.{$index}.low_stock_threshold", $variant->low_stock_threshold ?? 5) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                        <label class="flex items-center gap-2 self-end pb-2 text-xs font-semibold text-slate-700"><input type="checkbox" name="variants[{{ $index }}][is_active]" value="1" @checked(old("variants.{$index}.is_active", $variant->is_active ?? true)) class="rounded border-slate-300 text-blue-600"> Biến thể đang bán</label>
                    </div>
                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                        @foreach(['main' => 'Ảnh mặt trước', 'back' => 'Ảnh mặt sau', 'ports' => 'Ảnh cổng kết nối / cạnh'] as $angle => $label)
                            <label class="rounded-lg border border-dashed border-slate-300 bg-white p-3 text-xs font-semibold text-slate-700">{{ $label }} <input type="file" name="variants[{{ $index }}][images][{{ $angle }}]" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-xs font-normal"><span class="mt-1 block font-normal text-slate-500">JPG, PNG, WebP; tối đa 5 MB.</span>
                                @if($variantImages->has($angle))<img src="{{ $variantImages[$angle]->image_url }}" alt="{{ $label }}" class="mt-3 h-24 w-full rounded-md bg-slate-50 object-contain"><span class="mt-2 flex items-center gap-1.5 font-normal text-rose-700"><input type="checkbox" name="variants[{{ $index }}][remove_images][{{ $angle }}]" value="1" class="rounded border-slate-300"> Xóa ảnh hiện tại nếu không tải ảnh thay thế</span>@endif
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6">
        <div class="mb-4 flex items-center justify-between gap-3"><div><h2 class="text-base font-bold text-slate-900">Thông số kỹ thuật</h2><p class="mt-1 text-xs text-slate-500">Các trường thay đổi theo danh mục. Có thể thêm tên thông số tại mục Quản lý thông số.</p></div><a href="{{ route('admin.specifications.index') }}" class="text-xs font-semibold text-blue-600">Quản lý trường thông số →</a></div>
        <div id="specification-groups" class="space-y-4">
            @foreach($specificationGroups as $group)
                @if($group->specifications->isNotEmpty())
                    <div class="specification-group rounded-lg border border-slate-200 p-4" data-category-id="{{ $group->category_id }}">
                        <h3 class="mb-3 text-sm font-bold text-slate-800">{{ $group->name }} <span class="font-normal text-slate-400">· {{ $group->category->name }}</span></h3>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($group->specifications as $specification)
                                @php $savedValue = $savedSpecifications->get($specification->id)?->value_text; @endphp
                                <label class="text-xs font-semibold text-slate-700">{{ $specification->name }} @if($specification->unit)<span class="font-normal text-slate-500">({{ $specification->unit }})</span>@endif
                                    <input name="specifications[{{ $specification->id }}]" value="{{ old("specifications.{$specification->id}", $savedValue) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal">
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <div class="flex justify-end gap-2"><a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold">Hủy</a><button class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white">Lưu sản phẩm</button></div>
</form>

<template id="variant-template">
    <div class="variant-card rounded-xl border border-slate-200 bg-slate-50 p-4" data-variant-index="__INDEX__">
        <input type="hidden" name="variants[__INDEX__][id]" value="">
        <div class="mb-3 flex items-center justify-between"><h3 class="variant-title text-sm font-bold text-slate-800">Biến thể</h3><button type="button" class="remove-variant text-xs font-semibold text-rose-600">Xóa biến thể</button></div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <label class="text-xs font-semibold text-slate-700">SKU <input name="variants[__INDEX__][sku]" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-xs font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Tên màu <input name="variants[__INDEX__][color_name]" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Mã màu (#RRGGBB) <input name="variants[__INDEX__][color_hex]" pattern="#[0-9A-Fa-f]{6}" placeholder="#FFFFFF" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">RAM <input name="variants[__INDEX__][ram]" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Dung lượng lưu trữ <input name="variants[__INDEX__][storage]" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Giá bán (₫) <input type="number" name="variants[__INDEX__][price]" min="0" step="1" required data-auto-fill="true" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Giá khuyến mãi (₫) <input type="number" name="variants[__INDEX__][sale_price]" min="0" step="1" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Tồn kho <input type="number" name="variants[__INDEX__][stock_quantity]" min="0" value="0" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="text-xs font-semibold text-slate-700">Ngưỡng cảnh báo tồn <input type="number" name="variants[__INDEX__][low_stock_threshold]" min="0" value="5" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="flex items-center gap-2 self-end pb-2 text-xs font-semibold text-slate-700"><input type="checkbox" name="variants[__INDEX__][is_active]" value="1" checked class="rounded border-slate-300 text-blue-600"> Biến thể đang bán</label>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            <label class="rounded-lg border border-dashed border-slate-300 bg-white p-3 text-xs font-semibold text-slate-700">Ảnh mặt trước <input type="file" name="variants[__INDEX__][images][main]" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-xs font-normal"><span class="mt-1 block font-normal text-slate-500">Tối đa 5 MB.</span></label>
            <label class="rounded-lg border border-dashed border-slate-300 bg-white p-3 text-xs font-semibold text-slate-700">Ảnh mặt sau <input type="file" name="variants[__INDEX__][images][back]" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-xs font-normal"><span class="mt-1 block font-normal text-slate-500">Tối đa 5 MB.</span></label>
            <label class="rounded-lg border border-dashed border-slate-300 bg-white p-3 text-xs font-semibold text-slate-700">Ảnh cổng kết nối / cạnh <input type="file" name="variants[__INDEX__][images][ports]" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-xs font-normal"><span class="mt-1 block font-normal text-slate-500">Tối đa 5 MB.</span></label>
        </div>
    </div>
</template>

<script>
    (() => {
        const list = document.getElementById('variant-list');
        const template = document.getElementById('variant-template');
        let nextIndex = Math.max(-1, ...Array.from(list.querySelectorAll('[data-variant-index]')).map((row) => Number(row.dataset.variantIndex))) + 1;

        const updateTitles = () => list.querySelectorAll('.variant-card').forEach((card, index) => {
            card.querySelector('.variant-title').textContent = `Biến thể ${index + 1}`;
        });

        const basePriceInput = document.getElementById('base-price-input');
        const syncDefaultPrice = (card) => {
            const priceInput = card.querySelector('[name$="[price]"]');
            if (priceInput.dataset.autoFill === 'true') priceInput.value = basePriceInput.value;
        };
        list.querySelectorAll('.variant-card').forEach(syncDefaultPrice);
        basePriceInput.addEventListener('input', () => list.querySelectorAll('.variant-card').forEach(syncDefaultPrice));
        list.addEventListener('input', (event) => {
            if (event.target.matches('[name$="[price]"]')) event.target.dataset.autoFill = 'false';
        });

        document.getElementById('add-variant').addEventListener('click', () => {
            const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
            list.insertAdjacentHTML('beforeend', html);
            updateTitles();
            syncDefaultPrice(list.lastElementChild);
        });

        list.addEventListener('click', (event) => {
            if (!event.target.closest('.remove-variant')) return;
            if (list.querySelectorAll('.variant-card').length <= 1) {
                alert('Sản phẩm cần có ít nhất một biến thể.');
                return;
            }
            event.target.closest('.variant-card').remove();
            updateTitles();
        });

        const categorySelect = document.getElementById('category-select');
        const showSpecifications = () => document.querySelectorAll('.specification-group').forEach((group) => {
            group.hidden = group.dataset.categoryId !== categorySelect.value;
        });
        categorySelect.addEventListener('change', showSpecifications);
        showSpecifications();
    })();
</script>
@endsection
