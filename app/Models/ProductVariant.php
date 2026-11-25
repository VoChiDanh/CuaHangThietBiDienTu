<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'color_name',
        'color_hex',
        'ram',
        'storage',
        'price',
        'sale_price',
        'stock_quantity',
        'low_stock_threshold',
        'last_sold_at',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'last_sold_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
