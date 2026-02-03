<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrescriptionApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_prescription_successfully()
    {
        //  إنشاء طبيب ومستخدم مرتبط به لتجاوز الاوثنتكيشن
        $user = User::factory()->create(['role' => 'doctor']);
        $doctor = Doctor::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        // إنشاء موعد مرتبط بهذا الطبيب تحديداً
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'status' => 'pending'
        ]);

        $payload = [
            'appointment_id'    => $appointment->id,
            'medications'       => ['Panadol', 'Antibiotic'],
            'diagnosis'         => 'Acute Pharyngitis',
            'frequency'         => '3 times daily',
            'duration_days'     => 7,
            'prescription_date' => now()->toDateString(),
        ];

        //  تنفيذ الطلب
        $response = $this->postJson('/api/doctor/prescriptions', $payload);

        //  التحقق من النتائج
        $response->assertStatus(201)
            ->assertJsonPath('message', 'Diagnosis summary created successfully');

        // التأكد من تحديث حالة الموعد إلى 'completed'
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed'
        ]);

        // التأكد من حفظ الروشتة في الداتابيز
        $this->assertDatabaseHas('prescriptions', [
            'appointment_id' => $appointment->id,
            'diagnosis' => 'Acute Pharyngitis'
        ]);
    }

    #[Test]
    public function it_fails_to_create_prescription_for_non_existent_appointment()
    {
        $user = User::factory()->create(['role' => 'doctor']);
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

        // يجب أن يعيد 422 بسبب الفلديشن
        $response->assertStatus(422);
    }
}
