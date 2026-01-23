<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
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

        $this->command->info('Generating todos...');
        $todosCount = 500;
        $chunkSize = 100;
        $this->command->getOutput()->progressStart($todosCount);

        $todosData = [];
        $now = now();

        for ($i = 0; $i < $todosCount; $i++) {
            $todosData[] = Todo::factory()->raw([
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if (count($todosData) >= $chunkSize) {
                Todo::insert($todosData);
                $this->command->getOutput()->progressAdvance($chunkSize);
                $todosData = [];
            }
        }

        if (!empty($todosData)) {
            Todo::insert($todosData);
            $this->command->getOutput()->progressAdvance(count($todosData));
        }

        $this->command->getOutput()->progressFinish();
    }
}
