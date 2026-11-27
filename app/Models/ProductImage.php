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
        return Attribute::make(get: function (string $value): string {
            $filename = basename($value);
            if (Storage::disk('public')->exists("products/{$filename}")) {
                return "/storage/products/{$filename}";
            }

            $productSlug = $this->product?->slug;
            if ($productSlug !== null) {
                $productImage = "products/{$productSlug}-{$this->angle_type}.jpg";
                if (Storage::disk('public')->exists($productImage)) {
                    return "/storage/{$productImage}";
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
