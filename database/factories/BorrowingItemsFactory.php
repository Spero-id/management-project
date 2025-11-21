<?php

namespace Database\Factories;

use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BorrowingItems>
 */
class BorrowingItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['VINTECH', 'HIKVISION', 'DAHUA', 'AXIS', 'SAMSUNG'];
        $types = ['VLM-MC1C-15', 'VLM-MC-15', 'VTC-1000', 'PTZ-5000', 'DOME-200'];
        $stokTersedia = fake()->numberBetween(1, 50);

        return [
            'borrowing_id' => Borrowing::factory(),
            'brand' => fake()->randomElement($brands),
            'type' => fake()->randomElement($types),
            'stok_tersedia' => $stokTersedia,
            'jumlah_barang' => fake()->numberBetween(1, min(5, $stokTersedia)),
        ];
    }
}
