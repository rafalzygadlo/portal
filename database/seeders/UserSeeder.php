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
        
        $usersCount = 10000;    
        $chunkSize = 100;
    
        $this->command->info('Creating users...');
        
        \App\Models\User::create([
            'first_name' => ' Demo',
            'last_name' => 'User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        
        $this->command->getOutput()->progressStart($usersCount);

        $usersData = [];
        
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
