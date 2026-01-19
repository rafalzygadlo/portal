<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement\Announcement;
use App\Models\User;
use App\Models\Announcement\AnnouncementCategory;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = AnnouncementCategory::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Cannot seed announcements without users and categories.');
            return;
        }

        Announcement::factory()
            ->count(20)
            ->for($users->random())
            ->for($categories->random())
            ->create();
    }
}
