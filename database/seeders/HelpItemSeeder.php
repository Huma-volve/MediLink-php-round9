<?php

namespace Database\Seeders;

use App\Models\HelpItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HelpItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HelpItem::create([
            'faq_url' => 'https://example.com/faq',
            'contact_support_url' => 'https://example.com/contact-support',
            'documentation_url' => 'https://example.com/docs',
            'video_tutorials_url' => 'https://example.com/videos',
        ]);
    }
}
