<?php

namespace Database\Factories;

use App\Models\Announcement\AnnouncementCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnnouncementCategoryFactory extends Factory
{
    protected $model = AnnouncementCategory::class;

    public function definition()
    {
        $name = $this->faker->unique()->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
