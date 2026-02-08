<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorProfileTest extends TestCase
{
    use RefreshDatabase;

    
    public function it_returns_doctor_profile_if_authenticated_and_exists()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/doctor/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'doctor' => [
                             'id',
                             'user_id',
                             'specialization_id',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);
    }

    
    public function it_returns_404_if_doctor_not_found_for_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/doctor/profile');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'doctor not found',
                     'data' => null
                 ]);
    }

    
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/doctor/profile');

        $response->assertStatus(401); 
    }
}
