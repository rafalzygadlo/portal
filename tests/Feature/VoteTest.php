<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_upvote_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => 1, // upvote
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'voteable_id' => $article->id,
            'value' => 1,
        ]);
    }

    /** @test */
    public function test_it_can_downvote_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => -1, // downvote
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'value' => -1,
        ]);
    }

    /** @test */
    public function test_it_can_get_vote_count_for_article()
    {
        $article = Article::factory()->create();
        
        Vote::factory(5)->create([
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => 1,
        ]);

        Vote::factory(2)->create([
            'voteable_id' => $article->id,
            'voteable_type' => Article::class,
            'value' => -1,
        ]);

        $votes = $article->votes;

        $this->assertCount(7, $votes);
    }
}
