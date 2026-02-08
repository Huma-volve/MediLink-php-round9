<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingPatientTest extends TestCase
{
    use RefreshDatabase;

    // اختبار عرض الملف الشخصي

    public function test_patient_can_view_profile_info()
    {
        $user = User::factory()->create();
        Patient::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/patient/profile');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200
            ]);
    }

    // اختبار تحديث البيانات

    public function test_patient_can_update_profile_and_picture()
    {
        Storage::fake('public');
        $user = User::factory()->has(Patient::factory())->create();

        $payload = [
            'name' => 'Updated Name',
            'phone' => '0123456789',
            'blood_group' => 'O+',
        ];

        $response = $this->actingAs($user)->postJson('/api/patient/profile/update', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    //اختبار تغيير كلمة المرور

    public function test_patient_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old_password_123')
        ]);

        $payload = [
            'current_password' => 'old_password_123',
            'new_password' => 'new_secret_password',
            'new_password_confirmation' => 'new_secret_password',
        ];

        $response = $this->actingAs($user)->postJson('/api/patient/change-password', $payload);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('new_secret_password', $user->refresh()->password));
    }

    // اختبار سجل المدفوعات

    public function test_patient_can_view_payment_history()
    {
        $user = User::factory()->has(Patient::factory())->create();
        $patient = $user->patient;

        Payment::factory()->create(['patient_id' => $patient->id]);

        $response = $this->actingAs($user)->getJson('/api/patient/paymentHistory');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    // اختبار حذف الحساب

    public function test_patient_can_delete_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/patient/delete-account');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
