<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articlesCount = 10000;
        $chunkSize = 10;

        $this->command->info('Generating articles...');
        $this->command->getOutput()->progressStart($articlesCount);


        // Fetch a larger sample of users to be authors
        $users = User::limit(1000)->get();
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $articlesData = [];
        
        for ($i = 0; $i < $articlesCount; $i++) {
            $articlesData[] = Article::factory()->raw([
                'user_id' => $users->random()->id,
                'created_at' => date('Y-m-d H:i:s', random_int(strtotime('-1 year'), strtotime('now'))),
                'updated_at' => date('Y-m-d H:i:s', random_int(strtotime('-1 year'), strtotime('now'))),
            ]);

            if (count($articlesData) >= $chunkSize) {
                Article::insert($articlesData);
                $articlesData = [];
                $this->command->getOutput()->progressAdvance($chunkSize);
            }
        }

        if (!empty($articlesData)) {
            Article::insert($articlesData);
        }

        $this->command->getOutput()->progressFinish();
        
    }
}
