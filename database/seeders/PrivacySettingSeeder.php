<?php

namespace Database\Seeders;

use App\Models\PrivacySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivacySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrivacySetting::factory()->count(5)->create();
    }
}
