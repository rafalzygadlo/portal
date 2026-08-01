<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubdomainAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_logout_route_is_available_on_subdomain(): void
    {
        $user = User::factory()->create();
        $business = Business::factory()->create([
            'user_id' => $user->id,
            'subdomain' => 'marcin',
        ]);

        $url = 'http://marcin.'.config('app.business_domain').'/logout';

        $response = $this->actingAs($user)->post($url);

        $response->assertRedirect('http://marcin.'.config('app.business_domain').'/');
        $this->assertGuest();
    }

    /** @test */
    public function test_guest_on_subdomain_admin_is_redirected_to_subdomain_login(): void
    {
        $business = Business::factory()->create(['subdomain' => 'marcin']);
        $url = 'http://marcin.'.config('app.business_domain').'/admin/dashboard';

        $response = $this->get($url);

        $response->assertRedirect('http://marcin.'.config('app.business_domain').'/login');
    }
}
