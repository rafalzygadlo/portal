<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(), // Domyślnie tworzy nowy biznes, jeśli ID nie zostanie podane
            'name' => fake()->name(),
            'type' => fake()->randomElement(['person', 'equipment', 'room']),
            'user_id' => null, // Może zostać nadpisane
            'is_active' => true,
        ];
    }
}
