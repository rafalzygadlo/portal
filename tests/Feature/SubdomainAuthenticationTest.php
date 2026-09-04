<?php

namespace Tests\Feature;

use App\Models\Company;
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
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'subdomain' => 'marcin',
        ]);

        $url = 'http://marcin.'.config('app.company_domain').'/logout';

        $response = $this->actingAs($user)->post($url);

        $response->assertRedirect('http://marcin.'.config('app.company_domain').'/');
        $this->assertGuest();
    }

    /** @test */
    public function test_guest_on_subdomain_admin_is_redirected_to_subdomain_login(): void
    {
        $company = Company::factory()->create(['subdomain' => 'marcin']);
        $url = 'http://marcin.'.config('app.company_domain').'/admin/dashboard';

        $response = $this->get($url);

        $response->assertRedirect('http://marcin.'.config('app.company_domain').'/login');
    }

    /** @test */
    public function test_company_owner_can_access_subdomain_admin_route_with_subdomain_parameter(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Company::factory()->create([
            'user_id' => $user->id,
            'subdomain' => 'marcin',
        ]);

        $url = 'http://marcin.'.config('app.company_domain').'/admin/dashboard';

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);
    }
}
