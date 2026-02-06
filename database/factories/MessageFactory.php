<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::inRandomOrder()->first() ?? User::factory()->create();
        $receiver = User::where('id', '!=', $sender->id)->inRandomOrder()->first() ?? User::factory()->create();

        return [
            'sender_id'   => $sender->id,
            'receiver_id' => $receiver->id,
            'message'     => $this->faker->sentence(),
            'is_read'     => $this->faker->boolean(),
        ];
    }
}
