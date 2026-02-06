<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        // تأكد من وجود Patient و Doctor صالحين
        $patient = Patient::factory()->create();
        $doctor = User::factory()->create(['role' => 'doctor']);

        return [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $this->faker->date('Y-m-d'),
            'appointment_time' => $this->faker->time('H:i:s'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled', 'paid']),
            'reason_for_visit' => $this->faker->sentence(),
            'consultation_type' => $this->faker->randomElement(['in_person', 'online']),
        ];
    }
}
