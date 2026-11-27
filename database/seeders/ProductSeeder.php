<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * @var array<int, int>
     */
    private array $productCategoryMap = [];

    private array $productSlugMap = [];

    /**
     * @var array<int, string> variant_id => color_slug
     */
    private array $variantColorSlugMap = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = base_path('CSDD_THUCTAP_CHINH.sql');
        if (! file_exists($sqlPath)) {
            $this->command->error("File SQL không tồn tại tại: {$sqlPath}");

            return;
        }

        $sql = file_get_contents($sqlPath);

        Schema::disableForeignKeyConstraints();
        DB::table('product_images')->truncate();
        DB::table('product_specifications')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Parse and Insert Products
        $this->seedProducts($sql);

        // 2. Parse, Sanitize and Insert Product Variants
        $this->seedVariants($sql);

        // 3. Parse, Sanitize and Insert Product Specifications
        $this->seedProductSpecifications($sql);

        // 4. Attach each variant to its color-specific images
        $this->seedProductImages();
    }

    private function seedProducts(string $sql): void
    {
        preg_match_all('/INSERT INTO `products`.*?VALUES\s*(.*?);/s', $sql, $blocks);
        $products = [];
        $now = now();

        foreach ($blocks[1] as $block) {
            preg_match_all(
                "/\((\d+),\s*(\d+),\s*(\d+),\s*'([^']+)',\s*'([^']+)',\s*'([^']+)',\s*([\d\.]+),\s*([\d\.]+),\s*(\d+),\s*(\d+),\s*(\d+|NULL),\s*([\d\.]+|NULL),\s*([\d\.]+|NULL),\s*'(.*?)',\s*'(.*?)',\s*(\d+),\s*(\d+)\)/s",
                $block,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $id = (int) $m[1];
                $categoryId = (int) $m[2];
                $name = $m[4];
                $slug = $m[5];

                $this->productCategoryMap[$id] = $categoryId;
                $this->productSlugMap[$id] = $slug;

                // Chuẩn hóa tên sản phẩm thực tế
                if ($id === 1) {
                    $name = 'MacBook Air 13 inch M3 (2024)';
                } elseif ($id === 2) {
                    $name = 'MacBook Air 15 inch M3 (2024)';
                }

                $products[] = [
                    'id' => $id,
                    'category_id' => $categoryId,
                    'brand_id' => (int) $m[3],
                    'name' => $name,
                    'slug' => $slug,
                    'sku_master' => $m[6],
                    'base_price' => (float) $m[7],
                    'sale_price' => (float) $m[8],
                    'warranty_months' => (int) $m[9],
                    'weight_grams' => (int) $m[10],
                    'battery_mah' => $m[11] === 'NULL' ? null : (int) $m[11],
                    'battery_wh' => $m[12] === 'NULL' ? null : (float) $m[12],
                    'screen_size' => $m[13] === 'NULL' ? null : (float) $m[13],
                    'description' => stripslashes($m[14]),
                    'ai_summary' => stripslashes($m[15]),
                    'total_sold' => (int) $m[16],
                    'is_active' => (bool) $m[17],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($products, 50) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        $this->command->info('Đã nạp thành công '.count($products).' sản phẩm.');
    }

    private function seedVariants(string $sql): void
    {
        preg_match_all('/INSERT INTO `product_variants`.*?VALUES\s*(.*?);/s', $sql, $blocks);
        $variants = [];
        $now = now();

        foreach ($blocks[1] as $block) {
            preg_match_all(
                "/\((\d+),\s*(\d+),\s*'([^']+)',\s*'([^']+)',\s*'([^']+)',\s*(NULL|'[^']*'),\s*(NULL|'[^']*'),\s*([\d\.]+),\s*([\d\.]+),\s*(\d+),\s*(\d+),\s*(\d+)\)/s",
                $block,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $id = (int) $m[1];
                $productId = (int) $m[2];
                $sku = $m[3];
                $isV1 = str_ends_with($sku, '-V1');

                $ram = $m[6] === 'NULL' ? null : trim($m[6], "'");
                $storage = $m[7] === 'NULL' ? null : trim($m[7], "'");

                // Khử RAM và Ổ cứng ảo cho phụ kiện (chuột, bàn phím, tai nghe)
                if ($productId >= 23 && $productId <= 25) { // Phụ kiện Apple
                    $ram = null;
                    $storage = null;
                } elseif ($productId === 50 || ($productId >= 73 && $productId <= 75) || ($productId >= 226 && $productId <= 250)) { // Chuột, phím, tai nghe
                    $ram = null;
                    $storage = null;
                }

                // Chuẩn hóa màu sắc thật theo từng dòng sản phẩm
                [$colorName, $colorHex] = $this->resolveRealColor($productId, $isV1, $m[4], $m[5]);

                // Tạo color slug cho ánh xạ ảnh theo màu
                $this->variantColorSlugMap[$id] = $this->slugifyColor($colorName);

                $variants[] = [
                    'id' => $id,
                    'product_id' => $productId,
                    'sku' => $sku,
                    'color_name' => $colorName,
                    'color_hex' => $colorHex,
                    'ram' => $ram,
                    'storage' => $storage,
                    'price' => (float) $m[8],
                    'sale_price' => (float) $m[9],
                    'stock_quantity' => (int) $m[10],
                    'low_stock_threshold' => (int) $m[11],
                    'is_active' => (bool) $m[12],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($variants, 50) as $chunk) {
            DB::table('product_variants')->insert($chunk);
        }

        $this->command->info('Đã nạp thành công '.count($variants).' biến thể với màu sắc & cấu hình thực tế.');
    }

    private function resolveRealColor(int $productId, bool $isV1, string $defaultColor, string $defaultHex): array
    {
        // 1. Dòng iPhone
        if ($productId === 10) { // iPhone 16 Pro Max
            return $isV1 ? ['Titan Sa Mạc (Desert Titanium)', '#C2AC99'] : ['Titan Tự Nhiên (Natural Titanium)', '#9F9A93'];
        }
        if ($productId === 11) { // iPhone 16 Pro
            return $isV1 ? ['Titan Đen (Black Titanium)', '#38373B'] : ['Titan Trắng (White Titanium)', '#F2F1ED'];
        }
        if ($productId === 12) { // iPhone 16 Plus
            return $isV1 ? ['Xanh Lưu Ly (Ultramarine)', '#4E6E8E'] : ['Hồng (Pink)', '#E8A5A5'];
        }
        if ($productId === 13) { // iPhone 16
            return $isV1 ? ['Xanh Mòng Két (Teal)', '#8CA7A1'] : ['Đen (Black)', '#35393B'];
        }
        if ($productId === 14) { // iPhone 15 Pro Max
            return $isV1 ? ['Titan Tự Nhiên (Natural Titanium)', '#9F9A93'] : ['Titan Xanh (Blue Titanium)', '#3B4450'];
        }
        if ($productId === 15) { // iPhone 15
            return $isV1 ? ['Hồng Pastel (Pink)', '#F9E5E8'] : ['Đen (Black)', '#35393B'];
        }
        if ($productId === 16) { // iPhone 14
            return $isV1 ? ['Xanh Dương (Blue)', '#A0B4C8'] : ['Ánh Sao (Starlight)', '#F0E4D3'];
        }
        if ($productId === 17) { // iPhone 13
            return $isV1 ? ['Xanh Lá (Green)', '#3B4E43'] : ['Hồng (Pink)', '#F9E5E8'];
        }

        // 2. Dòng MacBook
        if ($productId >= 1 && $productId <= 4) { // MacBook Air
            return $isV1 ? ['Xanh Đen (Midnight)', '#1F2937'] : ['Ánh Sao (Starlight)', '#F4EAD4'];
        }
        if ($productId >= 5 && $productId <= 9) { // MacBook Pro
            return $isV1 ? ['Đen Không Gian (Space Black)', '#1C1C1E'] : ['Bạc (Silver)', '#E5E7EB'];
        }

        // 3. Dòng iPad
        if ($productId >= 18 && $productId <= 19) { // iPad Pro
            return $isV1 ? ['Đen Không Gian (Space Black)', '#1C1C1E'] : ['Bạc (Silver)', '#E5E7EB'];
        }
        if ($productId >= 20 && $productId <= 21) { // iPad Air
            return $isV1 ? ['Xanh Da Trời (Blue)', '#C2D5E8'] : ['Ánh Sao (Starlight)', '#F4EAD4'];
        }
        if ($productId === 22) { // iPad Mini
            return $isV1 ? ['Tím (Purple)', '#DCD5E8'] : ['Xám Không Gian (Space Gray)', '#4B5563'];
        }

        // 4. Phụ kiện Apple
        if ($productId === 23) { // AirPods Max
            return $isV1 ? ['Bạc (Silver)', '#E3E4E6'] : ['Xanh Đen (Midnight)', '#1F2937'];
        }
        if ($productId === 24) { // AirPods Pro 2 (Chỉ có màu trắng duy nhất ngoài đời!)
            return ['Trắng Tinh Khôi (White)', '#FFFFFF'];
        }
        if ($productId === 25) { // Magic Mouse
            return $isV1 ? ['Trắng (White)', '#F5F5F7'] : ['Đen (Black)', '#1D1D1F'];
        }

        // 5. Asus
        if ($productId >= 26 && $productId <= 29) { // Zenbook
            return $isV1 ? ['Xanh Lam Trầm (Ponder Blue)', '#2C3E50'] : ['Bạc Ánh Sương (Foggy Silver)', '#D5D8DC'];
        }
        if ($productId >= 30 && $productId <= 34) { // Vivobook
            return $isV1 ? ['Bạc Ánh Trăng (Cool Silver)', '#DCDDE0'] : ['Đen Indie (Indie Black)', '#212529'];
        }
        if ($productId === 43 || $productId === 44) { // ROG Zephyrus
            return $isV1 ? ['Xám Nhật Thực (Eclipse Gray)', '#374151'] : ['Trắng Bạch Kim (Platinum White)', '#F3F4F6'];
        }
        if (($productId >= 39 && $productId <= 42) || ($productId >= 46 && $productId <= 48)) { // ROG Strix, TUF
            return $isV1 ? ['Xám Cơ Khí (Mecha Gray)', '#4B5563'] : ['Đen Huyền Bí (Eclipse Black)', '#1C1C1E'];
        }
        if ($productId === 49) { // ROG Phone 8
            return $isV1 ? ['Đen Phantom (Phantom Black)', '#1F2937'] : ['Xám Bão Tố (Storm Grey)', '#4B5563'];
        }
        if ($productId === 50) { // Chuột ROG Harpe
            return $isV1 ? ['Đen (Black)', '#1C1C1E'] : ['Trắng Ánh Trăng (Moonlight White)', '#F5F5F5'];
        }

        // 6. Dell
        if ($productId >= 51 && $productId <= 54) { // XPS
            return $isV1 ? ['Bạch Kim (Platinum)', '#E5E7EB'] : ['Than Chì (Graphite)', '#374151'];
        }
        if (($productId >= 55 && $productId <= 59) || ($productId >= 63 && $productId <= 64)) { // Inspiron / Vostro
            return $isV1 ? ['Đen Carbon (Carbon Black)', '#1C1C1C'] : ['Bạc Bạch Kim (Platinum Silver)', '#D3D3D3'];
        }
        if ($productId >= 67 && $productId <= 69) { // Alienware Laptops
            return $isV1 ? ['Xám Đêm Trăng (Dark Metallic Moon)', '#2C2D30'] : ['Trắng Ánh Sao (Lunar Light)', '#E8E8E8'];
        }
        if ($productId >= 73 && $productId <= 75) { // Alienware Gear
            return $isV1 ? ['Xám Đêm Trăng (Dark Side of the Moon)', '#2C2D30'] : ['Trắng Ánh Sao (Lunar Light)', '#E8E8E8'];
        }

        // 7. Lenovo
        if ($productId >= 76 && $productId <= 80) { // ThinkPad
            return $isV1 ? ['Đen Vân Carbon (Deep Black)', '#111827'] : ['Đen Nhám (Matte Black)', '#1F2937'];
        }
        if ($productId >= 89 && $productId <= 92) { // Legion
            return $isV1 ? ['Xám Bão Tố (Storm Grey)', '#4B5563'] : ['Xám Đen Onyx (Onyx Grey)', '#2B2C2E'];
        }

        // 8. Samsung
        if ($productId === 151) { // S24 Ultra
            return $isV1 ? ['Xám Titan (Titanium Gray)', '#7A797B'] : ['Đen Titan (Titanium Black)', '#2B2B2C'];
        }
        if ($productId === 152 || $productId === 153) { // S24 / S24+
            return $isV1 ? ['Tím Coban (Cobalt Violet)', '#5E5669'] : ['Vàng Amber (Amber Yellow)', '#E6E1C5'];
        }
        if ($productId === 155) { // Z Fold5
            return $isV1 ? ['Xanh Icy (Icy Blue)', '#93C5FD'] : ['Đen Phantom (Phantom Black)', '#1F2937'];
        }
        if ($productId === 156) { // Z Flip5
            return $isV1 ? ['Xanh Mint', '#A3E4D7'] : ['Tím Lavender', '#D7BDE2'];
        }
        if ($productId === 164 || $productId === 165) { // Tab S9
            return $isV1 ? ['Than Chì (Graphite)', '#3A3B3C'] : ['Kem (Beige)', '#E8E2D4'];
        }

        // 9. Xiaomi
        if ($productId >= 176 && $productId <= 178) { // Xiaomi 14 / 14T Pro
            return $isV1 ? ['Xám Titan (Titan Gray)', '#6B7280'] : ['Xanh Titan (Titan Blue)', '#3B5998'];
        }

        // 10. Sony
        if ($productId === 201) { // WH-1000XM5
            return $isV1 ? ['Đen (Black)', '#1F1F1F'] : ['Bạc Ánh Kim (Silver)', '#E6E5E0'];
        }
        if ($productId === 206) { // WF-1000XM5
            return $isV1 ? ['Đen (Black)', '#1F1F1F'] : ['Bạc (Silver)', '#E6E5E0'];
        }
        if ($productId === 221) { // Xperia 1 VI
            return $isV1 ? ['Đen (Black)', '#1A1A1A'] : ['Xanh Rêu (Khaki Green)', '#4A5B4E'];
        }

        // 11. Logitech
        if ($productId === 226) { // MX Master 3S
            return $isV1 ? ['Đen Graphite', '#2A2B2D'] : ['Xám Nhạt Pale Grey', '#E3E3E3'];
        }
        if ($productId === 236) { // MX Keys S
            return $isV1 ? ['Đen Than Chì', '#1F2937'] : ['Xám Bạc Pale Grey', '#E5E7EB'];
        }
        if ($productId === 228) { // M650 Signature
            return $isV1 ? ['Hồng Pastel', '#F4C2C2'] : ['Trắng Tinh Khôi', '#FFFFFF'];
        }
        if ($productId === 246) { // G Pro X 2
            return $isV1 ? ['Trắng Tinh Khôi', '#FFFFFF'] : ['Đen (Black)', '#1A1A1A'];
        }

        // 12. Bản đồ màu sắc chi tiết cho các sản phẩm còn lại (Asus, Dell, Lenovo, HP, MSI, Samsung, Xiaomi, Sony, Logitech...)
        static $colorsMap = null;
        if ($colorsMap === null) {
            $mapPath = __DIR__.'/product_colors_map.php';
            $colorsMap = file_exists($mapPath) ? require $mapPath : [];
        }

        if (isset($colorsMap[$productId])) {
            return $isV1 ? $colorsMap[$productId]['v1'] : $colorsMap[$productId]['v2'];
        }

        return [$defaultColor, $defaultHex];
    }

    private function seedProductSpecifications(string $sql): void
    {
        preg_match_all('/INSERT INTO `product_specifications`.*?VALUES\s*(.*?);/s', $sql, $blocks);
        $specs = [];

        foreach ($blocks[1] as $block) {
            preg_match_all(
                "/\((\d+),\s*(\d+),\s*'([^']*)',\s*(NULL|[\d\.]+)\)/s",
                $block,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $productId = (int) $m[1];
                $specId = (int) $m[2];
                $valText = stripslashes($m[3]);
                $valNum = $m[4] === 'NULL' ? null : (float) $m[4];

                // 1. Sửa lỗi GPU: Khử NVIDIA GeForce RTX khỏi Apple Silicon MacBooks!
                if ($productId >= 1 && $productId <= 9 && $specId === 4) {
                    if ($productId >= 1 && $productId <= 4) { // MacBook Air
                        $valText = 'Apple 10-core GPU tích hợp';
                    } elseif ($productId === 5) { // Pro 14 M4
                        $valText = 'Apple 10-core GPU tích hợp';
                    } elseif ($productId === 6 || $productId === 8) { // Pro M4 Pro
                        $valText = 'Apple 16-core GPU / 20-core GPU';
                    } else { // Pro M4 Max
                        $valText = 'Apple 32-core GPU / 40-core GPU';
                    }
                }

                // 2. Sửa lỗi tần số quét: MacBook Air chỉ có 60Hz (không phải 120Hz)
                if ($productId >= 1 && $productId <= 4 && $specId === 6) {
                    $valText = '60Hz';
                    $valNum = 60.0;
                }

                // 3. Sửa cổng kết nối: MacBook Air không có HDMI
                if ($productId >= 1 && $productId <= 4 && $specId === 9) {
                    $valText = '2x Thunderbolt / USB 4, Jack 3.5mm, MagSafe 3';
                }

                $specs[] = [
                    'product_id' => $productId,
                    'specification_id' => $specId,
                    'value_text' => $valText,
                    'value_numeric' => $valNum,
                ];
            }
        }

        foreach (array_chunk($specs, 100) as $chunk) {
            DB::table('product_specifications')->insert($chunk);
        }

        $this->command->info('Đã nạp thành công '.count($specs).' thông số kỹ thuật đã kiểm duyệt thực tế.');
    }

    private function seedProductImages(): void
    {
        $images = [];
        $genericImageCount = 0;
        $angles = [
            'main' => 1,
            'back' => 2,
            'ports' => 3,
        ];

        $variants = DB::table('product_variants')
            ->select(['id', 'product_id'])
            ->orderBy('id')
            ->get();

        foreach ($variants as $variant) {
            $variantId = (int) $variant->id;
            $productId = (int) $variant->product_id;
            $slug = $this->productSlugMap[$productId] ?? null;
            $colorSlug = $this->variantColorSlugMap[$variantId] ?? null;

            if ($slug === null || $colorSlug === null) {
                throw new \RuntimeException("Không tìm thấy slug sản phẩm hoặc màu cho biến thể {$variantId}.");
            }

            foreach ($angles as $angleType => $sortOrder) {
                $imageUrl = $this->resolveProductImageUrl($slug, $colorSlug, $angleType, $genericImageCount);

                $images[] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'image_url' => $imageUrl,
                    'angle_type' => $angleType,
                    'sort_order' => $sortOrder,
                ];
            }
        }

        foreach (array_chunk($images, 100) as $chunk) {
            DB::table('product_images')->insert($chunk);
        }

        if ($genericImageCount > 0) {
            $this->command->warn("{$genericImageCount} ảnh dùng ảnh chung sản phẩm vì chưa có ảnh đúng màu.");
        }

        $this->command->info('Đã gắn thành công '.count($images).' hình ảnh cho '.count($variants).' biến thể.');
    }

    /**
     * Resolve an existing image from public storage or public uploads, creating a placeholder only when absent.
     */
    private function resolveProductImageUrl(string $slug, string $colorSlug, string $angleType, int &$genericImageCount): string
    {
        $hasPublicStorage = is_dir(public_path('storage'));
        $colorFilename = "{$slug}-{$colorSlug}-{$angleType}.jpg";

        if ($hasPublicStorage && is_file(storage_path("app/public/products/{$colorFilename}"))) {
            return "/storage/products/{$colorFilename}";
        }

        if (is_file(public_path("uploads/products/{$colorFilename}"))) {
            return "/uploads/products/{$colorFilename}";
        }

        $genericImageCount++;
        $candidates = ["{$slug}-{$angleType}.jpg", "{$slug}-main.jpg"];

        foreach ($candidates as $filename) {
            if ($hasPublicStorage && is_file(storage_path("app/public/products/{$filename}"))) {
                return "/storage/products/{$filename}";
            }

            if (is_file(public_path("uploads/products/{$filename}"))) {
                return "/uploads/products/{$filename}";
            }
        }

        $fallbackPath = "/uploads/products/{$slug}-{$angleType}.jpg";
        $targetPath = public_path(ltrim($fallbackPath, '/'));
        $targetDir = dirname($targetPath);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $placeholder = public_path('images/placeholder.png');
        if (is_file($placeholder)) {
            copy($placeholder, $targetPath);
        } else {
            file_put_contents($targetPath, '');
        }

        return $fallbackPath;
    }

    /**
     * Convert Vietnamese color name to ASCII slug.
     * Example: "Titan Sa Mạc (Desert Titanium)" -> "titan-sa-mac"
     */
    private function slugifyColor(string $colorName): string
    {
        // Remove parenthesized English name
        $name = preg_replace('/\s*\(.*?\)\s*/', '', $colorName);
        $name = str_replace(['Đ', 'đ'], '', $name);
        // Transliterate Vietnamese characters to ASCII
        $transliterated = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $name);
        // Replace non-alphanumeric with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $transliterated);

        return trim($slug, '-') ?: 'default';
    }
}
