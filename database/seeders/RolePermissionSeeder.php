<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Khởi tạo danh sách quyền hạn chi tiết (Permissions)
        $permissions = [
            // Quản lý tổng quan & Báo cáo
            ['name' => 'view_dashboard', 'group_name' => 'Báo cáo', 'description' => 'Xem bảng điều khiển và biểu đồ thống kê doanh số'],
            ['name' => 'export_reports', 'group_name' => 'Báo cáo', 'description' => 'Xuất báo cáo doanh thu ra file Excel'],

            // Quản lý người dùng & Phân quyền
            ['name' => 'manage_users', 'group_name' => 'Tài khoản', 'description' => 'Xem danh sách, khóa hoặc cấp quyền cho tài khoản'],
            ['name' => 'manage_roles', 'group_name' => 'Tài khoản', 'description' => 'Cấu hình ma trận vai trò và quyền hạn'],

            // Quản lý sản phẩm & danh mục
            ['name' => 'manage_products', 'group_name' => 'Sản phẩm', 'description' => 'Thêm, sửa, xóa sản phẩm, biến thể, ảnh và thông số kỹ thuật'],
            ['name' => 'manage_categories', 'group_name' => 'Sản phẩm', 'description' => 'Quản lý danh mục và thương hiệu'],

            // Quản lý đơn hàng & Kho
            ['name' => 'manage_orders', 'group_name' => 'Đơn hàng & Kho', 'description' => 'Xem, duyệt đơn, cập nhật trạng thái đơn và in hóa đơn PDF'],
            ['name' => 'view_inventory', 'group_name' => 'Đơn hàng & Kho', 'description' => 'Xem cảnh báo tồn kho sắp hết hàng'],

            // Khuyến mãi & Tiếp thị
            ['name' => 'manage_coupons', 'group_name' => 'Tiếp thị', 'description' => 'Tạo, sửa và quản lý mã giảm giá voucher'],
            ['name' => 'manage_banners', 'group_name' => 'Tiếp thị', 'description' => 'Quản lý slider và banner quảng cáo trên trang chủ'],

            // Khách hàng & Đánh giá
            ['name' => 'manage_reviews', 'group_name' => 'Đánh giá', 'description' => 'Kiểm duyệt, ẩn/hiện và trả lời đánh giá của khách'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        // 2. Khởi tạo các vai trò (Roles)
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Quản trị viên tối cao (Admin)']
        );

        $salesRole = Role::updateOrCreate(
            ['name' => 'sales_staff'],
            ['display_name' => 'Nhân viên bán hàng (Sales)']
        );

        $warehouseRole = Role::updateOrCreate(
            ['name' => 'warehouse_staff'],
            ['display_name' => 'Nhân viên quản lý kho (Warehouse)']
        );

        // 3. Gán quyền cho từng vai trò
        // Admin: có toàn bộ quyền
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Nhân viên bán hàng: Dashboard, Đơn hàng, Voucher, Đánh giá, Banners
        $salesPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'manage_orders',
            'manage_coupons',
            'manage_reviews',
            'manage_banners',
        ])->pluck('id');
        $salesRole->permissions()->sync($salesPermissions);

        // Nhân viên kho: Dashboard, Xem đơn, quản lý sản phẩm, tồn kho
        $warehousePermissions = Permission::whereIn('name', [
            'view_dashboard',
            'manage_orders',
            'manage_products',
            'view_inventory',
        ])->pluck('id');
        $warehouseRole->permissions()->sync($warehousePermissions);
    }
}
