<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DynamicRbacTest extends TestCase
{
    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::where('role', 'customer')->first();
        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Bảng điều khiển');
    }

    public function test_admin_can_view_staff_management(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/staffs');
        $response->assertStatus(200);
        $response->assertSee('Quản lý Nhân sự & Phân quyền Động');
    }

    public function test_staff_cannot_access_staff_management(): void
    {
        $staff = User::where('role', 'staff')->first();
        $response = $this->actingAs($staff)->get('/admin/staffs');
        $response->assertStatus(403);
    }
}
