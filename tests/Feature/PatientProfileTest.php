<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientProfileTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function patient_can_get_their_profile()
    {
        $user = User::factory()->create();

        $patient = Patient::factory()->create([
            'user_id' => $user->id
        ]);

        // 3. authentication
        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/patient/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'patient' => [
                             'id',
                         ]
                     ]
                 ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function returns_404_if_patient_not_found()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/patient/profile');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'patient not found',
                     'data' => null
                 ]);
    }
}
