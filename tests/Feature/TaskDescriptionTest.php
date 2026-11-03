<?php

namespace Tests\Feature;

use App\Features\Tasks\Models\Task;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TaskDescriptionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_details_show_a_toggle_when_a_long_description_exists(): void
    {
        $task = Task::factory()->create(['description' => 'Short description', 'longDescription' => 'Long description']);

        $this->actingAs($task->creator)->get(route('tasks.show', ['task_id' => $task->id]))
            ->assertOk()
            ->assertSeeInOrder(['Short description', 'Toggle long description', 'Long description']);
    }

    public function test_details_do_not_show_a_toggle_without_a_long_description(): void
    {
        $task = Task::factory()->create(['description' => 'Short description', 'longDescription' => null]);

        $this->actingAs($task->creator)->get(route('tasks.show', ['task_id' => $task->id]))
            ->assertOk()
            ->assertSee('Short description')
            ->assertDontSee('Toggle long description');
    }
}
