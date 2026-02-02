<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Favorite;

class DoctorsAndPatientsSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء مستخدمين مرضى
        $patientUser1 = User::firstOrCreate(
            ['email' => 'patient1@example.com'],
            [
                'name' => 'Patient One',
                'password' => bcrypt('password'),
                'role' => 'patient'
            ]
        );

        $patientUser2 = User::firstOrCreate(
            ['email' => 'patient2@example.com'],
            [
                'name' => 'Patient Two',
                'password' => bcrypt('password'),
                'role' => 'patient'
            ]
        );

        $patient1 = Patient::firstOrCreate(
            ['user_id' => $patientUser1->id],
            [
                'date_of_birth' => '1990-01-01',
                'blood_group' => 'O+'
            ]
        );

        $patient2 = Patient::firstOrCreate(
            ['user_id' => $patientUser2->id],
            [
                'date_of_birth' => '1992-05-10',
                'blood_group' => 'A+'
            ]
        );

        $doctorUser1 = User::firstOrCreate(
            ['email' => 'doctor1@example.com'],
            [
                'name' => 'Doctor One',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ]
        );

        $doctorUser2 = User::firstOrCreate(
            ['email' => 'doctor2@example.com'],
            [
                'name' => 'Doctor Two',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ]
        );

        $doctorUser3 = User::firstOrCreate(
            ['email' => 'doctor3@example.com'],
            [
                'name' => 'Doctor Three',
                'password' => bcrypt('password'),
                'role' => 'doctor'
            ]
        );

        $doctor1 = Doctor::firstOrCreate(
            ['user_id' => $doctorUser1->id],
            [
                'license_number' => 'D001',
                'experience_years' => 10,
                'specialization_id' => 1,
                'location' => 'Cairo',
                'is_verified' => true,
                'current_balance' => 1000
            ]
        );

        $doctor2 = Doctor::firstOrCreate(
            ['user_id' => $doctorUser2->id],
            [
                'license_number' => 'D002',
                'experience_years' => 5,
                'specialization_id' => 2,
                'location' => 'Giza',
                'is_verified' => true,
                'current_balance' => 2000

            ]
        );

        $doctor3 = Doctor::firstOrCreate(
            ['user_id' => $doctorUser3->id],
            [
                'license_number' => 'D003',
                'experience_years' => 8,
                'specialization_id' => 1,
                'location' => 'Cairo',
                'is_verified' => false,
                'current_balance' => 3000

            ]
        );

        Favorite::updateOrCreate(
            ['patient_id' => $patient1->id, 'doctor_id' => $doctor1->id],
            ['is_favorite' => true]
        );

        Favorite::updateOrCreate(
            ['patient_id' => $patient1->id, 'doctor_id' => $doctor3->id],
            ['is_favorite' => true]
        );

        Favorite::updateOrCreate(
            ['patient_id' => $patient2->id, 'doctor_id' => $doctor2->id],
            ['is_favorite' => true]
        );
    }
}
