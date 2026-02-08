<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientProfileTest extends TestCase
{
    use RefreshDatabase;

    
    public function it_returns_patient_profile_if_authenticated_and_exists()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/patient/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'patient' => [
                             'id',
                             'user_id',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);
    }

    
    public function it_returns_404_if_patient_not_found_for_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/patient/profile');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'patient not found',
                     'data' => null
                 ]);
    }

    
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/patient/profile');

        $response->assertStatus(401); 
    }
}
