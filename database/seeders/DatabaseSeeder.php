<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(CategorySeeder::class);

         \App\Models\User::create([
            'first_name' => 'demo',
            'last_name' => 'demo',
            'email' => 'demo@example.com',
            'password' => bcrypt('demo')
        ]);


        // Create more users to have unique votes for a single article
        $this->command->info('Creating users...');
        $this->command->getOutput()->progressStart(1200);

        $usersData = [];
        $password = bcrypt('password'); // Calculate hash once to speed up
        $now = now();

        for ($i = 0; $i < 1200; $i++) {
            $usersData[] = \App\Models\User::factory()->raw([
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->command->getOutput()->progressAdvance();
        }

        foreach (array_chunk($usersData, 1000) as $chunk) {
            \App\Models\User::insert($chunk);
        }
        $this->command->getOutput()->progressFinish();
        $users = \App\Models\User::all();

        $articles = \App\Models\Article\Article::factory()->count(220)->create([
            'user_id' => fn() => $users->random()->id
        ]);

        $this->command->info('Generating votes...');
        $this->command->getOutput()->progressStart($articles->count());

        $votes = [];
        $now = now();

        // Add random votes (thumbs up and down) to all articles
        foreach ($articles as $article) 
        {
            // Randomize the number of votes for a given article (e.g. from 5 to 50)
            $voters = $users->random(rand(5, 250));

            foreach ($voters as $voter) 
            {
                $votes[] = [
                    'votable_type' => 'article',
                    'votable_id' => $article->id,
                    'user_id' => $voter->id,
                    'value' => rand(0, 1) ? 1 : -1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->command->getOutput()->progressAdvance();
        }

        foreach (array_chunk($votes, 10000) as $chunk) {
            \App\Models\Article\Vote::insert($chunk);
        }

        $this->command->getOutput()->progressFinish();
    }
}
