<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category()
    {
        $category = Category::factory()->create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Technology',
            'slug' => 'technology',
        ]);
    }

    /** @test */
    public function it_can_have_subcategories()
    {
        $parentCategory = Category::factory()->create([
            'name' => 'Technology',
        ]);

        $childCategory = Category::factory()->create([
            'name' => 'Laravel',
            'parent_id' => $parentCategory->id,
        ]);

        $this->assertEquals($parentCategory->id, $childCategory->parent_id);
    }

    /** @test */
    public function it_can_get_articles_by_category()
    {
        $category = Category::factory()->create();

        $articles = Article::factory(3)->create();
        $articles->each(function ($article) use ($category) {
            $article->categories()->attach($category);
        });

        $this->assertCount(3, $category->articles);
    }
}
