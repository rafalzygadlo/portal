<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessIdentificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_identifies_the_business_by_subdomain()
    {
        // 1. Create a User first (because business belongs to a user)
        $user = User::factory()->create();

        // 2. Create the Business assigned to that user
        $business = Business::create([
            'user_id' => $user->id,
            'name' => 'Marcin IT Solutions',
            'subdomain' => 'marcin', // upewnij się, że masz to pole w tabeli business
            'description' => 'Opis naszego biznesu',
            'address' => 'Adres naszego biznesu'
        ]);

        // 3. Act: Visit the subdomain
        $url = 'http://marcin' . config('app.business_domain'); // upewnij się, że masz ustawioną domenę w .env
        $response = $this->get($url);

        // 4. Assert
        $response->assertStatus(200);
        $response->assertSee('Marcin IT Solutions');
    }

    /** @test */
    public function it_returns_404_for_non_existent_subdomains()
    {
        $url = 'http://fake-subdomain' . config('app.business_domain');
        $response = $this->get($url);

        $response->assertStatus(404);
    }
}