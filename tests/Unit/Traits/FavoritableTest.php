<?php

namespace Tests\Unit\Traits;

use App\Models\Article;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritableTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_detects_when_user_favorited_item(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        Favorite::factory()->create([
            'user_id' => $user->id,
            'favoritable_id' => $article->id,
            'favoritable_type' => Article::class,
        ]);

        $this->assertTrue($article->isFavoritedBy($user));
        $this->assertTrue($article->isFavoritedBy($user->id));
    }

    public function test_it_returns_false_for_guest(): void
    {
        $article = Article::factory()->create();

        $this->assertFalse($article->isFavoritedBy());
    }

    public function test_favorites_count_attribute(): void
    {
        $article = Article::factory()->create();

        Favorite::factory(3)->create([
            'favoritable_id' => $article->id,
            'favoritable_type' => Article::class,
        ]);

        $this->assertSame(3, $article->favorites_count);
    }
}
