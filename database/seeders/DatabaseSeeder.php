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


        // Tworzymy więcej użytkowników, aby mieć unikalne głosy dla jednego artykułu
        $users = \App\Models\User::factory()->count(200)->create();

        $articles = \App\Models\Article\Article::factory()->count(20)->create([
            'user_id' => fn() => $users->random()->id
        ]);

        // Dodajemy losowe głosy (kciuki w górę i w dół) do wszystkich artykułów
        foreach ($articles as $article) {
            // Losujemy liczbę głosów dla danego artykułu (np. od 5 do 50)
            $voters = $users->random(rand(5, 50));

            foreach ($voters as $voter) {
                \App\Models\Article\Vote::factory()->create([
                    'article_id' => $article->id,
                    'user_id' => $voter->id,
                    // Nie wymuszamy 'value', więc fabryka wylosuje 1 lub -1
                ]);
            }
        }
    }
}
