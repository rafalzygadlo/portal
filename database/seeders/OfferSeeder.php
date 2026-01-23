<?php

namespace Database\Seeders;

use App\Models\Offer\Offer;
use Illuminate\Database\Seeder;

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
        
        $categories = \App\Models\Offer\Category::all();
        if ($categories->isEmpty()) {
            $this->call(OfferCategorySeeder::class);
            $categories = \App\Models\Offer\Category::all();
        }

        Offer::factory()
            ->count(100)
            ->create([
                'user_id' => fn() => $users->random()->id,
                'offer_category_id' => fn() => $categories->random()->id,
            ]);
    }
}
