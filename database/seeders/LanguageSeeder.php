<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'english', 'native_name' => 'English'],
            ['code' => 'ar', 'name' => 'arabic', 'native_name' => 'اللغة العربية'],
            ['code' => 'fr', 'name' => 'french', 'native_name' => 'Français'],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
