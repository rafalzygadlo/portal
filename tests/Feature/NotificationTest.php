<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_authenticated_user_can_view_notifications()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        Notification::factory(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/notify');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_create_a_notification()
    {
        $user = User::factory()->create();

        $notification = Notification::factory()->create([
            'user_id' => $user->id,
            'type' => 'comment',
            'message' => 'New comment on your article',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'comment',
        ]);
    }

    /** @test */
    public function test_it_can_mark_notification_as_read()
    {
        $user = User::factory()->create();

        $notification = Notification::factory()->create([
            'user_id' => $user->id,
            'read_at' => null,
        ]);

        $notification->update(['read_at' => now()]);

        $this->assertNotNull($notification->read_at);
    }
}
