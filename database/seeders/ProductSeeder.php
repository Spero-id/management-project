<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Setting;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = [
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, and 8 channels configurable USB audio', 'price_list' => 68145520, 'up' => 50, 'harga_dasar_dolar' => 2004.28, 'harga_dasar_rupiah_fob_luar_negeri' => 34072760, 'harga_dasar_rupiah_fob_jakarta' => 35572760, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB AI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, and 128 x 128 channels of AVB', 'price_list' => 77539040, 'up' => 50, 'harga_dasar_dolar' => 2280.56, 'harga_dasar_rupiah_fob_luar_negeri' => 38769520, 'harga_dasar_rupiah_fob_jakarta' => 40269520, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB CI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, and AEC technology (all 12 inputs)', 'price_list' => 96283240, 'up' => 50, 'harga_dasar_dolar' => 2831.86, 'harga_dasar_rupiah_fob_luar_negeri' => 48141620, 'harga_dasar_rupiah_fob_jakarta' => 49641620, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB VT', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, AEC technology (all 12 inputs), 2 channel VoIP, and standard FXO telephone interface', 'price_list' => 103328380, 'up' => 50, 'harga_dasar_dolar' => 3039.07, 'harga_dasar_rupiah_fob_luar_negeri' => 51664190, 'harga_dasar_rupiah_fob_jakarta' => 53164190, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB VT4', 'description' => 'Fixed I/O DSP with 4 analog inputs, 4 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, AEC technology (all 4 inputs), 2 channel VoIP, and standard FXO telephone interface', 'price_list' => 72842280, 'up' => 50, 'harga_dasar_dolar' => 2142.42, 'harga_dasar_rupiah_fob_luar_negeri' => 36421140, 'harga_dasar_rupiah_fob_jakarta' => 37921140, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'CRESTRON', 'type' => 'CP4', 'description' => 'Control System', 'price_list' => 86932560, 'up' => 50, 'harga_dasar_dolar' => 2556.84, 'harga_dasar_rupiah_fob_luar_negeri' => 43466280, 'harga_dasar_rupiah_fob_jakarta' => 44966280, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'CRESTRON', 'type' => 'TS1070', 'description' => 'Touchscreen 10 inch', 'price_list' => 84584180, 'up' => 50, 'harga_dasar_dolar' => 2487.77, 'harga_dasar_rupiah_fob_luar_negeri' => 42292090, 'harga_dasar_rupiah_fob_jakarta' => 43792090, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'VINTECH', 'type' => 'VTC1000', 'description' => 'Digital Conference', 'price_list' => 17000000, 'up' => 50, 'harga_dasar_dolar' => 500.00, 'harga_dasar_rupiah_fob_luar_negeri' => 8500000, 'harga_dasar_rupiah_fob_jakarta' => 10000000, 'distributor_origin' => 'CHINA', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'VINTECH', 'type' => 'VLM-MC-TC-154K', 'description' => 'Monitor Lift 17 inch 4K double motor', 'price_list' => 23800000, 'up' => 50, 'harga_dasar_dolar' => 700.00, 'harga_dasar_rupiah_fob_luar_negeri' => 11900000, 'harga_dasar_rupiah_fob_jakarta' => 13400000, 'distributor_origin' => 'CHINA', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
            ['brand' => 'VINTECH', 'type' => 'TESTING PRODUCT', 'description' => 'Monitor Lift 17 inch 4K double motor', 'price_list' => 23800000, 'up' => 50, 'harga_dasar_dolar' => 1, 'harga_dasar_rupiah_fob_luar_negeri' => 11900000, 'harga_dasar_rupiah_fob_jakarta' => 13400000, 'distributor_origin' => 'CHINA', 'shipping_fee_by_air' => 300000, 'weight' => 5, 'margin_percentage' => 50],
        ];

        $exchangeSetting = Setting::where('setting_name', 'currency_exchange_rate')->first();
        $exchangeRate = $exchangeSetting ? (int) $exchangeSetting->setting_value : 17000;

        foreach ($products as $productData) {
            $pricing = Product::calculatePricing(
                (float) $productData['harga_dasar_dolar'],
                $exchangeRate,
                (float) $productData['shipping_fee_by_air'],
                (float) $productData['weight'],
                (float) $productData['margin_percentage']
            );

            $product = Product::create([
                'name' => $productData['brand'].' - '.$productData['type'],
                'description' => $productData['description'],
                'price' => $pricing['unit_price'],
                'brand' => $productData['brand'],
                'type' => $productData['type'],
                'distributor_origin' => $productData['distributor_origin'],
                'weight' => $productData['weight'],
                'shipping_fee_by_air' => (float) $productData['shipping_fee_by_air'],
                'dollar_base_price' => (float) $productData['harga_dasar_dolar'],
                'base_price_rupiah_for_luar_negeri' => $pricing['base_price_rupiah_for_luar_negeri'],
                'base_price_rupiah_for_jakarta' => $pricing['base_price_rupiah_for_jakarta'],
                'margin_percentage' => (float) $productData['margin_percentage'],
            ]);

            // Create stock for each product
            ProductStock::create([
                'product_id' => $product->id,
                'stock_quantity' => rand(10, 100),
            ]);
        }
    }
}
