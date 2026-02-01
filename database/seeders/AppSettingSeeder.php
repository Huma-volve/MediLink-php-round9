<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::create([
            'app_name' => 'My Awesome App',
            'app_version' => '1.0.0',
            'company_name' => 'My Company',
            'terms_url' => 'https://example.com/terms',
            'privacy_url' => 'https://example.com/privacy',
            'license_url' => 'https://example.com/license',
            'release_notes_url' => 'https://example.com/release-notes',
        ]);
    }
}
