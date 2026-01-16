<?php

namespace Database\Factories;

use App\Models\Announcement\Announcement;
use App\Models\Announcement\AnnouncementCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'announcement_category_id' => AnnouncementCategory::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
        ];
    }
}
