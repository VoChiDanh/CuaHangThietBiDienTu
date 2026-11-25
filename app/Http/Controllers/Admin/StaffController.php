<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffs = User::whereIn('role', ['admin', 'staff'])
            ->with('permissions')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.staffs.index', compact('staffs', 'permissionsByModule'));
    }

    public function create(): View
    {
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.staffs.create', compact('permissionsByModule'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:staff,admin'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ], [
            'name.required' => 'Vui lòng nhập tên nhân viên.',
            'email.required' => 'Vui lòng nhập email đăng nhập.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu khởi tạo.',
            'password.min' => 'Mật khẩu phải từ 8 ký tự trở lên.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        if ($user->isStaff() && ! empty($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.staffs.index')
            ->with('success', "Thêm mới nhân sự '{$user->name}' và phân quyền thành công!");
    }

    public function edit(User $staff): View
    {
        $permissionsByModule = Permission::all()->groupBy('module');
        $assignedPermissionIds = $staff->permissions->pluck('id')->toArray();

        return view('admin.staffs.edit', compact('staff', 'permissionsByModule', 'assignedPermissionIds'));
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$staff->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:staff,admin'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'is_active' => (bool) $validated['is_active'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        if ($staff->isStaff()) {
            $staff->permissions()->sync($validated['permissions'] ?? []);
        }

        return redirect()->route('admin.staffs.index')
            ->with('success', "Cập nhật phân quyền ma trận cho nhân sự '{$staff->name}' thành công!");
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        $name = $staff->name;
        $staff->delete();

        return redirect()->route('admin.staffs.index')
            ->with('success', "Đã xóa tài khoản nhân sự '{$name}' khỏi hệ thống.");
    }
}
