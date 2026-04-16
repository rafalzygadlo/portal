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
        // Define categories by type
        $categories = [
            'business' => [
                'Biznes',
                'Technologia',
                'Motoryzacja',
                'Programowanie',
                'Real Estate',
                'Sales'
            ],
            'article' => [
                'News',
                'Problemy',
                'Lifestyle',
                'Zdrowie',
                'Travel',
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

        // Use insertOrIgnore to avoid errors when running the seeder again
        DB::table('categories')->insertOrIgnore($data);
    }
}