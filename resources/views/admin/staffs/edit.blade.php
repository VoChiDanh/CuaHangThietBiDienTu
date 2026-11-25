@extends('layouts.admin')

@section('page_title', 'Cập nhật Quyền hạn Nhân viên')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Điều chỉnh phân quyền: <span class="text-indigo-600">{{ $staff->name }}</span></h2>
            <p class="text-xs text-slate-500 mt-0.5">Cập nhật vai trò hệ thống, trạng thái và ma trận cấp quyền theo từng module</p>
        </div>
        <a href="{{ route('admin.staffs.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.staffs.update', $staff) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-200 pb-3">Thông tin tài khoản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Họ và tên *</label>
                    <input type="text" name="name" value="{{ old('name', $staff->name) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email đăng nhập *</label>
                    <input type="email" name="email" value="{{ old('email', $staff->email) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mật khẩu mới (để trống nếu giữ nguyên)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Vai trò hệ thống *</label>
                    <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                        <option value="staff" {{ old('role', $staff->role) === 'staff' ? 'selected' : '' }}>Nhân viên quản lý (Staff)</option>
                        <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>Quản trị viên (Super Admin)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Trạng thái hoạt động *</label>
                    <select name="is_active" class="w-full px-3 py-2 border border-slate-300 rounded-none text-xs text-slate-900 focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                        <option value="1" {{ old('is_active', $staff->is_active) ? 'selected' : '' }}>Hoạt động bình thường</option>
                        <option value="0" {{ !old('is_active', $staff->is_active) ? 'selected' : '' }}>Tạm khóa tài khoản</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Permissions Matrix Card -->
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Ma trận phân quyền (Permissions Matrix)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tích chọn các quyền mà nhân viên được phép truy cập và thực thi</p>
                </div>
                <button type="button" id="toggleAllBtn" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider">
                    Chọn tất cả
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($permissionsByModule as $moduleName => $perms)
                    <div class="p-4 bg-slate-50 border border-slate-200">
                        <div class="flex items-center space-x-2 mb-3 pb-2 border-b border-slate-200">
                            <span class="w-1.5 h-3 bg-slate-900 inline-block"></span>
                            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-900">Phân hệ: {{ $moduleName }}</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach($perms as $perm)
                                <label class="flex items-start space-x-2.5 cursor-pointer group">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" {{ in_array($perm->id, $assignedPermissionIds) ? 'checked' : '' }} class="perm-checkbox w-4 h-4 text-slate-900 rounded-none border-slate-300 focus:ring-0 focus:ring-offset-0 mt-0.5">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $perm->name }}</p>
                                        <p class="text-[11px] text-slate-500 leading-tight font-mono">{{ $perm->code }} - {{ $perm->description }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.staffs.index') }}" class="px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                Hủy bỏ
            </a>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider transition-colors shadow-none">
                Lưu phân quyền
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