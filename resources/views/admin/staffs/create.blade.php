@extends('layouts.admin')

@section('page_title', 'Thêm mới Nhân viên & Cấp quyền')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between bg-white p-4 border border-slate-200">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Tạo tài khoản nhân viên mới</h2>
            <p class="text-xs text-slate-500">Cấu hình thông tin đăng nhập và chọn ma trận quyền hạn chi tiết</p>
        </div>
        <a href="{{ route('admin.staffs.index') }}" class="text-xs text-blue-600 hover:underline font-semibold">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.staffs.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Account Info -->
        <div class="bg-white p-5 border border-slate-200 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2">Thông tin tài khoản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Họ và tên *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600" placeholder="Nguyễn Văn A">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email đăng nhập *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-slate-300 text-xs font-mono focus:outline-none focus:border-blue-600" placeholder="staff@techstore.vn">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-slate-300 text-xs font-mono focus:outline-none focus:border-blue-600" placeholder="0901 000 005">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu khởi tạo *</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600" placeholder="Tối thiểu 8 ký tự">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Vai trò hệ thống *</label>
                    <select name="role" class="w-full px-3 py-2 border border-slate-300 text-xs focus:outline-none focus:border-blue-600 bg-white">
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Nhân viên quản lý (Staff)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Quản trị viên (Super Admin)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Permissions Matrix Card -->
        <div class="bg-white p-5 border border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Ma trận phân quyền (Permissions Matrix)</h3>
                    <p class="text-[11px] text-slate-400">Chọn các quyền cụ thể nhân viên được phép thao tác</p>
                </div>
                <button type="button" id="toggleAllBtn" class="text-xs font-bold text-blue-600 hover:underline">
                    Chọn tất cả
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($permissionsByModule as $moduleName => $perms)
                    <div class="p-3 border border-slate-200 bg-slate-50/50">
                        <h4 class="font-bold text-xs text-slate-900 mb-2 border-b border-slate-200 pb-1">
                            Phân hệ: {{ $moduleName }}
                        </h4>
                        <div class="space-y-2">
                            @foreach($perms as $perm)
                                <label class="flex items-start gap-2 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox w-3.5 h-3.5 text-blue-600 border-slate-300 mt-0.5">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">{{ $perm->name }}</p>
                                        <p class="text-[10px] text-slate-500 leading-tight">{{ $perm->description }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.staffs.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Hủy bỏ
            </a>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-xs font-semibold transition-colors">
                Lưu và Cấp quyền
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleAllBtn');
        const checkboxes = document.querySelectorAll('.perm-checkbox');
        let allChecked = false;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                allChecked = !allChecked;
                checkboxes.forEach(cb => cb.checked = allChecked);
                toggleBtn.textContent = allChecked ? 'Bỏ chọn tất cả' : 'Chọn tất cả';
            });
        }
    });
</script>
@endsection
