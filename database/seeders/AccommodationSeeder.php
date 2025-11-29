<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Seeder;

final class AccommodationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accommodations = [
            ['name' => 'HARGA PENGINAPAN', 'price' => 300000],
            ['name' => 'HARGA TRANSPORTASI KENDARAAN', 'price' => 300000],
        ];

        foreach ($accommodations as $accommodationData) {
            Accommodation::create([
                'name' => $accommodationData['name'],
                'price' => $accommodationData['price'],
            ]);
        }
    }
}