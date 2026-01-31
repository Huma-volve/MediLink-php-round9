<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpelizationTest extends TestCase
{
    public function test_user_can_register()
    {
        // Arrange
        $data = [
            'name' => 'Sara',
            'email' => 'sara@test.com',
            'phone' => '01000000000',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        // Act
        $response = $this->postJson('/api/register', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'sara@test.com'
        ]);
    }
}
