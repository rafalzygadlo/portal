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

        $this->call(CategorySeeder::class);

        // Create more users to have unique votes for a single article
        $this->command->info('Creating users...');
        $this->command->getOutput()->progressStart(9999);

        $usersData = [];
        $usersCount = 9999;
        $password = bcrypt('password'); // Calculate hash once to speed up
        $now = now();

        for ($i = 0; $i < $usersCount ; $i++) {
            $usersData[] = \App\Models\User::factory()->raw([
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->command->getOutput()->progressAdvance();
        }

        foreach (array_chunk($usersData, $usersCount) as $chunk) 
        {
            \App\Models\User::insert($chunk);
        }
        
        $this->command->getOutput()->progressFinish();
        
        //Articles 
        $this->command->info('Generating articles...');        
        $articlesCount = 100;
        $this->command->getOutput()->progressStart($articlesCount);
        
        $users = \App\Models\User::all();
        $articles = \App\Models\Article\Article::factory()->count($articlesCount)->create(
            ['user_id' => fn() => $users->random()->id]
        );
        
        $this->command->getOutput()->progressFinish();
              
        //Votes for Articles
        $this->command->info('Generating votes for articles...'); 
        $this->command->getOutput()->progressStart($articlesCount);       
        $votes = [];
        $now = now();

        // Add random votes (thumbs up and down) to all articles
        foreach ($articles as $article) 
        {
            // Randomize the number of votes for a given article (e.g. from 5 to 50)
            $voters = $users->random(rand(5, 450));

            foreach ($voters as $voter) 
            {
                $votes[] = [
                    'voteable_type' => 'App\\Models\\Article\\Article',
                    'voteable_id' => $article->id,
                    'user_id' => $voter->id,
                    'value' => rand(0, 1) ? 1 : -1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->command->getOutput()->progressAdvance();
        }

        foreach (array_chunk($votes, 9999) as $chunk) {
            \App\Models\Vote::insert($chunk);
        }
        
        $this->command->getOutput()->progressFinish();

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
        $items = $modelClass::all();
        $itemsCount = $items->count();
        
        if ($itemsCount === 0) {
            $this->command->warn("No {$modelName} found to seed votes");
            return;
        }

        $this->command->getOutput()->progressStart($itemsCount);
        $votes = [];
        $now = now();
        $voteChunkSize = 5000;
        $userIds = $users->pluck('id');

        foreach ($items as $item) {
            // Randomize the number of votes for each item
            $numVotes = rand(5, min(450, $userIds->count()));
            $voterIds = $userIds->random($numVotes);

            // Ensure voterIds is a collection for consistent processing
            if (!$voterIds instanceof \Illuminate\Support\Collection) {
                $voterIds = collect([$voterIds]);
            }

            foreach ($voterIds as $voterId) {
                $votes[] = [
                    'voteable_type' => (string)$modelClass,
                    'voteable_id' => $item->id,
                    'user_id' => $voterId,
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
        $pollOptions = \App\Models\Poll\PollOption::all();
        $pollOptionsCount = $pollOptions->count();
        
        if ($pollOptionsCount === 0) {
            $this->command->warn('No poll options found to seed votes');
            return;
        }

        $this->command->getOutput()->progressStart($pollOptionsCount);
        $votes = [];
        $now = now();
        $voteChunkSize = 5000;
        $userIds = $users->pluck('id');

        foreach ($pollOptions as $option) {
            // Randomize the number of votes for each option
            $numVotes = rand(5, min(450, $userIds->count()));
            $voterIds = $userIds->random($numVotes);

            // Ensure voterIds is a collection for consistent processing
            if (!$voterIds instanceof \Illuminate\Support\Collection) {
                $voterIds = collect([$voterIds]);
            }

            foreach ($voterIds as $voterId) {
                $votes[] = [
                    'voteable_type' => 'App\\Models\\Poll\\PollOption',
                    'voteable_id' => $option->id,
                    'user_id' => $voterId,
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

        if (!empty($votes)) {
            \App\Models\Vote::insert($votes);
        }

        $this->command->getOutput()->progressFinish();
    }
}
