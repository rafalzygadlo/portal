<?php

namespace Tests\Feature;

use App\Livewire\Profile\Article\Create;
use App\Livewire\Profile\Article\Edit;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleProfileFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_create_component_requires_title_and_content(): void
    {
        $user = User::factory()->create();
        Category::factory()->create(['slug' => 'news']);

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('title', 'ab')
            ->set('content', 'short')
            ->set('categories', [])
            ->call('save')
            ->assertHasErrors(['title', 'content', 'categories']);
    }

    public function test_article_edit_component_loads_article_data(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id, 'title' => 'Hello world']);
        $category = Category::factory()->create(['slug' => 'news']);
        $article->categories()->attach($category->id);

        Livewire::actingAs($user)
            ->test(Edit::class, ['article' => $article])
            ->assertSet('title', 'Hello world');
    }
}
