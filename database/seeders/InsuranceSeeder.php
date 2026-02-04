<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Insurance::create([
            'name' => 'Insurance 1',
        ]);
        Insurance::create([
            'name' => 'Insurance 2',
        ]);
        Insurance::create([
            'name' => 'Insurance 3',
        ]);
    }
}
