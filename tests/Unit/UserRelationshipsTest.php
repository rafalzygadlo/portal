<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Article;
use App\Models\Business;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_have_many_articles()
    {
        $user = User::factory()->create();

        Article::factory(3)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $user->articles);
    }

    /** @test */
    public function user_can_have_many_todos()
    {
        $user = User::factory()->create();

        Todo::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(5, $user->todos);
    }

    /** @test */
    public function user_can_own_business()
    {
        $user = User::factory()->create();

        $business = Business::factory()->create();
        $business->users()->attach($user);

        $this->assertTrue($user->businesses()->where('business_id', $business->id)->exists());
    }

    /** @test */
    public function user_has_full_name()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $user->name);
    }
}
