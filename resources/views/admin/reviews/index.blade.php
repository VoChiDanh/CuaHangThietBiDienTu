@extends('layouts.admin')

@section('page_title', 'Kiểm duyệt Đánh giá Sản phẩm (Reviews & Ratings)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Kiểm duyệt Nhận xét & Đánh giá</h2>
            <p class="text-xs text-slate-500 mt-0.5">Xử lý đánh giá từ khách hàng đã mua, kiểm soát spam và phản hồi chính thức từ cửa hàng</p>
        </div>
    </div>

    <!-- Top Filter & Stats (Clean rectangular metric tiles) -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.reviews.index') }}" class="p-4 bg-white border {{ empty($status) ? 'border-slate-900 bg-slate-50/50 ring-1 ring-slate-900' : 'border-slate-200 hover:border-slate-300' }} transition-colors">
            <span class="text-[11px] uppercase tracking-wider text-slate-500 font-bold block">Tất cả đánh giá</span>
            <span class="text-2xl font-black text-slate-900 font-mono mt-1 block">{{ $stats['total'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="p-4 bg-white border {{ $status === 'approved' ? 'border-emerald-600 bg-emerald-50/30 ring-1 ring-emerald-600' : 'border-slate-200 hover:border-slate-300' }} transition-colors">
            <span class="text-[11px] uppercase tracking-wider text-emerald-700 font-bold block">Đã duyệt (Approved)</span>
            <span class="text-2xl font-black text-emerald-700 font-mono mt-1 block">{{ $stats['approved'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="p-4 bg-white border {{ $status === 'pending' ? 'border-amber-600 bg-amber-50/30 ring-1 ring-amber-600' : 'border-slate-200 hover:border-slate-300' }} transition-colors">
            <span class="text-[11px] uppercase tracking-wider text-amber-700 font-bold block">Chờ kiểm duyệt</span>
            <span class="text-2xl font-black text-amber-700 font-mono mt-1 block">{{ $stats['pending'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'hidden']) }}" class="p-4 bg-white border {{ $status === 'hidden' ? 'border-rose-600 bg-rose-50/30 ring-1 ring-rose-600' : 'border-slate-200 hover:border-slate-300' }} transition-colors">
            <span class="text-[11px] uppercase tracking-wider text-rose-700 font-bold block">Đã ẩn (Spam / Khóa)</span>
            <span class="text-2xl font-black text-rose-700 font-mono mt-1 block">{{ $stats['hidden'] }}</span>
        </a>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Khách hàng</th>
                        <th class="py-3 px-4">Sản phẩm</th>
                        <th class="py-3 px-4">Đánh giá</th>
                        <th class="py-3 px-4 max-w-sm">Nội dung & Phản hồi</th>
                        <th class="py-3 px-4 text-center">Trạng thái</th>
                        <th class="py-3 px-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                <div>{{ $rev->user?->name ?: 'Khách hàng #' . $rev->user_id }}</div>
                                <div class="text-[11px] text-slate-500 font-mono font-normal">{{ $rev->user?->email }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-indigo-600 font-medium max-w-xs truncate">
                                {{ $rev->product?->name ?: 'Sản phẩm #' . $rev->product_id }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-amber-500 font-bold text-xs font-mono">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                                <span class="text-slate-700 ml-1">({{ $rev->rating }}/5)</span>
                            </td>
                            <td class="py-3.5 px-4 max-w-sm">
                                <p class="text-slate-800 leading-relaxed">{{ $rev->comment }}</p>
                                
                                @if($rev->admin_reply)
                                    <div class="mt-2 p-2.5 bg-slate-50 border border-slate-200 text-[11px] text-slate-800">
                                        <strong class="font-semibold text-indigo-700 block mb-0.5">Phản hồi của Shop:</strong>
                                        {{ $rev->admin_reply }}
                                    </div>
                                @endif

                                <!-- Form Reply Shop -->
                                <details class="mt-2 text-[11px]">
                                    <summary class="text-indigo-600 hover:text-indigo-800 cursor-pointer font-semibold uppercase tracking-wider">
                                        {{ $rev->admin_reply ? 'Sửa phản hồi' : '+ Trả lời phản hồi' }}
                                    </summary>
                                    <form action="{{ route('admin.reviews.reply', $rev) }}" method="POST" class="mt-2 space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="admin_reply" rows="2" required class="w-full p-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Viết phản hồi tới khách hàng...">{{ $rev->admin_reply }}</textarea>
                                        <button type="submit" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-semibold uppercase tracking-wider transition-colors">
                                            Gửi phản hồi
                                        </button>
                                    </form>
                                </details>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 text-[11px] font-bold uppercase tracking-wider border rounded-none cursor-pointer {{ $rev->status === 'approved' ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : ($rev->status === 'pending' ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-rose-300 bg-rose-50 text-rose-800') }}">
                                        <option value="approved" {{ $rev->status === 'approved' ? 'selected' : '' }}>Hiển thị</option>
                                        <option value="pending" {{ $rev->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                        <option value="hidden" {{ $rev->status === 'hidden' ? 'selected' : '' }}>Ẩn / Khóa</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
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
                            <td colspan="6" class="py-10 text-center text-slate-400">Không có đánh giá nào phù hợp với bộ lọc hiện tại.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection