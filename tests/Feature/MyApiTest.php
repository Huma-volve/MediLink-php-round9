<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MyApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_get_doctors_api(): void
    {
        $user = User::factory()->create([
            'name' => 'Ahmed'
        ]);

        $this->actingAs($user , 'sanctum');

        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/doctors_search?search=Ahmed');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $doctor->user->name
        ]);
    }


     #[Test]
    public function it_can_get_doctor_working_hours()
    {
        $this->actingAs(User::factory()->create());

        $doctor = Doctor::factory()->create();

        // لو عندك factory لساعات العمل للـ Doctor
        $doctor->workingHours()->create([
            'day_of_week' => 'Monday',
            'opening_time' => '09:00:00',
            'closing_time' => '17:00:00',
            'is_closed' => false
        ]);

        $response = $this->getJson("/api/doctor/{$doctor->id}/doctor-working-hours");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'day_of_week' => 'Monday',
                     'opening_time' => '09:00:00',
                     'closing_time' => '17:00:00'
                 ]);
    }

    #[Test]
    public function it_can_get_doctor_working_hours_online()
    {
        $this->actingAs(User::factory()->create());

        $doctor = Doctor::factory()->create();

        // ساعات العمل Online
        $doctor->workingHoursOnline()->create([
            'day_of_week' => 'Tuesday',
            'opening_time' => '10:00:00',
            'closing_time' => '16:00:00',
            'is_closed' => false,
        ]);

        $response = $this->getJson("/api/doctor/{$doctor->id}/doctor-working-hours_online");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'day_of_week' => 'Tuesday',
                 ]);
    }
}
