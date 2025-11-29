<?php

namespace Database\Seeders;

use App\Models\ProspectStatus;
use Illuminate\Database\Seeder;

final class ProspectStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $prospectStatuses = [
            ['name' => 'Prospecting', 'persentage' => 15, 'color' => '#3B82F6'],
            ['name' => 'Qualification', 'persentage' => 30, 'color' => '#F59E0B'],
            ['name' => 'Proposal / Quotation', 'persentage' => 50, 'color' => '#8B5CF6'],
            ['name' => 'Negotiation', 'persentage' => 75, 'color' => '#06B6D4'],
            ['name' => 'Closing', 'persentage' => 100, 'color' => '#10B981'],
            ['name' => 'LOST', 'persentage' => 0, 'color' => '#EF4444'],
        ];

        foreach ($prospectStatuses as $statusData) {
            ProspectStatus::create([
                'name' => $statusData['name'],
                'persentage' => $statusData['persentage'],
                'color' => $statusData['color'],
            ]);
        }
    }
}