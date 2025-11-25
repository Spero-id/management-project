<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryOrderItems extends Model
{
    protected $fillable = [
        'delivery_order_id',
        'product_id',
        'sn',
        'qty',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sn' => 'array',
        ];
    }

    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
