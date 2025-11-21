<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            ['item' => 'Bor Drill makita', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Cililin'],
            ['item' => 'Bor Drill bosch', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Gerinda potong besi makita', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Gerinda keramik makita', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Bor kecil makita', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Surveyor Indonesia'],
            ['item' => 'Tangga M', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Surveyor Indonesia'],
            ['item' => 'Tangga M Krisbow', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 0, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'LAN Tester', 'stock_awal' => 1, 'unit_awal' => 'set', 'stock_akhir' => 1, 'unit_akhir' => 'set', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Multitester', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Trackper', 'stock_awal' => 2, 'unit_awal' => 'unit', 'stock_akhir' => 2, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'VTG', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Tang Ripert', 'stock_awal' => 2, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Office'],
            ['item' => 'Laser Meter', 'stock_awal' => 1, 'unit_awal' => 'unit', 'stock_akhir' => 1, 'unit_akhir' => 'unit', 'note' => '', 'posisi' => 'Arifin'],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
