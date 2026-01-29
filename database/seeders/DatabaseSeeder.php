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
<<<<<<< HEAD
            // DoctorsAndPatientsSeeder::class

            // SpelizationSeeder::class,
            // InsuranceSeeder::class,
            // PatientSeeder::class,

            // PrescriptionSeeder::class,
            // AppointmentSeeder::class,

=======
            SpelizationSeeder::class,
            InsuranceSeeder::class,
            PatientSeeder::class,
           DoctorsAndPatientsSeeder::class,
>>>>>>> 620136d37187ae1b06387174217497df0dca6d12
        ]);

        // User::factory()->count(5)->create();
    }
}
