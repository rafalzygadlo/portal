<?php

namespace Database\Factories;

use App\Models\Company;
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
            'company_id' => Company::factory(), // By default creates a new company if no ID is provided
            'name' => fake()->name(),
            'type' => fake()->randomElement(['person', 'equipment', 'room']),
            'user_id' => null, // Can be overridden
            'is_active' => true,
        ];
    }
}
