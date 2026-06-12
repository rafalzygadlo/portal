<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_should_return_a_successful_response_for_the_homepage()
    {
        Business::factory()->create();
        
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_view_business_index_page()
    {
        Business::factory(2)->create();
        
        $response = $this->get('/businesses');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_view_business_show_page()
    {
        $business = Business::factory()->create();
        
        $response = $this->get("/business/{$business->subdomain}");
        
        $response->assertStatus(200);
    }
}
