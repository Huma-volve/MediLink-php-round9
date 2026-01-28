<?php

namespace Database\Seeders;

use App\Models\Spelization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpelizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {    
        Spelization::factory()->count(10)->create();
    }
}
