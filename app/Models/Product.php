<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'price',
        "brand",
        "type",
        "distributor_origin",
        "weight",
        "shipping_fee_by_air",
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
