<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_profile_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        
        $response = $this->actingAs($user)->get('/profile');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_notifications_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        
        $response = $this->actingAs($user)->get('/notify');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_notifications_page()
    {
        $response = $this->get('/notify');
        
        $response->assertRedirect('/login');
    }
}
