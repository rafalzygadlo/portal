<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_a_successful_response_for_the_homepage()
    {
        Business::factory()->create();
        // 1. Visit the landing page
        $this->withoutExceptionHandling();

        $response = $this->get('/');
        
        dump($response->status());
        // 2. Assert the status code is 200 (OK)
        $response->assertStatus(200);
        
        // 3. Optional: Assert that the page contains specific text
        // $response->assertSee('Welcome'); 
    }

    /** @test */
    public function it_should_allow_a_user_to_see_the_login_page()
    {
        // Change '/login' to your actual login route
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }
}