<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Offer\Category;

class OfferCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Praca',
            'Nieruchomości',
            'Motoryzacja',
            'Usługi',
            'Elektronika',
            'Dom i Ogród',
            'Sport i Hobby',
            'Oddam za darmo',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );
        }
    }
}
