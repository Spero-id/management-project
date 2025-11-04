<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationInstallationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'installation_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });
    }
}
