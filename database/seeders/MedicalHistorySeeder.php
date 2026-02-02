<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalHistory;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

class MedicalHistorySeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors  = Doctor::all();

        foreach ($patients as $patient) {

            $doctor = $doctors->random();

            $prescription = Prescription::where('appointment_id', $patient->id)->inRandomOrder()->first();

            MedicalHistory::create([
                'patient_id'         => $patient->id,
                'doctor_id'          => $doctor->id,
                'prescription_id'    => $prescription?->id,
                'chronic_conditions' => 'Condition ' . rand(1, 5),
                'allergies'          => 'Allergy ' . rand(1, 5),
                'previous_surgeries' => 'Surgery ' . rand(1, 5),
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ]);
        }
    }
}
