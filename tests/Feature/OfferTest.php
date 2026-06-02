<?php

namespace Tests\Feature;

use App\Models\Offer\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_view_offers_index_page()
    {
        Offer::factory(3)->create();
        
        $response = $this->get('/offers');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_view_offer_show_page()
    {
        $offer = Offer::factory()->create();
        
        $response = $this->get("/offer/{$offer->slug}");
        
        $response->assertStatus(200);
    }
}
