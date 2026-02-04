<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Doctor;

class MedicalHistoryFactory extends Factory
{
    protected $model = \App\Models\MedicalHistory::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'prescription_id' => null, // لو تحب تضيف prescription لاحقًا
            'chronic_conditions' => $this->faker->word,
            'allergies' => $this->faker->word,
            'previous_surgeries' => $this->faker->word,
        ];
    }
}


