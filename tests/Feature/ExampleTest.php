<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Đã được 12.500+ khách hàng tin chọn');
        $response->assertSee('images/blog-workspace.jpg');
        $response->assertDontSee('Có trợ lý AI đồng hành cùng bạn');
    }
}
