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
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $article->comments()->saveMany(
            Comment::factory(2)->make([
                'user_id' => $user->id,
            ])
        );
        

        $this->assertDatabaseHas('comments', [
            'content' => $comment->content,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function test_it_can_get_comments_for_article()
    {
        $article = Article::factory()->create();
        Comment::factory(2)->create([
            'user_id' => User::factory()->create()->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
            'content' => fake()->paragraph()
        ]);

        $comments = $article->comments;
        // Tworzenie przez relację automatycznie ustawia commentable_id i commentable_type
        $article->comments()->saveMany(
            Comment::factory(2)->make([
                'user_id' => User::factory()->create()->id,
            ])
        );

        $this->assertCount(2, $comments);
        $this->assertCount(2, $article->fresh()->comments);
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
