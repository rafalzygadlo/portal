<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'voteable_id' => 1,
            'voteable_type' => 'App\\Models\\Article',
            'value' => $this->faker->randomElement([1, -1]),
        ];
    }
}
