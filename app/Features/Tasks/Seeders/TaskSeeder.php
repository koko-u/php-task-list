<?php

namespace App\Features\Tasks\Seeders;

use App\Features\Auth\Models\User;
use App\Features\Tasks\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aoki = User::where('email', 'aoki@example.com')->firstOrFail();
        $baba = User::where('email', 'baba@example.com')->firstOrFail();
        $chiura = User::where('email', 'chiura@example.com')->firstOrFail();

        Task::factory()->count(4)->create([
            'creator_id' => $aoki->id,
            'assignee_id' => $baba->id,
        ]);

        Task::factory()->count(3)->create([
            'creator_id' => $baba->id,
            'assignee_id' => $chiura->id,
        ]);

        Task::factory()->count(2)->create([
            'creator_id' => $baba->id,
            'assignee_id' => $baba->id,
        ]);

        Task::factory()->create([
            'creator_id' => $baba->id,
            'assignee_id' => null,
        ]);
    }
}
