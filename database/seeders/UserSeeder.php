<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating users...');
        $usersCount = 100000;
        $this->command->getOutput()->progressStart($usersCount);

        $usersData = [];
        $chunkSize = 1000;
        $password = bcrypt('password');
        $now = now();

        for ($i = 0; $i < $usersCount ; $i++) {
            $usersData[] = \App\Models\User::factory()->raw([
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if (count($usersData) >= $chunkSize) {
                \App\Models\User::insert($usersData);
                $usersData = [];
                $this->command->getOutput()->progressAdvance($chunkSize);
            }
        }
        
        if (!empty($usersData)) {
            \App\Models\User::insert($usersData);
        }
        
        $this->command->getOutput()->progressFinish();
    }
}
