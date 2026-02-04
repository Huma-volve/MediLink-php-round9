<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Insurance;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\MedicalHistory;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function doctor_can_view_patient_details()
    {

        $doctorUser = User::factory()->create(['role' => 'doctor']);
        $doctor = Doctor::factory()->create([
            'user_id' => $doctorUser->id
        ]);


        $patientUser = User::factory()->create(['role' => 'patient']);
        $insurance = Insurance::factory()->create();
        $patient = Patient::factory()->create([
            'user_id' => $patientUser->id,
            'insurance_id' => $insurance->id
        ]);


        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => now()->addHour()->format('H:i:s'),
            'status' => 'pending',
            'reason_for_visit' => 'Routine checkup',
            'consultation_type' => 'in_person'
        ]);


        $prescription = Prescription::factory()->create([
            'appointment_id' => $appointment->id
        ]);


        $medicalHistory = MedicalHistory::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'prescription_id' => $prescription->id,
            'chronic_conditions' => 'Diabetes',
            'allergies' => 'Peanuts',
            'previous_surgeries' => 'Appendectomy',
        ]);


        Sanctum::actingAs($doctorUser, ['*']);


        $response = $this->getJson("/api/doctor/patient/{$patient->id}");


        $response->assertStatus(200);

        $response->assertJsonStructure([
            'patient' => [
                'id',
                'user' => ['id', 'name', 'email'],
                'date_of_birth',
                'insurance_company',
                'medical_histories',
                'prescriptions',
                'blood_group',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship'
            ]
        ]);
    }

    #[Test]
    public function guest_cannot_view_patient_details()
    {
        $patientUser = User::factory()->create(['role' => 'patient']);
        $insurance = Insurance::factory()->create();
        $patient = Patient::factory()->create([
            'user_id' => $patientUser->id,
            'insurance_id' => $insurance->id
        ]);


        $response = $this->getJson("/api/doctor/patient/{$patient->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function non_existing_patient_returns_404()
    {
        $doctorUser = User::factory()->create(['role' => 'doctor']);
        Sanctum::actingAs($doctorUser, ['*']);

        $response = $this->getJson("/api/doctor/patient/999");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Patient not found'
        ]);
    }
}
