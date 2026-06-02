<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_comment_on_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $comment = Comment::factory()->make([
            'user_id' => $user->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $comment->save();

        $this->assertDatabaseHas('comments', [
            'content' => $comment->content,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_get_comments_for_article()
    {
        $article = Article::factory()->create();
        Comment::factory(5)->create([
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $comments = $article->comments;

        $this->assertCount(5, $comments);
    }

    /** @test */
    public function it_can_reply_to_comment()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        
        $parentComment = Comment::factory()->create([
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $reply = Comment::factory()->create([
            'parent_id' => $parentComment->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $this->assertEquals($parentComment->id, $reply->parent_id);
    }
}
