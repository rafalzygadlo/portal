<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_soft_delete_stores_deletion_reason(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => User::factory(),
            'commentable_id' => Article::factory(),
            'commentable_type' => Article::class,
        ]);

        $comment->delete('Usunięte przez autora');

        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
        $this->assertSame('Usunięte przez autora', $comment->fresh()->deletion_reason);
    }

    public function test_replies_relationship(): void
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $parent = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $reply = Comment::factory()->create([
            'user_id' => $user->id,
            'parent_id' => $parent->id,
            'commentable_id' => $article->id,
            'commentable_type' => Article::class,
        ]);

        $this->assertTrue($parent->replies->contains($reply));
    }
}
