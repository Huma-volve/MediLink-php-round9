<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Doctor;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class DoctorManagmentRoutesTest extends TestCase
{
       use RefreshDatabase;


    public function test_it_returns_doctors_list()
    {

        Doctor::factory()->count(3)->create();


        $response = $this->get('/api/doctors');


        $response->assertStatus(200);
    }
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

public function test_user_can_toggle_doctor_favorite()
{

    $doctor = \App\Models\Doctor::factory()->create();


    $user = \App\Models\User::factory()->create();


    $patient = \App\Models\Patient::factory()->create([
        'user_id' => $user->id
    ]);


    \Laravel\Sanctum\Sanctum::actingAs($user);

    $response = $this->post('/api/doctors/' . $doctor->id . '/favorite');


    $response->assertStatus(200);


    $response->assertJsonStructure([
        'doctor_id',
        'is_favorite'
    ]);


    $this->assertDatabaseHas('favorites', [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'is_favorite' => true
    ]);
}


}
