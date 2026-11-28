<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechCornerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_links_to_tech_corner_and_all_three_articles(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('tech-corner.index'), false);
        $response->assertSee(route('tech-corner.show', 'goc-lam-viec-toi-gian'), false);
        $response->assertSee(route('tech-corner.show', 'chon-may-tinh-dau-tien'), false);
        $response->assertSee(route('tech-corner.show', 'pin-ben-hon-moi-ngay'), false);
    }

    public function test_tech_corner_index_lists_articles_and_filters_by_topic(): void
    {
        $indexResponse = $this->get(route('tech-corner.index'));

        $indexResponse->assertOk();
        $indexResponse->assertSee('Gọn một góc bàn. Rộng một khoảng sáng tạo.');
        $indexResponse->assertSee('Chọn máy tính đầu tiên: Bắt đầu từ điều bạn cần.');
        $indexResponse->assertSee('Pin bền hơn từ những thói quen nhỏ mỗi ngày.');

        $filteredResponse = $this->get(route('tech-corner.index', ['topic' => 'tu-van-chon-mua']));

        $filteredResponse->assertOk();
        $filteredResponse->assertSee('Chọn máy tính đầu tiên: Bắt đầu từ điều bạn cần.');
        $filteredResponse->assertDontSee('Gọn một góc bàn. Rộng một khoảng sáng tạo.');
    }

    public function test_tech_corner_index_rejects_invalid_topics(): void
    {
        $this->get(route('tech-corner.index', ['topic' => 'unknown-topic']))->assertNotFound();
        $this->get('/goc-cong-nghe?topic=%3Cscript%3E')->assertStatus(400);
    }

    public function test_each_tech_corner_article_renders_detail_page_and_recommendations(): void
    {
        $laptopCategory = Category::factory()->create([
            'name' => 'Laptop',
            'slug' => 'laptop',
        ]);

        Product::factory()->create([
            'category_id' => $laptopCategory->id,
            'name' => 'D&D Book Air 14 M4',
            'slug' => 'dd-book-air-14-m4',
            'is_active' => true,
        ]);

        foreach (['goc-lam-viec-toi-gian', 'chon-may-tinh-dau-tien', 'pin-ben-hon-moi-ngay'] as $slug) {
            $this->get(route('tech-corner.show', $slug))->assertOk();
        }

        $guideResponse = $this->get(route('tech-corner.show', 'chon-may-tinh-dau-tien'));
        $guideResponse->assertOk();
        $guideResponse->assertSee('Điểm cốt lõi cần nhớ');
        $guideResponse->assertSee('D&D Book Air 14 M4');
        $guideResponse->assertSee('Đọc tiếp từ Góc công nghệ');
    }

    public function test_tech_corner_show_rejects_invalid_or_missing_slug(): void
    {
        $this->get('/goc-cong-nghe/bai-viet-khong-ton-tai')->assertNotFound();
        $this->get('/goc-cong-nghe/invalid$slug')->assertStatus(400);
    }
}
