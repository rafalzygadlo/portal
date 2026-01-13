<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definiujemy kategorie z podziałem na typy
        $categories = [
            'business' => [
                'Biznes',
                'Technologia',
                'Motoryzacja',
                'Programowanie',
                'Nieruchomości',
                'Sprzedaż'
            ],
            'article' => [
                'Aktualności',
                'Problemy',
                'Lifestyle',
                'Zdrowie',
                'Podróże',
                'Kulinaria',
                'Sport',
                'Edukacja',
            ]
        ];

        $data = [];
        $now = now();

        foreach ($categories as $type => $categoryList) {
            foreach ($categoryList as $categoryName) {
                $data[] = [
                    'name'       => $categoryName,
                    'slug'       => Str::slug($categoryName),
                    'type'       => $type,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Używamy insertOrIgnore, aby uniknąć błędów przy ponownym uruchamianiu seedera
        DB::table('categories')->insertOrIgnore($data);
    }
}