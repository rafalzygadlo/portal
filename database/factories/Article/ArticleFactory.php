<?php

namespace Database\Factories\Article;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article\Article;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'image_path' => null,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
