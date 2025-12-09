<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithStartRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $exchangeSetting = Setting::where('setting_name', 'currency_exchange_rate')->first();
        $exchangeRate = $exchangeSetting ? (int) $exchangeSetting->setting_value : 17000.0;

        $pricing = Product::calculatePricing(
            (float) $row[4] ?? 0, // dollar_base_price
            $exchangeRate,
            (float) $row[7] ?? 0, // shipping_fee_by_air
            (float) $row[6] ?? 0, // weight
            (float) $row[5] ?? 0  // margin_percentage (up_percentage)
        );

        return new Product([
            'name' => $row[0],
            'brand' => $row[1],
            'type' => $row[2],
            'distributor_origin' => $row[3],
            'dollar_base_price' => (float) $row[4] ?? 0,
            'margin_percentage' => (float) $row[5] ?? 0,
            'weight' => (float) $row[6] ?? 0,
            'shipping_fee_by_air' => (float) $row[7] ?? 0,
            'description' => $row[8],
            'price' => $pricing['unit_price'],
            'base_price_rupiah_for_luar_negeri' => $pricing['base_price_rupiah_for_luar_negeri'],
            'base_price_rupiah_for_jakarta' => $pricing['base_price_rupiah_for_jakarta'],
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Skip header row, start from row 2
    }
}
