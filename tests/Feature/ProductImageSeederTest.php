<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
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
                $colorImageUrl = "/storage/products/{$variant->slug}-{$colorSlug}-{$image->angle_type}.jpg";
                $genericImageUrl = "/storage/products/{$variant->slug}-{$image->angle_type}.jpg";
                $expectedImageUrl = is_file(storage_path("app/public/products/{$variant->slug}-{$colorSlug}-{$image->angle_type}.jpg"))
                    ? $colorImageUrl
                    : (is_file(storage_path("app/public/products/{$variant->slug}-{$image->angle_type}.jpg"))
                        ? $genericImageUrl
                        : "/storage/products/{$variant->slug}-main.jpg");

                $this->assertSame($variant->product_id, $image->product_id);
                $this->assertSame($expectedImageUrl, $image->image_url);
                $this->assertFileExists(public_path(ltrim($image->image_url, '/')));
            }
        }
    }
}
