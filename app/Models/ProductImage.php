<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'variant_id',
        'image_url',
        'angle_type',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(get: function (?string $value): string {
            if ($value !== null && str_starts_with($value, 'http')) {
                return $value;
            }

            $filename = $value !== null ? basename($value) : '';
            if ($filename !== '') {
                $storagePath = "products/{$filename}";
                if (Storage::disk('public')->exists($storagePath) && (is_dir(public_path('storage')) || ! is_file(public_path("uploads/products/{$filename}")))) {
                    return "/storage/{$storagePath}";
                }

                if (is_file(public_path("uploads/products/{$filename}"))) {
                    return "/uploads/products/{$filename}";
                }
            }

            $productSlug = $this->product?->slug;
            if ($productSlug !== null) {
                foreach (["{$productSlug}-{$this->angle_type}.jpg", "{$productSlug}-main.jpg"] as $fallbackName) {
                    $storagePath = "products/{$fallbackName}";
                    if (Storage::disk('public')->exists($storagePath) && is_dir(public_path('storage'))) {
                        return "/storage/{$storagePath}";
                    }

                    if (is_file(public_path("uploads/products/{$fallbackName}"))) {
                        return "/uploads/products/{$fallbackName}";
                    }
                }
            }

            return '/images/placeholder.svg';
        });
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<ProductVariant, $this>
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
