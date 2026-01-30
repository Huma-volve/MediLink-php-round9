<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => \App\Models\Patient::factory(),
            'amount' => fake()->randomFloat(2, 10, 500),
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'currency' => 'EGP',
            'transaction_id' => 'TXN-' . strtoupper(fake()->bothify('??#?###?')),
            'payment_date' => now(),
        ];
    }
}
