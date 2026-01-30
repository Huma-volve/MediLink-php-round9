<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {

            $doctor = Doctor::inRandomOrder()->first();
            $patient = Patient::inRandomOrder()->first();

            if ($doctor && $patient) {
                Appointment::create([
                    'patient_id'        => $patient->id,
                    'doctor_id'         => $doctor->id,
                    'appointment_date'  => Carbon::now()->addDays(rand(1, 15))->format('Y-m-d'),
                    'appointment_time'  => rand(9, 17) . ':00:00',
                    'status'            => 'upcoming',
                    'reason_for_visit'  => 'Routine Checkup',
                    'consultation_type' => rand(0, 1) ? 'online' : 'online',
                ]);
            }
        }
    }
}
