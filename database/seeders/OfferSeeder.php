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
        
        Offer::factory()
            ->count(100)
            ->create(function () use ($users) {
            // Najpierw losujemy datę stworzenia
            $createdAt = rand(strtotime('-2 months'), strtotime('now'));
            echo "Created at: " . date('Y-m-d H:i:s', $createdAt) . "\n";
            // Data aktualizacji musi być >= dacie stworzenia
            $updatedAt = rand($createdAt, strtotime('now'));

            return [
                'user_id' => $users->random()->id,
                'created_at' => date('Y-m-d H:i:s', $createdAt),
                'updated_at' => date('Y-m-d H:i:s', $updatedAt),
        ];
    });
    }
}
