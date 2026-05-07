<?php

namespace Database\Seeders;

use App\Models\Offer\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::all();
        if ($users->isEmpty()) {
            $users = \App\Models\User::factory()->count(10)->create();
        }
        
        $categories = \App\Models\Category::all();
        Offer::factory()
            ->count(100)
            ->create(function () use ($users) {
                // Define random creation date and a subsequent update date
                $createdAt = fake()->dateTimeBetween('-2 months', 'now');
                $title = fake()->sentence(5);
                
                return [
                    'user_id' => $users->random()->id,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'created_at' => $createdAt,
                    'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                ];
            })
            ->each(function (Offer $offer) use ($categories) {
                // Attach categories after the model is created and saved
                if ($categories->isNotEmpty()) {
                    $numberOfCategories = rand(1, min(3, $categories->count()));
                    $randomCategories = $categories->random($numberOfCategories);
                    $offer->categories()->attach($randomCategories->pluck('id')->toArray());
                }
            });
    }
}
