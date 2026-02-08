<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorWorkingHoursOnline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorWorkingHoursOnlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $doctors = Doctor::all(); 
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($doctors as $doctor) {
            foreach ($daysOfWeek as $day) {
                DoctorWorkingHoursOnline::factory()->create([
                    'doctor_id'   => $doctor->id,
                    'day_of_week' => $day,
                ]);
            }
        }
    }
}
