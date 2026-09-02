<?php

namespace Tests\Feature;

use App\Livewire\Feed\Index;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_shows_load_more_when_another_page_exists(): void
    {
        Offer::factory(11)->create();

        Livewire::test(Index::class)
            ->assertSet('perPage', 10)
            ->assertSet('hasMore', true)
            ->assertSee('Załaduj więcej');
    }

    public function test_feed_hides_load_more_on_the_last_page(): void
    {
        Offer::factory(10)->create();

        Livewire::test(Index::class)
            ->assertSet('hasMore', false)
            ->assertDontSee('Załaduj więcej');
    }

    public function test_feed_load_more_increases_the_page_size(): void
    {
        Offer::factory(11)->create();

        Livewire::test(Index::class)
            ->call('loadMore')
            ->assertSet('perPage', 20)
            ->assertSet('hasMore', false)
            ->assertDontSee('Załaduj więcej');
    }
}