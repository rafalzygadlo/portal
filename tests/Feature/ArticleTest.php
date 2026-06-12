<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_view_articles_index_page()
    {
        Article::factory(3)->create();
        
        $response = $this->get('/articles');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_view_article_show_page()
    {
        $article = Article::factory()->create();
        
        $response = $this->get("/articles/{$article->slug}");
        
        $response->assertStatus(200);
    }
}
