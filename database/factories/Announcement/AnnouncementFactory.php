<?php

namespace Database\Factories\Announcement;

use App\Models\Announcement\Announcement;
use App\Models\Announcement\Category;
use App\Models\User;
use Database\Factories\Announcement\AnnouncementCategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'announcement_category_id' => AnnouncementCategoryFactory::new(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
        ];
    }
}

