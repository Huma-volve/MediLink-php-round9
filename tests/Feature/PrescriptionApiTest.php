<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrescriptionApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_prescription_successfully()
    {
        // 1. تجهيز البيانات (Acting As Doctor)
        $user = User::factory()->create();
        $this->actingAs($user);

        // إنشاء موعد موجود في القاعدة (لأن الـ Validation يتطلب exists:appointments,id)
        $appointment = Appointment::factory()->create();

        $payload = [
            'appointment_id'    => $appointment->id,
            'medications'       => ['Panadol', 'Antibiotic'],
            'diagnosis'         => 'Acute Pharyngitis',
            'frequency'         => '3 times daily',
            'duration_days'     => 7,
            'prescription_date' => '2026-01-30',
        ];

        // 2. تنفيذ الطلب
        $response = $this->postJson('/api/doctor/prescriptions', $payload);

        // 3. التحقق من النتائج
        $response->assertStatus(201)
            ->assertJsonPath('message', 'Diagnosis Summery created successfully');

        // التأكد من تحديث حالة الموعد إلى 'completed'
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed'
        ]);

        // التأكد من حفظ الروشتة
        $this->assertDatabaseHas('prescriptions', [
            'appointment_id' => $appointment->id,
            'diagnosis' => 'Acute Pharyngitis'
        ]);
    }

    #[Test]
   public function it_fails_to_create_prescription_for_non_existent_appointment()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $payload = [
        'appointment_id'    => 9999,
        'medications'       => ['Test'],
        'diagnosis'         => 'Test',
        'frequency'         => 'Test',
        'duration_days'     => 1,
        'prescription_date' => now()->toDateString(),
    ];

    $response = $this->postJson('/api/doctor/prescriptions', $payload);

    $response->assertStatus(422);
}
}
