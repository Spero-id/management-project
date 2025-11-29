<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

final class SettingSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $settings = [
            ['setting_name' => 'currency_exchange_rate', 'setting_value' => '15000'],
            ['setting_name' => 'total_jasa', 'setting_value' => '10'],
        ];

        foreach ($settings as $settingData) {
            Setting::create([
                'setting_name' => $settingData['setting_name'],
                'setting_value' => $settingData['setting_value'],
            ]);
        }
    }
}