<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_chat_prompt_uses_dnd_ai_branding_and_includes_catalog_hint(): void
    {
        $keyboardCategory = Category::factory()->create([
            'name' => 'Chuột, Bàn Phím & Gear',
            'slug' => 'chuot-ban-phim',
        ]);

        Product::factory()->create([
            'category_id' => $keyboardCategory->id,
            'name' => 'Bàn phím cơ Logitech Pebble K380s',
            'slug' => 'ban-phim-logitech-pebble-k380s',
            'base_price' => 890000,
            'is_active' => true,
        ]);

        config(['services.gemini.api_key' => 'fake-gemini-key']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'D&D AI xin chào bạn! D&D. hiện có mẫu Bàn phím cơ Logitech Pebble K380s giá 890.000đ.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/ai/chat', [
            'message' => 'Tư vấn bàn phím dưới 1 triệu',
            'session_id' => 'test-dnd-session',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        Http::assertSent(function (HttpRequest $request): bool {
            $prompt = (string) ($request['contents'][0]['parts'][0]['text'] ?? '');

            return str_contains($prompt, 'D&D AI')
                && str_contains($prompt, 'Bàn phím cơ Logitech Pebble K380s')
                && ! str_contains($prompt, 'SmartTech');
        });
    }

    public function test_ai_chat_fallback_reply_uses_dnd_branding_when_gemini_unavailable(): void
    {
        config(['services.gemini.api_key' => null]);

        $response = $this->postJson('/api/ai/chat', [
            'message' => 'Tư vấn laptop học tập',
            'session_id' => 'test-dnd-fallback',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $reply = (string) $response->json('reply');
        $this->assertStringContainsString('D&D.', $reply);
        $this->assertStringContainsString('D&D AI', $reply);
        $this->assertStringNotContainsString('SmartTech', $reply);
    }

    public function test_ai_summary_prompt_uses_dnd_branding(): void
    {
        $product = Product::factory()->create([
            'name' => 'D&D Ultrabook Air M4',
            'slug' => 'dd-ultrabook-air-m4',
        ]);

        config(['services.gemini.api_key' => 'fake-gemini-key']);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => '{"pros":["Mỏng nhẹ"],"cons":["Ít cổng"],"target_users":"Sinh viên"}'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson("/api/ai/summary/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('success', true);

        Http::assertSent(function (HttpRequest $request): bool {
            $prompt = (string) ($request['contents'][0]['parts'][0]['text'] ?? '');

            return str_contains($prompt, 'D&D.') && ! str_contains($prompt, 'SmartTech');
        });
    }
}
