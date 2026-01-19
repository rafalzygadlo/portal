<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Poll::factory()
            ->count(100)
            ->create()
            ->each(function ($poll) {
                PollOption::factory()->count(rand(2, 5))->create([
                    'poll_id' => $poll->id,
                ]);
            });
    }
}