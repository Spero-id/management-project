<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in proper dependency order
        $this->call([
            RoleSeeder::class,
            // InventorySeeder::class,
            // BorrowingSeeder::class,
            SettingSeeder::class,
            DivisionSeeder::class,
            ProspectStatusSeeder::class,
            InstallationSeeder::class,
            AccommodationSeeder::class,
            QuotationConditionSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            // ProjectSeeder::class,
            // ProspectSeeder::class,
        ]);
    }
}
