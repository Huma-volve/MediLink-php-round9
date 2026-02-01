<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function task_it_validates_medications_is_stored_as_array()
    {
        // 1. create a Prescription with medications as an array
        $meds = ['Panadol 500mg', 'Amoxicillin'];
        $prescription = new Prescription([
            'medications' => $meds,
            'diagnosis'   => 'Flu',
        ]);

        // 2. assert that medications is stored and retrieved as an array
        $this->assertIsArray($prescription->medications);
        $this->assertEquals('Panadol 500mg', $prescription->medications[0]);
    }

    /** @test */
    public function task_it_checks_prescription_belongs_to_appointment()
    {
        // test the relationship between Prescription and Appointment
        $appointment = Appointment::factory()->create();
        $prescription = Prescription::factory()->create([
            'appointment_id' => $appointment->id
        ]);

        // assert the relationship
        $this->assertInstanceOf(Appointment::class, $prescription->appointment);
        $this->assertEquals($appointment->id, $prescription->appointment->id);
    }

    /** @test */
    public function task_it_formats_diagnosis_string()
    {
        // اختبار  معالجة النصوص (مثلاً إزالة المسافات الزائدة)
        $prescription = new Prescription([
            'diagnosis' => '   حساسية صيفية    '
        ]);

        $this->assertEquals('حساسية صيفية', trim($prescription->diagnosis));
    }
}
