<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Feed;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_view_includes_created_articles(): void
    {
        $article = Article::factory()->create(['title' => 'Feed Article']);

        $feedItem = Feed::query()->where('type', 'article')->where('item_id', $article->id)->first();

        $this->assertNotNull($feedItem);
        $this->assertSame('Feed Article', $feedItem->title);
    }

    public function test_feed_item_accessor_loads_related_model(): void
    {
        $offer = Offer::factory()->create(['title' => 'Feed Offer']);

        $feedItem = Feed::query()->where('type', 'offer')->where('item_id', $offer->id)->first();

        $this->assertNotNull($feedItem);
        $this->assertInstanceOf(Offer::class, $feedItem->item);
        $this->assertSame('Feed Offer', $feedItem->item->title);
    }

    public function test_feed_marks_promoted_items(): void
    {
        $offer = Offer::factory()->create();

        $offer->promotions()->create(['expires_at' => now()->addDays(7)]);

        $feedItem = Feed::query()->where('type', 'offer')->where('item_id', $offer->id)->first();

        $this->assertTrue((bool) $feedItem->is_promoted);
    }
}
