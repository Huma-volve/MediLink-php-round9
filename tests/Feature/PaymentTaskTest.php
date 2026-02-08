<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTaskTest extends TestCase
{
    use RefreshDatabase;

    // لإنشاء مستخدم وتسجيل الدخول به

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_payment_initialization_with_doctor()
    {
        $this->authenticate();

        $payment = Payment::factory()->create([
            'amount' => 150.00,
            'payment_status' => 'pending'
        ]);

        $this->assertEquals(150.00, $payment->amount);
    }

    public function test_payment_refund_updates_doctor_balance()
    {
        $this->authenticate();

        $doctor = Doctor::factory()->create(['current_balance' => 500]);
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $payment = Payment::factory()->create([
            'amount' => 100.00,
            'payment_status' => 'completed',
            'appointment_id' => $appointment->id
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/refund");

        $response->assertStatus(200);
        $this->assertEquals('refunded', $payment->fresh()->payment_status);
        $this->assertEquals(400.00, $doctor->fresh()->current_balance);
    }

    public function test_processing_payment_increments_doctor_balance()
    {
        $this->authenticate();

        $doctor = Doctor::factory()->create(['current_balance' => 0]);
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $payment = Payment::factory()->create([
            'amount' => 250.00,
            'payment_status' => 'pending',
            'appointment_id' => $appointment->id
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/process");

        $response->assertStatus(200);
        $this->assertEquals(250.00, $doctor->fresh()->current_balance);
    }

    public function test_cannot_refund_uncompleted_payment()
    {
        $this->authenticate();

        $payment = Payment::factory()->create(['payment_status' => 'pending']);

        $response = $this->postJson("/api/payments/{$payment->id}/refund");

        $response->assertStatus(400);
        $this->assertNotEquals('refunded', $payment->fresh()->payment_status);
    }
}
