<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_a_user_to_create_an_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->make();

        $response = $this->post('/articles', $article->toArray());

        $this->assertDatabaseHas('articles', [
            'title' => $article->title,
            'content' => $article->content,
        ]);
    }
}
