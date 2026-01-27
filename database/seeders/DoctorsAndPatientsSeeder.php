<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

class DoctorsAndPatientsSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء مستخدمين مرضى
        $patientUser1 = User::create([
            'full_name' => 'Patient One',
            'email' => 'patient1@example.com',
            'password' => bcrypt('password'),
            'role' => 'patient'
        ]);

        $patientUser2 = User::create([
            'full_name' => 'Patient Two',
            'email' => 'patient2@example.com',
            'password' => bcrypt('password'),
            'role' => 'patient'
        ]);

        // إنشاء مرضى
        $patient1 = Patient::create([
            'user_id' => $patientUser1->id,
            'date_of_birth' => '1990-01-01',
            'blood_group' => 'O+'
        ]);

        $patient2 = Patient::create([
            'user_id' => $patientUser2->id,
            'date_of_birth' => '1992-05-10',
            'blood_group' => 'A+'
        ]);

        // إنشاء مستخدمين دكاترة
        $doctorUser1 = User::create([
            'full_name' => 'Doctor One',
            'email' => 'doctor1@example.com',
            'password' => bcrypt('password'),
            'role' => 'doctor'
        ]);

        $doctorUser2 = User::create([
            'full_name' => 'Doctor Two',
            'email' => 'doctor2@example.com',
            'password' => bcrypt('password'),
            'role' => 'doctor'
        ]);

        $doctorUser3 = User::create([
            'full_name' => 'Doctor Three',
            'email' => 'doctor3@example.com',
            'password' => bcrypt('password'),
            'role' => 'doctor'
        ]);

        // إنشاء دكاترة
        $doctor1 = Doctor::create([
            'user_id' => $doctorUser1->id,
            'license_number' => 'D001',
            'experience_years' => 10,
            'spelization_id' => 1,
            'location' => 'Cairo',
            'is_verified' => true
        ]);

        $doctor2 = Doctor::create([
            'user_id' => $doctorUser2->id,
            'license_number' => 'D002',
            'experience_years' => 5,
            'spelization_id' => 2,
            'location' => 'Giza',
            'is_verified' => true
        ]);

        $doctor3 = Doctor::create([
            'user_id' => $doctorUser3->id,
            'license_number' => 'D003',
            'experience_years' => 8,
            'spelization_id' => 1,
            'location' => 'Cairo',
            'is_verified' => false
        ]);

        // إضافة بعض المفضلات تلقائيًا
        $patient1->favorites()->attach($doctor1->id, ['is_favorite' => true]);
        $patient1->favorites()->attach($doctor3->id, ['is_favorite' => true]);

        $patient2->favorites()->attach($doctor2->id, ['is_favorite' => true]);
    }
}
