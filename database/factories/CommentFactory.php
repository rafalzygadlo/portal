<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'user_id' => null, // Set this in the test when creating a comment
            'parent_id' => null, // For replies, set this to the parent comment's ID
            'commentable_id' => null, // Set this in the test when creating a comment
            'commentable_type' => null, // Set this in the test when creating a comment
            //
        ];
    }
}
