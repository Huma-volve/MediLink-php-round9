<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;


    #[Test]
    public function it_can_create_a_payment()
    {

        $patient = Patient::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = \App\Models\Patient::factory()->create();


        $payload = [
            'patient_id' => $patient->id,
            'amount' => 150.00,
            'payment_method' => 'cash',
            'currency' => 'EGP'
        ];

        $response = $this->postJson('/api/payments/store', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Payment created successfully');

        $this->assertDatabaseHas('payments', [
            'patient_id' => $patient->id,
            'amount' => 150.00
        ]);
    }


    #[Test]
    public function it_can_refund_a_completed_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // إنشاء دفع حالته مكتملة (Completed)
        $payment = Payment::factory()->create([
            'payment_status' => 'completed'
        ]);

        // 2. طلب استرجاع المبلغ (Refund)
        $response = $this->postJson("/api/payments/{$payment->id}/refund");

        $response->assertStatus(200)
            ->assertJsonPath('data.payment_status', 'refunded');
    }

    #[Test]
    public function it_cannot_refund_a_pending_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // إنشاء دفع بحالة 'pending' (وليس completed)
        $payment = Payment::factory()->create([
            'payment_status' => 'pending'
        ]);

        $response = $this->postJson("/api/payments/{$payment->id}/refund");

        $response->assertStatus(400)
            ->assertJsonPath('message', 'Only completed payments can be refunded');
    }

    #[Test]
    public function it_can_delete_a_payment()
    {
        $user =  User::factory()->create();
        $this->actingAs($user);

        $payment = Payment::factory()->create();

        $response = $this->deleteJson("/api/payments/delete/{$payment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    #[Test]
    // returns 404 when deleting not existent payment
    public function it_returns_404_when_deleting_non_existent_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/payments/delete/999");

        $response->assertStatus(404)
            ->assertJsonPath('message', 'Payment not found');
    }
}
