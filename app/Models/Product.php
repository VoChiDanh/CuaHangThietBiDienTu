<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku_master',
        'base_price',
        'sale_price',
        'warranty_months',
        'weight_grams',
        'battery_mah',
        'battery_wh',
        'screen_size',
        'description',
        'ai_summary',
        'total_sold',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'float',
        'sale_price' => 'float',
        'battery_wh' => 'float',
        'screen_size' => 'float',
        'ai_summary' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->reviews()->where('status', 'approved')->avg('rating') ?? 5.0);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->where('status', 'approved')->count();
    }
}
