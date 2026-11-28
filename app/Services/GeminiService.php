<?php

namespace App\Services;

use App\Models\AiChatLog;
use App\Models\Product;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;

    protected string $model;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = Config::get('services.gemini.api_key');
        $this->model = Config::get('services.gemini.model', 'gemini-1.5-flash');
    }

    /**
     * Tóm tắt sản phẩm bằng AI: Ưu điểm, Nhược điểm, Đối tượng phù hợp
     *
     * @return array{pros: array<string>, cons: array<string>, target_users: string}
     */
    public function generateProductSummary(Product $product): array
    {
        $specsText = $this->buildSpecsText($product);

        $prompt = <<<PROMPT
Bạn là chuyên gia tư vấn công nghệ tại cửa hàng thiết bị điện tử D&D.. Hãy phân tích thông số kỹ thuật và tạo tóm tắt đánh giá cho sản phẩm sau:

Tên sản phẩm: {$product->name}
Hãng: {$product->brand?->name}
Danh mục: {$product->category?->name}
Giá niêm yết: {$product->base_price} VNĐ
Giá khuyến mãi: {$product->sale_price} VNĐ
Trọng lượng: {$product->weight_grams} g
Dung lượng pin: {$product->battery_mah} mAh ({$product->battery_wh} Wh)
Kích thước màn hình: {$product->screen_size} inch
Thông số chi tiết:
{$specsText}

Mô tả hiện tại: {$product->description}

Yêu cầu xuất ra định dạng JSON DUY NHẤT theo cấu trúc chính xác sau (không thêm markdown backtick hay bất cứ từ nào khác ngoài JSON):
{
  "pros": [
    "Ưu điểm nổi bật 1 (ngắn gọn, tập trung vào trải nghiệm thực tế)",
    "Ưu điểm nổi bật 2",
    "Ưu điểm nổi bật 3"
  ],
  "cons": [
    "Nhược điểm hoặc điểm cần lưu ý 1 (thẳng thắn, khách quan)",
    "Nhược điểm 2"
  ],
  "target_users": "Câu miêu tả ngắn gọn nhóm khách hàng phù hợp nhất (ví dụ: Sinh viên, nhân viên văn phòng cần máy mỏng nhẹ, pin trâu...)"
}
PROMPT;

        $response = $this->callGemini($prompt, true);

        if ($response !== null) {
            $parsed = $this->parseJson($response);
            if (isset($parsed['pros'], $parsed['cons'], $parsed['target_users'])) {
                return [
                    'pros' => (array) $parsed['pros'],
                    'cons' => (array) $parsed['cons'],
                    'target_users' => (string) $parsed['target_users'],
                ];
            }
        }

        // Fallback tự động thông minh nếu chưa có API Key hoặc mạng lỗi
        return $this->fallbackProductSummary($product);
    }

    /**
     * Chatbot tư vấn ngữ cảnh sản phẩm
     */
    public function chatWithContext(
        string $userMessage,
        string $sessionId,
        ?int $userId = null,
        ?Product $product = null
    ): string {
        $context = 'Bạn là D&D AI - Trợ lý ảo tư vấn công nghệ thông minh, nhiệt tình và am hiểu kỹ thuật của cửa hàng thiết bị điện tử D&D. (tuyệt đối luôn tự xưng là D&D AI hoặc D&D., không dùng bất kỳ tên cửa hàng nào khác).';

        if ($product !== null) {
            $specsText = $this->buildSpecsText($product);
            $context .= "\nKhách hàng hiện đang xem trang sản phẩm sau:\n";
            $context .= "Tên: {$product->name}\n";
            $context .= 'Giá: '.number_format($product->current_price, 0, ',', '.')." VNĐ\n";
            $context .= "Trọng lượng: {$product->weight_grams}g | Pin: {$product->battery_mah}mAh ({$product->battery_wh}Wh) | Màn hình: {$product->screen_size} inch\n";
            $context .= "Thông số: {$specsText}\n";
            $context .= 'Quy định bảo hành: '.$product->warranty_months." tháng toàn quốc.\n";
            $context .= "Hãy trả lời đúng thực tế kỹ thuật của máy này, không bịa đặt thông số. Tư vấn chân thành, ngắn gọn, súc tích (dưới 4 câu nếu có thể).\n";
        } else {
            $context .= "\nKhách hàng đang duyệt cửa hàng D&D.. Hãy tư vấn nhiệt tình các dòng Laptop, Điện thoại, Máy tính bảng, Âm thanh và Phụ kiện theo nhu cầu và ngân sách của họ.\n";
            $catalogHint = $this->buildStoreCatalogHint($userMessage);
            if ($catalogHint !== '') {
                $context .= "Một số sản phẩm hiện đang bán tại D&D. liên quan để bạn ưu tiên gợi ý (nếu chưa có mẫu đúng tầm giá khách hỏi, hãy nói rõ và gợi ý mẫu gần nhất trong danh sách):\n{$catalogHint}\n";
            }
        }

        $context .= "Định dạng câu trả lời gọn gàng bằng tiếng Việt thuần, dùng gạch đầu dòng '-' khi liệt kê, hạn chế dùng ký tự Markdown '**'.\n";
        $prompt = "{$context}\n\nKhách hỏi: \"{$userMessage}\"\n\nTrả lời khách hàng bằng tiếng Việt thân thiện, chuyên nghiệp:";

        $aiReply = $this->callGemini($prompt, false)
            ?? 'Cảm ơn bạn đã quan tâm đến D&D.! Hiện tại hệ thống tư vấn D&D AI đang bận một chút, bạn có thể tham khảo bảng thông số kỹ thuật bên dưới hoặc để lại câu hỏi nhé!';

        // Lưu vào bảng ai_chat_logs
        try {
            AiChatLog::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $product?->id,
                'user_message' => $userMessage,
                'ai_response' => $aiReply,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Không thể lưu ai_chat_logs: '.$e->getMessage());
        }

        return $aiReply;
    }

    /**
     * Tạo bài viết mô tả sản phẩm tự động cho Admin
     */
    public function generateProductDescription(string $productName, ?string $brand, array $specs = []): string
    {
        $specsFormatted = '';
        foreach ($specs as $key => $val) {
            $specsFormatted .= "- {$key}: {$val}\n";
        }

        $prompt = <<<PROMPT
Bạn là chuyên gia Content Marketing công nghệ. Hãy viết một bài mô tả sản phẩm chuẩn SEO, hấp dẫn cho sản phẩm sau:

Tên: {$productName}
Hãng: {$brand}
Thông số:
{$specsFormatted}

Yêu cầu:
- Định dạng HTML gồm các thẻ <p>, <h3>, <ul>, <li>, <strong> (KHÔNG dùng markdown backticks ```html).
- Bố cục gồm 3 phần:
  1. Giới thiệu tổng quan và thiết kế.
  2. Hiệu năng thực tế và trải nghiệm người dùng.
  3. Thời lượng pin, khả năng tản nhiệt & kết nối.
- Giọng văn chuyên nghiệp, kích thích mua sắm, tối ưu hóa công cụ tìm kiếm.
PROMPT;

        return $this->callGemini($prompt, false)
            ?? "<p><strong>{$productName}</strong> là thiết bị công nghệ chính hãng từ {$brand}, mang lại hiệu năng tối ưu và trải nghiệm đỉnh cao cho người dùng.</p>";
    }

    /**
     * Gọi Google Gemini REST API với cơ chế tự động chuyển model dự phòng
     */
    protected function callGemini(string $prompt, bool $expectJson = false): ?string
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $modelsToTry = array_unique([
            $this->model,
            'gemini-3.5-flash-lite',
            'gemini-flash-lite-latest',
            'gemini-3.8-flash',
        ]);

        foreach ($modelsToTry as $modelName) {
            try {
                $url = "{$this->baseUrl}/{$modelName}:generateContent?key={$this->apiKey}";

                $payload = [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => $expectJson ? 0.2 : 0.7,
                    ],
                ];

                if ($expectJson) {
                    $payload['generationConfig']['responseMimeType'] = 'application/json';
                }

                $response = Http::timeout(12)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();

                    return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                }

                Log::warning("Gemini model {$modelName} trả về {$response->status()}, đang thử model tiếp theo...");
            } catch (\Throwable $e) {
                Log::warning("Gemini model {$modelName} timeout/lỗi: {$e->getMessage()}, đang thử model tiếp theo...");
            }
        }

        return null;
    }

    protected function buildStoreCatalogHint(string $userMessage): string
    {
        try {
            $keywords = collect(preg_split('/\s+/u', mb_strtolower(trim($userMessage))) ?: [])
                ->filter(fn (string $word): bool => mb_strlen($word) >= 2 && ! in_array($word, ['tư', 'vấn', 'cho', 'tôi', 'mình', 'mua', 'giá', 'dưới', 'trên', 'từ', 'tới', 'triệu', 'ngàn', 'đồng', 'nào', 'tốt', 'nhất', 'với', 'cần', 'tìm'], true))
                ->take(4)
                ->values();

            $query = Product::with(['brand', 'category'])->where('is_active', true);

            if ($keywords->isNotEmpty()) {
                $query->where(function ($q) use ($keywords): void {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                            ->orWhereHas('category', fn ($catQ) => $catQ->where('name', 'like', "%{$word}%"))
                            ->orWhereHas('brand', fn ($brandQ) => $brandQ->where('name', 'like', "%{$word}%"));
                    }
                });
            }

            $products = $query->orderByRaw('COALESCE(NULLIF(sale_price, 0), base_price) ASC')
                ->limit(6)
                ->get();

            if ($products->isEmpty()) {
                $products = Product::with(['brand', 'category'])
                    ->where('is_active', true)
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get();
            }

            return $products->map(function (Product $item): string {
                $price = number_format($item->current_price, 0, ',', '.').'đ';
                $brand = $item->brand?->name ?? 'D&D.';
                $cat = $item->category?->name ?? 'Thiết bị';

                return "- {$item->name} ({$brand} · {$cat}): {$price}";
            })->implode("\n");
        } catch (\Throwable) {
            return '';
        }
    }

    protected function buildSpecsText(Product $product): string
    {
        $product->loadMissing('productSpecifications.specification');
        $lines = [];

        foreach ($product->productSpecifications as $ps) {
            $specName = $ps->specification?->name ?? 'Thông số';
            $unit = $ps->specification?->unit ? " {$ps->specification->unit}" : '';
            $lines[] = "- {$specName}: {$ps->value_text}{$unit}";
        }

        return implode("\n", $lines);
    }

    protected function parseJson(string $raw): ?array
    {
        $clean = trim($raw);
        $clean = preg_replace('/^```(?:json)?/i', '', $clean);
        $clean = preg_replace('/```$/', '', $clean);
        $clean = trim($clean);

        $decoded = json_decode($clean, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Fallback tóm tắt sản phẩm dựa trên thông số nếu chưa có key Gemini
     *
     * @return array{pros: array<string>, cons: array<string>, target_users: string}
     */
    protected function fallbackProductSummary(Product $product): array
    {
        $pros = [];
        $cons = [];

        if ($product->weight_grams && $product->weight_grams < 1300) {
            $pros[] = 'Trọng lượng siêu nhẹ chỉ '.number_format($product->weight_grams / 1000, 2).'kg, tính cơ động cực kỳ cao.';
        } elseif ($product->weight_grams && $product->weight_grams > 2200) {
            $cons[] = 'Trọng lượng '.number_format($product->weight_grams / 1000, 2).'kg khá đầm tay, cần balo trợ lực khi mang theo thường xuyên.';
        }

        if ($product->battery_wh && $product->battery_wh >= 70) {
            $pros[] = "Viên pin khủng {$product->battery_wh}Wh mang lại thời lượng sử dụng vượt trội cho cả ngày làm việc.";
        } elseif ($product->battery_mah && $product->battery_mah >= 5000) {
            $pros[] = "Dung lượng pin lớn {$product->battery_mah}mAh đáp ứng trọn vẹn nhu cầu sử dụng cường độ cao.";
        }

        if ($product->screen_size && $product->screen_size >= 15.6) {
            $pros[] = "Màn hình rộng {$product->screen_size} inch đem lại không gian làm việc và giải trí thoải mái.";
        }

        if (empty($pros)) {
            $pros[] = 'Thiết kế sang trọng, độ hoàn thiện cao, bảo hành chính hãng '.$product->warranty_months.' tháng.';
            $pros[] = 'Cấu hình cân đối, đáp ứng tốt nhu cầu học tập, làm việc và giải trí.';
        }

        if (empty($cons)) {
            $cons[] = 'Nên sử dụng củ sạc nhanh và phụ kiện chính hãng để đạt hiệu suất tối ưu.';
        }

        $targetUsers = match ($product->category_id) {
            2 => 'Sinh viên, nhân viên văn phòng và người thường xuyên di chuyển cần thiết bị mỏng nhẹ, pin tốt.',
            3 => 'Game thủ, lập trình viên và người dùng cần hiệu năng xử lý đồ họa mạnh mẽ.',
            4 => 'Kỹ sư đồ họa, dựng video chuyên nghiệp và người dùng máy trạm cao cấp.',
            5, 6, 7 => 'Người dùng yêu thích thiết bị di động chụp ảnh đẹp, màn hình sắc nét và thời lượng pin bền bỉ.',
            default => 'Khách hàng tìm kiếm thiết bị công nghệ chính hãng với mức giá hợp lý và bảo hành dài hạn.',
        };

        return [
            'pros' => $pros,
            'cons' => $cons,
            'target_users' => $targetUsers,
        ];
    }
}
