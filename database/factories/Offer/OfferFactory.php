<?php

namespace Database\Factories\Offer;

use App\Models\Offer\Offer;
use App\Models\Offer\Category;
use App\Models\User;
use Database\Factories\Offer\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'offer_category_id' => CategoryFactory::new(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
        ];
    }
}
