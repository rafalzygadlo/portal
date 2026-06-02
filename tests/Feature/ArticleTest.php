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
    public function it_can_view_articles_index_page()
    {
        Article::factory(3)->create();
        
        $response = $this->get('/articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_view_article_show_page()
    {
        $article = Article::factory()->create();
        
        $response = $this->get("/articles/{$article->id}");
        
        $response->assertStatus(200);
    }
}
