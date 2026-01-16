<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Announcement\AnnouncementCategory;

class AnnouncementCategorySeeder extends Seeder
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
            AnnouncementCategory::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );
        }
    }
}
