<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
       "name",
       "description",
       "price",
       "brand",
       "type",
       "distributor_origin",
       "weight",
       "shipping_fee_by_air",
       "dollar_base_price",
       "base_price_rupiah_for_luar_negeri",
       "base_price_rupiah_for_jakarta",
       "margin_percentage",
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Calculate product pricing based on dollar base price, exchange rate, and margin.
     */
    public static function calculatePricing(
        float $dollarBasePrice,
        float $exchangeRate,
        float $shippingFeeByAir,
        float $weight,
        float $marginPercentage
    ): array {
        $basePriceRupiahLuarNegeri = $dollarBasePrice * $exchangeRate;
        $basePriceRupiahJakarta = $basePriceRupiahLuarNegeri + ($shippingFeeByAir * $weight);
        $unitPrice = $basePriceRupiahLuarNegeri / ((100 - $marginPercentage) / 100);

        return [
            'base_price_rupiah_for_luar_negeri' => $basePriceRupiahLuarNegeri,
            'base_price_rupiah_for_jakarta' => $basePriceRupiahJakarta,
            'unit_price' => $unitPrice,
        ];
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class);
    }
}
