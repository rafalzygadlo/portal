<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Business;
use App\Models\Todo;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_many_articles()
    {
        $user = User::factory()->create();

        Article::factory(3)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $user->articles);
    }

    public function test_user_can_have_many_todos()
    {
        $user = User::factory()->create();

        Todo::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(5, $user->todos);
    }

    public function test_user_can_have_many_offers()
    {
        $user = User::factory()->create();

        Offer::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(5, $user->offers);
    }

    public function test_user_can_own_business()
    {
        $user = User::factory()->create();

        $business = Business::factory()->create();
        $business->users()->attach($user);

        $this->assertTrue($user->businesses()->where('business_id', $business->id)->exists());
    }
}