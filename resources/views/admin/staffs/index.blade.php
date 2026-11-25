@extends('layouts.admin')

@section('page_title', 'Quản lý Nhân sự & Phân quyền Động')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 border border-slate-200">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Nhân sự & Ma trận Quyền hạn (Dynamic RBAC)</h2>
            <p class="text-xs text-slate-500 mt-0.5">Admin cấp quyền chi tiết cho nhân viên theo từng module nghiệp vụ</p>
        </div>
        <a href="{{ route('admin.staffs.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-colors flex items-center gap-1.5 border border-slate-900">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Thêm nhân viên mới</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-4">Nhân sự</th>
                        <th class="py-2.5 px-4">Email / SĐT</th>
                        <th class="py-2.5 px-4">Vai trò</th>
                        <th class="py-2.5 px-4">Quyền hạn đã cấp (Permissions Matrix)</th>
                        <th class="py-2.5 px-4 text-center">Trạng thái</th>
                        <th class="py-2.5 px-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($staffs as $staff)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                <div>{{ $staff->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">ID: {{ $staff->id }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div class="font-mono">{{ $staff->email }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $staff->phone ?: 'Chưa cập nhật' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($staff->isAdmin())
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">
                                        Super Admin
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                        Nhân viên
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                @if($staff->isAdmin())
                                    <span class="text-xs font-semibold text-emerald-700 flex items-center gap-1">
                                        <span>✓ Toàn quyền quản trị hệ thống (Full Access)</span>
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($staff->permissions as $perm)
                                            <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] border border-slate-200">
                                                {{ $perm->name }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic text-[11px]">Chưa cấp quyền nào</span>
                                        @endforelse
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold border {{ $staff->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-red-50 text-red-700 border-red-300' }}">
                                    {{ $staff->is_active ? 'Hoạt động' : 'Đã khóa' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.staffs.edit', $staff) }}" class="inline-flex px-2 py-1 border border-slate-200 hover:border-blue-600 text-slate-700 hover:text-blue-600 text-xs font-medium">
                                    Phân quyền
                                </a>
                                @if($staff->id !== auth()->id())
                                    <form action="{{ route('admin.staffs.destroy', $staff) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa nhân sự này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex px-2 py-1 border border-red-200 hover:bg-red-50 text-red-600 text-xs font-medium">
                                            Xóa
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Không có nhân sự nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
