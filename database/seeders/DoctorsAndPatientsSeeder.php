<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorsAndPatientsSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Ensure specialization exists
        $spec = Specialization::firstOrCreate(
            ['name' => 'General Medicine'],
            ['name' => 'General Medicine']
        );

        // 2) Create doctor user
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Dr Demo',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'is_active' => true,
            ]
        );

        // 3) Create doctor profile using the real specialization id
        Doctor::updateOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'license_number' => 'D001',
                'experience_years' => 10,
                'specialization_id' => $spec->id,
                'location' => 'Cairo',
                'is_verified' => true,
                'current_balance' => 1000,
            ]
        );

        // 4) Create patient user
        $patientUser = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Patient Demo',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'is_active' => true,
            ]
        );

        // 5) Create patient profile
        Patient::updateOrCreate(
            ['user_id' => $patientUser->id],
            [
                'date_of_birth' => '1999-01-01',
                'blood_group' => 'O+',
                'emergency_contact_name' => 'Emergency Person',
                'emergency_contact_phone' => '01000000000',
                'emergency_contact_relationship' => 'Brother',
            ]
        );
    }
}
