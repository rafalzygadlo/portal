<?php

namespace Database\Seeders;

use App\Models\Announcement\Announcement;
use App\Models\Announcement\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory()->count(10)->create();
        }
        
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(AnnouncementCategorySeeder::class);
            $categories = Category::all();
        }

        Announcement::factory()
            ->count(100)
            ->create([
                'user_id' => fn() => $users->random()->id,
                'announcement_category_id' => fn() => $categories->random()->id,
            ]);
    }
}
