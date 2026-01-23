<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable query log to save memory on long running commands
        \Illuminate\Support\Facades\DB::disableQueryLog();

        $this->command->info('Generating businesses...');
        $businessesCount = 20;
        $this->command->getOutput()->progressStart($businessesCount);

        $now = now();

        for ($i = 0; $i < $businessesCount; $i++) {
            Business::factory()->create([
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->command->getOutput()->progressAdvance(1);
        }

        $this->command->getOutput()->progressFinish();
    }
}


