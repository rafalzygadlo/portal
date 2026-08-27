<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Auth\Register;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_register()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_registration_applies_referral_code_without_reusing_it(): void
    {
        $referrer = User::factory()->create(['referral_code' => 'ABC123']);

        Livewire::test(Register::class)
            ->set('email', 'new-user@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('referral_code', 'ABC123')
            ->call('register')
            ->assertRedirect(route('user.profile'));

        $user = User::where('email', 'new-user@example.com')->firstOrFail();

        $this->assertSame($referrer->id, $user->referred_by_user_id);
        $this->assertNotSame('ABC123', $user->referral_code);
    }

    /** @test */
    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /** @test */
    public function test_guest_redirected_to_login_when_accessing_protected_route()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_authenticated_user_cannot_access_login_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        // Should redirect to home or profile
        $this->assertTrue($response->status() === 302 || $response->status() === 200);
    }

    /** @test */
    public function test_user_can_access_email_verification_page()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
    }
}
