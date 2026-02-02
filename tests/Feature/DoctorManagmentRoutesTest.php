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
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
      
public function test_user_can_toggle_doctor_favorite()
{
    // إنشاء doctor
    $doctor = \App\Models\Doctor::factory()->create();

    // إنشاء user
    $user = \App\Models\User::factory()->create();

    // إنشاء patient مرتبط بالـ user
    $patient = \App\Models\Patient::factory()->create([
        'user_id' => $user->id
    ]);

    // مصادقة المستخدم عبر Sanctum
    \Laravel\Sanctum\Sanctum::actingAs($user);

    // إرسال طلب toggle favorite
    $response = $this->post('/api/doctors/' . $doctor->id . '/favorite');

    // التأكد من الاستجابة
    $response->assertStatus(200);

    // التأكد من شكل JSON
    $response->assertJsonStructure([
        'doctor_id',
        'is_favorite'
    ]);

    // التأكد من تسجيل favorite في قاعدة البيانات
    $this->assertDatabaseHas('favorites', [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'is_favorite' => true
    ]);
}


}
