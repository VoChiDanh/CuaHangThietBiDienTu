@extends('layouts.admin')

@section('title', 'Quản lý thông số kỹ thuật')

@section('content')
<div class="space-y-5">
    <div><a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-blue-600">← Sản phẩm</a><h1 class="mt-1 text-xl font-bold text-slate-900">Danh mục thông số kỹ thuật</h1><p class="mt-1 text-xs text-slate-500">Tạo nhóm theo danh mục và khai báo các trường để nhập thông số trong form sản phẩm.</p></div>

    <div class="grid gap-4 xl:grid-cols-2">
        <form method="POST" action="{{ route('admin.specification-groups.store') }}" class="space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf<h2 class="font-bold text-slate-900">Thêm nhóm thông số</h2>
            <label class="block text-xs font-semibold">Danh mục<select name="category_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"><option value="">Chọn danh mục</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></label>
            <label class="block text-xs font-semibold">Tên nhóm<input name="name" required placeholder="Ví dụ: Màn hình, Bộ xử lý..." class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <label class="block text-xs font-semibold">Thứ tự<input type="number" name="sort_order" min="0" value="0" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white">Thêm nhóm</button>
        </form>

        <form method="POST" action="{{ route('admin.specifications.store') }}" class="space-y-3 rounded-xl border border-slate-200 bg-white p-4">
            @csrf<h2 class="font-bold text-slate-900">Thêm trường thông số</h2>
            <label class="block text-xs font-semibold">Nhóm<select name="group_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"><option value="">Chọn nhóm</option>@foreach($groups as $group)<option value="{{ $group->id }}">{{ $group->category->name }} · {{ $group->name }}</option>@endforeach</select></label>
            <div class="grid gap-3 sm:grid-cols-2"><label class="text-xs font-semibold">Tên thông số<input name="name" required placeholder="Ví dụ: Độ phân giải" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label><label class="text-xs font-semibold">Đơn vị<input name="unit" placeholder="GHz, inch, kg..." class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label></div>
            <div class="grid gap-3 sm:grid-cols-2"><label class="text-xs font-semibold">Cách so sánh<select name="compare_rule" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"><option value="none">Không so sánh</option><option value="higher_is_better">Giá trị cao hơn tốt hơn</option><option value="lower_is_better">Giá trị thấp hơn tốt hơn</option></select></label><label class="text-xs font-semibold">Thứ tự<input type="number" name="sort_order" min="0" value="0" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label></div>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white">Thêm thông số</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($groups as $group)
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50 p-4">
                    <form method="POST" action="{{ route('admin.specification-groups.update', $group) }}" class="flex flex-1 flex-wrap items-end gap-2">
                        @csrf @method('PUT')
                        <label class="min-w-48 flex-1 text-[11px] font-semibold">Nhóm thông số<input name="name" value="{{ $group->name }}" required class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal"></label>
                        <label class="min-w-48 flex-1 text-[11px] font-semibold">Danh mục<select name="category_id" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal">@foreach($categories as $category)<option value="{{ $category->id }}" @selected($group->category_id === $category->id)>{{ $category->name }}</option>@endforeach</select></label>
                        <label class="w-24 text-[11px] font-semibold">Thứ tự<input type="number" name="sort_order" min="0" value="{{ $group->sort_order }}" required class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-normal"></label>
                        <button class="rounded-lg border border-blue-200 bg-white px-3 py-2 text-xs font-bold text-blue-700">Lưu nhóm</button>
                    </form>
                    <form method="POST" action="{{ route('admin.specification-groups.destroy', $group) }}" onsubmit="return confirm('Xóa nhóm thông số này?')">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700">Xóa nhóm</button></form>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($group->specifications as $specification)
                        <form method="POST" action="{{ route('admin.specifications.update', $specification) }}" class="grid gap-2 p-3 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_1fr_auto_auto] lg:items-end">
                            @csrf @method('PUT')
                            <label class="text-[11px] font-semibold">Tên<input name="name" value="{{ $specification->name }}" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                            <label class="text-[11px] font-semibold">Nhóm<select name="group_id" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal">@foreach($groups as $optionGroup)<option value="{{ $optionGroup->id }}" @selected($specification->group_id === $optionGroup->id)>{{ $optionGroup->category->name }} · {{ $optionGroup->name }}</option>@endforeach</select></label>
                            <label class="text-[11px] font-semibold">Đơn vị<input name="unit" value="{{ $specification->unit }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-normal"></label>
                            <div class="grid grid-cols-2 gap-2"><label class="text-[11px] font-semibold">So sánh<select name="compare_rule" class="mt-1 w-full rounded-lg border border-slate-300 px-2 py-2 text-xs"><option value="none" @selected($specification->compare_rule === 'none')>Không</option><option value="higher_is_better" @selected($specification->compare_rule === 'higher_is_better')>Cao hơn tốt</option><option value="lower_is_better" @selected($specification->compare_rule === 'lower_is_better')>Thấp hơn tốt</option></select></label><label class="text-[11px] font-semibold">Thứ tự<input type="number" name="sort_order" min="0" value="{{ $specification->sort_order }}" class="mt-1 w-full rounded-lg border border-slate-300 px-2 py-2 text-xs"></label></div>
                            <button class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-bold text-blue-700">Lưu</button>
                        </form>
                        <form method="POST" action="{{ route('admin.specifications.destroy', $specification) }}" class="-mt-3 flex justify-end px-3 pb-3" onsubmit="return confirm('Xóa trường thông số này?')">@csrf @method('DELETE')<button class="text-xs font-semibold text-rose-600">Xóa trường</button></form>
                    @empty
                        <p class="p-4 text-xs text-slate-500">Nhóm chưa có trường thông số.</p>
                    @endforelse
                </div>
            </section>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">Chưa có nhóm thông số. Hãy tạo nhóm trước.</div>
        @endforelse
    </div>
</div>
@endsection
