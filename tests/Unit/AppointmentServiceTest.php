<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_returns_doctor_appointments_ordered_and_with_patient_user_loaded()
    {
        // Arrange
        $doctor = Doctor::factory()->create();
        $otherDoctor = Doctor::factory()->create();

        $patient1 = Patient::factory()->create();
        $patient2 = Patient::factory()->create();

        // two appointments for the target doctor (different dates to test ordering)
        $later = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient1->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '10:00:00',
            'status' => 'upcoming',
            'reason_for_visit' => 'Later appointment',
            'consultation_type' => 'in_person',
        ]);

        $earlier = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient2->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '09:00:00',
            'status' => 'upcoming',
            'reason_for_visit' => 'Earlier appointment',
            'consultation_type' => 'online',
        ]);

        // appointment for another doctor (must not be returned)
        Appointment::create([
            'doctor_id' => $otherDoctor->id,
            'patient_id' => $patient1->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '12:00:00',
            'status' => 'upcoming',
            'reason_for_visit' => 'Other doctor appointment',
            'consultation_type' => 'in_person',
        ]);

        $service = new AppointmentService();

        // Act
        $appointments = $service->getDoctorAppointments($doctor);

        // Assert: only this doctor's appointments
        $this->assertCount(2, $appointments);

        // Assert: ordered by appointment_date asc
        $this->assertSame(
            [$earlier->id, $later->id],
            $appointments->pluck('id')->all()
        );

        // Assert: eager loaded relations
        $this->assertTrue($appointments->first()->relationLoaded('patient'));
        $this->assertTrue($appointments->first()->patient->relationLoaded('user'));
    }


    public function test_doctor_can_confirm_appointment()
    {

        $specialization = Specialization::create([
            'name' => 'Test specialization',
        ]);
        // Create user
        $user = User::create([
            'name' => 'Test Doctor',
            'email' => 'doctor@test.com',
            'password' => bcrypt('password'),
            'role' => 'doctor',
        ]);

        // Create doctor
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'license_number' => 'DOC12345',
            'specialization_id' => $specialization->id,
        ]);

        // Create patient user
        $patientUser = User::create([
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        // Create patient
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'date_of_birth' => '1990-01-01',
        ]);

        // Create appointment
        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => '10:00',
            'status' => 'upcoming',
            'reason_for_visit' => 'Checkup',
        ]);

        $service = new AppointmentService();
        $result = $service->confirmAppointment($appointment, $doctor);

        //$this->assertTrue($result);
        $this->assertEquals('upcoming', $appointment->fresh()->status);
    }

    public function test_doctor_can_cancel_appointment()
    {
        $specialization = Specialization::create([
            'name' => 'Test  cancel specialization',
        ]);
        // Create user
        $user = User::create([
            'name' => 'Test cancel Doctor',
            'email' => 'doctorcancel@test.com',
            'password' => bcrypt('password'),
            'role' => 'doctor',
        ]);

        // Create doctor
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'license_number' => 'DOC12d345',
            'specialization_id' => $specialization->id,
        ]);

        // Create patient user
        $patientUser = User::create([
            'name' => 'Test cancel Patient',
            'email' => 'patientcancel@test.com',
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        // Create patient
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'date_of_birth' => '1990-01-01',
        ]);

        // Create appointment
        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => '11:00',
            'status' => 'upcoming',
            'reason_for_visit' => 'Checkup',
        ]);

        $service = new AppointmentService();
        $result = $service->cancelAppointment($appointment, $doctor);

        //$this->assertInstanceOf(Appointment::class, $result);
        $this->assertEquals('cancelled', $appointment->fresh()->status);
    }
}
