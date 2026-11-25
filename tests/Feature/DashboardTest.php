<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_admin_dashboard_export_excel_success(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition') ?? '');
        $this->assertStringContainsString('Bao_Cao_Doanh_Thu_SmartTech', $response->headers->get('content-disposition') ?? '');
    }

    public function test_unauthorized_user_cannot_export_excel(): void
    {
        $customer = User::where('role', 'customer')->first();

        $response = $this->actingAs($customer)->get('/admin/dashboard/export');

        $response->assertStatus(403);
    }
}
