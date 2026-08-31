<?php

namespace Tests\Unit\Traits;

use App\Models\Article;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteableTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_score_from_votes(): void
    {
        $article = Article::factory()->create();

        Vote::factory()->create([
            'user_id' => User::factory(),
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => 1,
        ]);

        Vote::factory()->create([
            'user_id' => User::factory(),
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => 1,
        ]);

        Vote::factory()->create([
            'user_id' => User::factory(),
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => -1,
        ]);

        $this->assertSame(1, $article->fresh()->getScore());
    }

    public function test_it_uses_eager_loaded_vote_sum_when_available(): void
    {
        $article = Article::factory()->create();
        $article->votes_sum_value = 42;

        $this->assertSame(42, $article->getScore());
    }

    public function test_upvotes_and_downvotes_scopes(): void
    {
        $article = Article::factory()->create();

        Vote::factory()->create([
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => 1,
        ]);

        Vote::factory()->create([
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => -1,
        ]);

        $this->assertCount(1, $article->upvotes);
        $this->assertCount(1, $article->downvotes);
    }
}
