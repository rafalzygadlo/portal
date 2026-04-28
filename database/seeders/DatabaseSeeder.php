<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);

        $users = \App\Models\User::all();
        
        
        // Create Articles
        $this->call(ArticleSeeder::class);
        $this->seedVotesForModel(\App\Models\Article\Article::class, $users, 'Articles');
        
        // Create Offers        
        $this->call(OfferSeeder::class);
        $this->seedVotesForModel(\App\Models\Offer\Offer::class, $users, 'Offers');
        
        // Create Businesses with votes
        $this->call(BusinessSeeder::class);
        $this->seedVotesForModel(\App\Models\Business::class, $users, 'Businesses');
        
        // Create Polls with votes
        $this->call(PollSeeder::class);
        $this->seedVotesForModel(\App\Models\Poll\Poll::class, $users, 'Polls');
        $this->seedVotesForPollOptions($users);
        
        // Create Todos with votes
        $this->call(TodoSeeder::class);
        $this->seedVotesForModel(\App\Models\Todo::class, $users, 'Todos');
    }

    /**
     * Seed votes for a given model
     */
    private function seedVotesForModel($modelClass, $users, $modelName)
    {
        $this->command->info("Generating votes for {$modelName}...");
        $itemsCount = $modelClass::count();
        
        if ($itemsCount === 0) {
            $this->command->warn("No {$modelName} found to seed votes");
            return;
        }

        $this->command->getOutput()->progressStart($itemsCount);
        $now = now();
        $voteChunkSize = 50;
        $userIds = $users->pluck('id')->toArray();
        $votes = [];

        // Process items in chunks to avoid loading all at once
        $modelClass::chunk(100, function ($items) use (&$votes, &$voteChunkSize, $now, $modelClass, $userIds) {
            foreach ($items as $item) {
                // Randomize the number of votes for each item (reduced from 450 to 100)
                $numVotes = rand(5, min(100, count($userIds)));
                $voterIds = array_rand($userIds, $numVotes);

                // Ensure voterIds is an array
                if (!is_array($voterIds)) {
                    $voterIds = [$voterIds];
                }

                foreach ($voterIds as $index) {
                    $votes[] = [
                        'voteable_type' => (string)$modelClass,
                        'voteable_id' => $item->id,
                        'user_id' => $userIds[$index],
                        'value' => rand(0, 1) ? 1 : -1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($votes) >= $voteChunkSize) {
                        \App\Models\Vote::insert($votes);
                        $votes = [];
                    }
                }
                $this->command->getOutput()->progressAdvance();
            }
        });

        if (!empty($votes)) {
            \App\Models\Vote::insert($votes);
        }

        $this->command->getOutput()->progressFinish();
    }

    /**
     * Seed votes for poll options
     */
    private function seedVotesForPollOptions($users)
    {
        $this->command->info('Generating votes for poll options...');
        $pollOptionsCount = \App\Models\Poll\PollOption::count();
        
        if ($pollOptionsCount === 0) {
            $this->command->warn('No poll options found to seed votes');
            return;
        }

        $this->command->getOutput()->progressStart($pollOptionsCount);
        $now = now();
        $voteChunkSize = 5000;
        $userIds = $users->pluck('id')->toArray();
        $votes = [];

        // Process poll options in chunks to avoid loading all at once
        \App\Models\Poll\PollOption::chunk(100, function ($pollOptions) use (&$votes, &$voteChunkSize, $now, $userIds) {
            foreach ($pollOptions as $option) {
                // Randomize the number of votes for each option (reduced from 450 to 100)
                $numVotes = rand(5, min(100, count($userIds)));
                $voterIds = array_rand($userIds, $numVotes);
                
                // Ensure voterIds is an array
                if (!is_array($voterIds)) {
                    $voterIds = [$voterIds];
                }

                foreach ($voterIds as $index) {
                    $votes[] = [
                        'voteable_type' => 'App\\Models\\Poll\\PollOption',
                        'voteable_id' => $option->id,
                        'user_id' => $userIds[$index],
                        'value' => 1, // Poll options typically only get upvotes
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($votes) >= $voteChunkSize) {
                        \App\Models\Vote::insert($votes);
                        $votes = [];
                    }
                }
                $this->command->getOutput()->progressAdvance();
            }
        });

        if (!empty($votes)) {
            \App\Models\Vote::insert($votes);
        }

        $this->command->getOutput()->progressFinish();
    }
}
