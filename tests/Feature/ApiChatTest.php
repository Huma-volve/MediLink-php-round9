<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiChatTest extends TestCase
{
    // protected $sender;
    // protected $receiver;

    //  protected function setUp(): void
    // {
    //     parent::setUp();

    //     // إنشاء مستخدمين
    //     $this->sender = User::factory()->create();
    //     $this->receiver = User::factory()->create();
    // }

    /**
     * A basic feature test example.
     */

    #[Test]
    public function user_create_message(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $response = $this->actingAs($sender, 'sanctum')->postJson('/api/chat/send', [
            'receiver_id' => $receiver->id,
            'message' => 'Hello World',
        ]);


        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => 'Hello World'
        ]);
    }

    #[Test]
    public function user_view_messages()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        Message::factory()->create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => 'Test',
        ]);

        $response = $this->actingAs($sender, 'sanctum')->getJson("/api/chat/{$receiver->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                     'status',
                     'message',
                     'data'
            ]);

        $this->assertEquals(1, count($response->json('data')));
    }

    #[Test]
    public function mark_as_read()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        Message::factory()->create([
            'sender_id' => $receiver->id,
            'receiver_id' => $sender->id,
            'message' => 'unread messages',
            'is_read' => false,
        ]);

        $response = $this->actingAs($sender , 'sanctum')->postJson("/api/chat/read/{$receiver->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $receiver->id,
            'receiver_id' => $sender->id,
            'message' => 'unread messages',
            'is_read' => true
        ]);
    }


    #[Test]
    public function count_unread_messages()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        Message::factory()->create([
            'sender_id' => $receiver->id,
            'receiver_id' => $sender->id,
            'is_read' => false,
        ]);

        $response = $this->actingAs($sender , 'sanctum')->getJson("/api/chat/count_unread_messages/{$receiver->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'count unread messages',
                'data' => 1,
            ]);
    }
}
