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
        $this->call(BusinessSeeder::class);
        $this->call(AnnouncementCategorySeeder::class);
        $this->call(AnnouncementSeeder::class);

        //Articles 
        $this->command->info('Generating articles...');        
        $articlesCount = 100;
        $this->command->getOutput()->progressStart($articlesCount);
        
        $users = \App\Models\User::all();
        $articles = \App\Models\Article\Article::factory()->count($articlesCount)->create
        ([
            'user_id' => fn() => $users->random()->id
            
        ]);
              
        //Votes 
        $this->command->info('Generating votes...'); 
        $this->command->getOutput()->progressStart($articlesCount);       
        $votes = [];
        $now = now();

        // Add random votes (thumbs up and down) to all articles
        foreach ($articles as $article) 
        {
            // Randomize the number of votes for a given article (e.g. from 5 to 50)
            $voters = $users->random(rand(5, 90));

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
    }
}
