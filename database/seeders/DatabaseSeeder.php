<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            // DoctorsAndPatientsSeeder::class

            // SpecializationSeeder::class,

            // InsuranceSeeder::class,
            // PatientSeeder::class,

            // PrescriptionSeeder::class,
            // AppointmentSeeder::class,



           
           SpecializationSeeder::class,
            DoctorsAndPatientsSeeder::class,
            PrescriptionSeeder::class,
            AppointmentSeeder::class,   // PatientSeeder::class,
 PatientSeeder::class,

        ]);

        // User::factory()->count(5)->create();
    }
}
