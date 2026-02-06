<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WithdrawalApiTest extends TestCase
{
    use RefreshDatabase;

    
    public function it_returns_doctor_withdrawals_if_exist()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create(['user_id' => $user->id]);
        
        Withdrawal::factory()->create([
            'doctor_id' => $doctor->id,
            'amount' => 100,
            'processed_at' => now()->subDay(),
        ]);
        Withdrawal::factory()->create([
            'doctor_id' => $doctor->id,
            'amount' => 200,
            'processed_at' => now(),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/doctor/withdrawals');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
             'data' => [
                 'doctor_withdrawals' => [
                     '*' => [
                         'id',
                         'amount',
                         'status',
                         'admin_notes',
                         'processed_at',
                         'doctor_name'
                     ]
                 ],
                 ]]);

        $data = $response->json('data.doctor_withdrawals');
        $this->assertEquals(200, $data[0]['amount']);
        $this->assertEquals(100, $data[1]['amount']);
    }

    
    public function it_returns_empty_array_if_no_withdrawals()
    {
        $user = User::factory()->create();
        Doctor::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/doctor/withdrawals');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => null,
                     'data' => []
                 ]);
    }

    
    public function it_returns_doctor_current_balance()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'current_balance' => 500
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/doctor/withdrawals/balance');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => null,
                     'data' => 500
                 ]);
    }

    
    public function it_creates_withdrawal_if_amount_is_valid()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'current_balance' => 500
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/doctor/withdrawals', [
            'amount' => 300
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Withdrawal request submitted successfully',
                     'data' => null
                 ]);

        $this->assertDatabaseHas('withdrawals', [
            'doctor_id' => $doctor->id,
            'amount' => 300
        ]);
    }

    
    public function it_fails_if_amount_exceeds_current_balance()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'current_balance' => 100
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/doctor/withdrawals', [
            'amount' => 200
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'status' => 400,
                     'message' => 'Your current balance is not sufficient for this withdrawal',
                     'data' => null
                 ]);
    }

    
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/doctor/withdrawals');
        $response->assertStatus(401);

        $response = $this->getJson('/api/doctor/withdrawals/balance');
        $response->assertStatus(401);

        $response = $this->postJson('/api/doctor/withdrawals', ['amount' => 100]);
        $response->assertStatus(401);
    }
}
