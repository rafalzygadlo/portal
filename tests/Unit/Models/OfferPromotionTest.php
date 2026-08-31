<?php

namespace Tests\Unit\Models;

use App\Models\Offer;
use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferPromotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_promoted_returns_true_for_active_promotion(): void
    {
        $offer = Offer::factory()->create();

        Promotion::factory()->create([
            'promotable_id' => $offer->id,
            'promotable_type' => Offer::class,
            'expires_at' => now()->addDays(7),
        ]);

        $this->assertTrue($offer->isPromoted());
    }

    public function test_is_promoted_returns_false_for_expired_promotion(): void
    {
        $offer = Offer::factory()->create();

        Promotion::factory()->expired()->create([
            'promotable_id' => $offer->id,
            'promotable_type' => Offer::class,
        ]);

        $this->assertFalse($offer->isPromoted());
    }

    public function test_is_promoted_returns_false_without_promotion(): void
    {
        $offer = Offer::factory()->create();

        $this->assertFalse($offer->isPromoted());
    }
}
