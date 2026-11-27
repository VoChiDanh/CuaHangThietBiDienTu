<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageSeederTest extends TestCase
{
    public function test_each_variant_gets_its_matching_images_for_all_gallery_angles(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        $this->artisan('migrate:fresh', ['--database' => 'sqlite', '--force' => true])
            ->assertExitCode(0);
        $this->seed(DatabaseSeeder::class);

        $variants = DB::table('product_variants')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->select([
                'product_variants.id as variant_id',
                'product_variants.product_id',
                'product_variants.color_name',
                'products.slug',
            ])
            ->orderBy('product_variants.id')
            ->get();
        $images = DB::table('product_images')->get();

        $this->assertCount($variants->count() * 3, $images);

        $product = Product::with('images')->findOrFail($variants->first()->product_id);
        $this->assertSame(
            $variants->first()->variant_id,
            $product->images->first()->variant_id
        );

        foreach ($variants as $variant) {
            $variantImages = $images
                ->where('variant_id', $variant->variant_id)
                ->sortBy('sort_order');

            $this->assertSame(
                ['main', 'back', 'ports'],
                $variantImages->pluck('angle_type')->values()->all()
            );

            $colorName = preg_replace('/\s*\(.*?\)\s*/', '', $variant->color_name);
            $colorName = str_replace(['Đ', 'đ'], '', $colorName);
            $colorName = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $colorName);
            $colorSlug = trim(preg_replace('/[^a-z0-9]+/', '-', $colorName), '-');

            foreach ($variantImages as $image) {
                $hasPublicStorage = is_dir(public_path('storage'));
                $colorFilename = "{$variant->slug}-{$colorSlug}-{$image->angle_type}.jpg";
                $angleFilename = "{$variant->slug}-{$image->angle_type}.jpg";
                $mainFilename = "{$variant->slug}-main.jpg";

                if ($hasPublicStorage && is_file(storage_path("app/public/products/{$colorFilename}"))) {
                    $expectedImageUrl = "/storage/products/{$colorFilename}";
                } elseif (is_file(public_path("uploads/products/{$colorFilename}"))) {
                    $expectedImageUrl = "/uploads/products/{$colorFilename}";
                } elseif ($hasPublicStorage && is_file(storage_path("app/public/products/{$angleFilename}"))) {
                    $expectedImageUrl = "/storage/products/{$angleFilename}";
                } elseif (is_file(public_path("uploads/products/{$angleFilename}"))) {
                    $expectedImageUrl = "/uploads/products/{$angleFilename}";
                } elseif ($hasPublicStorage && is_file(storage_path("app/public/products/{$mainFilename}"))) {
                    $expectedImageUrl = "/storage/products/{$mainFilename}";
                } elseif (is_file(public_path("uploads/products/{$mainFilename}"))) {
                    $expectedImageUrl = "/uploads/products/{$mainFilename}";
                } else {
                    $expectedImageUrl = "/uploads/products/{$angleFilename}";
                }

                $this->assertSame($variant->product_id, $image->product_id);
                $this->assertSame($expectedImageUrl, $image->image_url);
                $this->assertFileExists(public_path(ltrim($image->image_url, '/')));
            }
        }
    }

    public function test_product_image_accessor_supports_uploads_and_public_storage(): void
    {
        $tempUploadPath = public_path('uploads/products/test-accessor-sample.jpg');
        if (! is_dir(dirname($tempUploadPath))) {
            mkdir(dirname($tempUploadPath), 0755, true);
        }
        file_put_contents($tempUploadPath, 'test-image-bytes');

        try {
            $uploadImage = new ProductImage([
                'image_url' => '/uploads/products/test-accessor-sample.jpg',
                'angle_type' => 'main',
            ]);
            $this->assertSame('/uploads/products/test-accessor-sample.jpg', $uploadImage->image_url);

            Storage::fake('public');
            Storage::disk('public')->put('products/uploaded-sample.jpg', 'stored-bytes');
            $storageImage = new ProductImage([
                'image_url' => '/storage/products/uploaded-sample.jpg',
                'angle_type' => 'main',
            ]);
            $this->assertSame('/storage/products/uploaded-sample.jpg', $storageImage->image_url);

            $missingImage = new ProductImage([
                'image_url' => '/uploads/products/non-existent-image.jpg',
                'angle_type' => 'main',
            ]);
            $this->assertSame('/images/placeholder.svg', $missingImage->image_url);
        } finally {
            @unlink($tempUploadPath);
        }
    }
}
