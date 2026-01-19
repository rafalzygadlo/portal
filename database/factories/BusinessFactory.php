<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->company;
        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_approved' => $this->faker->boolean,
        ];
    }
}
