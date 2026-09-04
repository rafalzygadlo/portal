<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $counter = 0;
        $name = $this->faker->company . ' ' . ++$counter;
        return [
            'user_id' => User::factory(),
            'name' => $name,
            'subdomain' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'website' => $this->faker->url,
            'is_claimed' => $this->faker->boolean(30), // 30% chance of being claimed
            'logo' => $this->faker->imageUrl(400, 400, 'company', true),
        ];
    }
}
