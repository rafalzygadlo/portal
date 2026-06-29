<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_should_allow_a_user_to_see_the_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /** @test */
    public function test_unauthenticated_user_cannot_access_profile_page()
    {
        $response = $this->get('/profile');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/logout');
        
        $this->assertNull(auth()->user());
    }
}