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


        \App\Models\User::factory()->count(100)->create();

        \App\Models\Article\Article::factory()->count(100)->create([
            'user_id' => fn() => \App\Models\User::inRandomOrder()->first()->id
        ]);
    }
}
