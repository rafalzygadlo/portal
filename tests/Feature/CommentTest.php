<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_create_a_comment_on_article()
    {
        // 1. Arrange
        $user = User::factory()->create();
        $article = Article::factory()->create();

        // 2. Act: Zamiast Comment::factory()->create(), 
        // używamy relacji z artykułu. Laravel sam zajmie się polimorfiką.
        $comment = $article->comments()->create([
            'content' => 'Testowy komentarz',
            'user_id' => $user->id,
        ]);

        // 3. Assert
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'commentable_id' => $article->id,
            'commentable_type' => "article", 
        ]);
    }

    /** @test */
    public function test_it_can_get_comments_for_article()
    {
        $article = Article::factory()->create();
        // Tworzenie przez relację automatycznie ustawia commentable_id i commentable_type
        $article->comments()->saveMany(
            Comment::factory(2)->make([
                'user_id' => User::factory()->create()->id,
            ])
        );

        $this->assertCount(2, $article->comments);
    }

    /** @test */
    public function test_it_can_reply_to_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        
        $parentComment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $reply = Comment::factory()->create([
            'parent_id' => $parentComment->id,
            'user_id' => $user->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $this->assertEquals($parentComment->id, $reply->parent_id);
    }
}
