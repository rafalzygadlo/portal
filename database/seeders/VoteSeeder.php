<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vote;
use App\Models\Article;


class VoteSeeder extends Seeder
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

        $this->command->info('Generating votes...');
        $articlesCount = Article::count();
        $this->command->getOutput()->progressStart($articlesCount);

        $votes = [];
        $now = now();
        $voteChunkSize = 1000;

        // Fetch user IDs to conserve memory instead of full models
        $userIds = User::limit(1000)->pluck('id');
        if ($userIds->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            $this->command->getOutput()->progressFinish();
            return;
        }
        $userCount = $userIds->count();

        foreach (Article::cursor() as $article)
        {
            // Skip if we don't have enough users to meet the minimum vote count
            if ($userCount < 5) {
                continue;
            }

            // Assign a random number of votes for each article
            $numVotes = rand(5, min(1000, $userCount));
            $voterIds = $userIds->random($numVotes);

            // Ensure voterIds is a collection for consistent processing
            if (!$voterIds instanceof \Illuminate\Support\Collection) {
                $voterIds = collect([$voterIds]);
            }

            foreach ($voterIds as $voterId)
            {
                $votes[] = [
                    'voteable_type' => 'App\\Models\\Article',
                    'voteable_id' => $article->id,
                    'user_id' => $voterId,
                    'value' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($votes) >= $voteChunkSize) {
                    Vote::insert($votes);
                    $votes = [];
                }
            }
            $this->command->getOutput()->progressAdvance();
        }


        if (!empty($votes)) {
            Vote::insert($votes);
        }

        $this->command->getOutput()->progressFinish();
    }
}
