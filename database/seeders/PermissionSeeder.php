<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'Xem danh sách sản phẩm',
                'slug' => 'product.view',
                'module' => 'Sản phẩm',
                'description' => 'Cho phép xem danh mục, thương hiệu và sản phẩm trong trang quản trị',
            ],
            [
                'name' => 'Quản lý sản phẩm & biến thể',
                'slug' => 'product.manage',
                'module' => 'Sản phẩm',
                'description' => 'Cho phép thêm mới, chỉnh sửa và ẩn/hiện sản phẩm, biến thể, hình ảnh',
            ],
            [
                'name' => 'Xem danh sách đơn hàng',
                'slug' => 'order.view',
                'module' => 'Đơn hàng & Kho',
                'description' => 'Cho phép xem chi tiết và danh sách đơn hàng',
            ],
            [
                'name' => 'Xử lý đơn hàng',
                'slug' => 'order.manage',
                'module' => 'Đơn hàng & Kho',
                'description' => 'Cho phép duyệt đơn, đổi trạng thái giao hàng, in hóa đơn PDF',
            ],
            [
                'name' => 'Quản lý kho hàng',
                'slug' => 'inventory.manage',
                'module' => 'Đơn hàng & Kho',
                'description' => 'Cho phép cập nhật số lượng tồn kho và xem cảnh báo sắp hết hàng',
            ],
            [
                'name' => 'Quản lý Mã giảm giá (Voucher)',
                'slug' => 'voucher.manage',
                'module' => 'Khuyến mãi',
                'description' => 'Cho phép tạo mới, sửa và quản lý điều kiện áp dụng mã giảm giá',
            ],
            [
                'name' => 'Kiểm duyệt đánh giá (Review)',
                'slug' => 'review.manage',
                'module' => 'Đánh giá',
                'description' => 'Cho phép ẩn/hiện đánh giá của khách hàng và gửi phản hồi từ shop',
            ],
            [
                'name' => 'Quản lý Banner quảng cáo',
                'slug' => 'banner.manage',
                'module' => 'Tiếp thị',
                'description' => 'Cho phép tải lên và sắp xếp slider/banner trên trang chủ',
            ],
            [
                'name' => 'Xem báo cáo & Xuất Excel',
                'slug' => 'report.view',
                'module' => 'Báo cáo',
                'description' => 'Cho phép xem số liệu doanh thu trên Dashboard và xuất file Excel',
            ],
            [
                'name' => 'Quản lý Nhân sự & Phân quyền',
                'slug' => 'staff.manage',
                'module' => 'Hệ thống',
                'description' => 'Cho phép tạo tài khoản nhân viên và tick phân quyền chi tiết',
            ],
        ];

        foreach ($permissions as $p) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $p['slug']],
                array_merge($p, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Cấp quyền ban đầu cho các nhân viên mẫu theo nghiệp vụ
        // User 2: Quản lý Kho HCM (order.view, order.manage, inventory.manage, product.view)
        $permMap = DB::table('permissions')->pluck('id', 'slug');

        $staffPermissions = [
            2 => ['order.view', 'order.manage', 'inventory.manage', 'product.view'],
            3 => ['order.view', 'order.manage', 'inventory.manage', 'product.view'],
            4 => ['review.manage', 'order.view', 'voucher.manage', 'banner.manage'], // CSKH & Kiểm duyệt
        ];

        foreach ($staffPermissions as $userId => $slugs) {
            foreach ($slugs as $slug) {
                if (isset($permMap[$slug])) {
                    DB::table('staff_permissions')->updateOrInsert([
                        'user_id' => $userId,
                        'permission_id' => $permMap[$slug],
                    ]);
                }
            }
        }
    }
}
