<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Generating articles...');
        $articlesCount = 10000;
        $chunkSize = 100;
        $this->command->getOutput()->progressStart($articlesCount);

        // Fetch a larger sample of users to be authors
        $users = \App\Models\User::limit(1000)->get();
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $articlesData = [];
        $now = now();

        for ($i = 0; $i < $articlesCount; $i++) {
            $articlesData[] = \App\Models\Article\Article::factory()->raw([
                'user_id' => $users->random()->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if (count($articlesData) >= $chunkSize) {
                \App\Models\Article\Article::insert($articlesData);
                $articlesData = [];
                $this->command->getOutput()->progressAdvance($chunkSize);
            }
        }

        if (!empty($articlesData)) {
            \App\Models\Article\Article::insert($articlesData);
        }

        $this->command->getOutput()->progressFinish();
    }
}
