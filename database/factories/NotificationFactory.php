<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'from_user_id' => User::factory(),
            'type' => $this->faker->randomElement(['comment', 'vote', 'system']),
            'notifiable_type' => 'App\\Models\\Article',
            'notifiable_id' => 1,
            'message' => $this->faker->sentence(),
            'read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
