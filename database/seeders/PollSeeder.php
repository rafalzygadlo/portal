<?php

namespace Database\Seeders;

use App\Models\Poll\Poll;
use App\Models\Poll\PollOption;
use Database\Factories\Poll\PollFactory;
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
        // Disable query log to save memory on long running commands
        \Illuminate\Support\Facades\DB::disableQueryLog();

        $this->command->info('Generating polls...');
        $pollsCount = 20;
        $this->command->getOutput()->progressStart($pollsCount);

        $now = now();

        for ($i = 0; $i < $pollsCount; $i++) {
            $poll = Poll::factory()->create([
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create poll options
            PollOption::factory()->count(rand(2, 5))->create([
                'poll_id' => $poll->id,
            ]);

            $this->command->getOutput()->progressAdvance(1);
        }

        $this->command->getOutput()->progressFinish();
    }
}
