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
        $this->call(UserSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(VoteSeeder::class);
        $this->call(BusinessSeeder::class);
    }
}
