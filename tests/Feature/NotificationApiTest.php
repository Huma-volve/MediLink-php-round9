<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    public function test_user_can_fetch_notifications_with_pagination_using_sanctum()
    {
        $user = User::factory()->create();

        Notification::factory()->count(15)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/user/notifications');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'notifications_count',
                'notifications' => [
                    '*' => [
                        'id',
                        'user_id',
                        'title',
                        'message',
                        'type',
                        'related_type',
                        'related_id',
                        'created_at',
                        'updated_at',
                    ]
                ],
            ],
        ]);

        $notifications = $response->json('data.notifications');
        $this->assertCount(10, $notifications);

        foreach ($notifications as $notification) {
            $this->assertEquals($user->id, $notification['user_id']);
        }
    }



    public function test_user_can_mark_notification_as_read_using_sanctum()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $notification = Notification::factory()->create([
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        $response = $this->postJson("/api/notification/read/{$notification->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }
}
