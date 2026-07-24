<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * en: User is redirected to the homepage after logging in without an intended URL.
     * de: Der Benutzer wird nach dem Anmelden ohne beabsichtigte URL zur Startseite weitergeleitet.
     */
    public function user_is_redirected_to_the_homepage_after_logging_in_without_an_intended_url(): void
    {
        // 1. Arrange: Create a user
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        // 2. Act: Simulate a login attempt via the Livewire component
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/'); // 3. Assert: Check redirection to the fallback URL ('/')

        // 4. Assert: Check if the user is authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * en: User is redirected to the intended URL after logging in.
     * de: Der Benutzer wird nach dem Anmelden zur beabsichtigten URL weitergeleitet.
     */
    public function user_is_redirected_to_the_intended_url_after_logging_in(): void
    {
        // 1. Arrange: Create a user
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        // 2. Act: Try to access a protected page, then log in
        $this->get('/profile')
            ->assertRedirect('/login'); // First, assert redirection to login

        Livewire::withQueryParams() // This ensures Livewire sees the 'redirect_to' query parameter
            ->test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/profile'); // 3. Assert: Check redirection to the intended URL
    }
}