<?php

namespace Database\Seeders;

use App\Models\Installation;
use Illuminate\Database\Seeder;

final class InstallationSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $installations = [
            ['name' => 'Installation A', 'description' => 'Installation description A', 'proportional' => 25.00],
            ['name' => 'Installation B', 'description' => 'Installation description B', 'proportional' => 50.00],
            ['name' => 'Installation C', 'description' => 'Installation description C', 'proportional' => 75.00],
        ];

        foreach ($installations as $installationData) {
            Installation::create([
                'name' => $installationData['name'],
                'description' => $installationData['description'],
                'proportional' => $installationData['proportional'],
            ]);
        }
    }
}