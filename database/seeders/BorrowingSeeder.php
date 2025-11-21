<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\BorrowingItems;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Borrowing::factory(10)
            ->has(BorrowingItems::factory()->count(3), 'items')
            ->create();
    }
}
