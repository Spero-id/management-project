<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    protected $fillable = [
        'product_id',
        'stock_quantity',
    ];

    /**
     * Get the product that owns the stock
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
