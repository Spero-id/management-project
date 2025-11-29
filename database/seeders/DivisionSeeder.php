<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

final class DivisionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'BOD', 'kode' => 'BOD', 'is_generate_sales_quotation_number' => true],
            ['name' => 'SALES & MARKETING', 'kode' => 'SMT', 'is_generate_sales_quotation_number' => true],
            ['name' => 'MANAGER FINANCE', 'kode' => 'FIN', 'is_generate_sales_quotation_number' => false],
            ['name' => 'GENERAL MANAGER', 'kode' => 'GEN', 'is_generate_sales_quotation_number' => false],
            ['name' => 'PROJECT & TECHNICAL', 'kode' => 'PTK', 'is_generate_sales_quotation_number' => false],
            ['name' => 'GENERAL AFFAIRS', 'kode' => 'GAF', 'is_generate_sales_quotation_number' => false],
            ['name' => 'LOGISTIC', 'kode' => 'LGS', 'is_generate_sales_quotation_number' => false],
        ];

        foreach ($divisions as $divisionData) {
            Division::create([
                'name' => $divisionData['name'],
                'kode' => $divisionData['kode'],
                'is_generate_sales_quotation_number' => $divisionData['is_generate_sales_quotation_number'],
            ]);
        }
    }
}