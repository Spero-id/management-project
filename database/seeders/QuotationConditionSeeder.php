<?php

namespace Database\Seeders;

use App\Models\QuotationCondition;
use Illuminate\Database\Seeder;

final class QuotationConditionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $quotationConditions = [
            ['condition' => 'Delivery time in 4-12 weeks after Down Payment'],
            ['condition' => 'Term Payment DP : 50%, Before Delivery : 50%'],
            ['condition' => 'The Prices have not included any government tax PPn & PPh'],
        ];

        foreach ($quotationConditions as $conditionData) {
            QuotationCondition::create([
                'condition' => $conditionData['condition'],
            ]);
        }
    }
}