<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'no_peminjaman' => 'FKB/' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT) . '/' . now()->format('m/Y'),
            'keperluan' => fake()->randomElement(['POC', 'DEMO', 'BACKUP']),
            'penanggung_jawab' => fake()->name(),
        ];
    }
}
