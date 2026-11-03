<?php

namespace Tests\Feature;

use App\Features\Auth\Models\User;
use App\Features\Tasks\Models\Task;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TaskDueDateTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_task_list_displays_due_dates_in_order_with_unset_dates_last(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['creator_id' => $user->id, 'title' => 'No deadline', 'dueDate' => null]);
        Task::factory()->create(['creator_id' => $user->id, 'title' => 'Later deadline', 'dueDate' => '2027-02-01']);
        Task::factory()->create(['creator_id' => $user->id, 'title' => 'Earlier deadline', 'dueDate' => '2027-01-01']);

        $this->actingAs($user)->get(route('tasks.index'))
            ->assertOk()
            ->assertSeeInOrder(['Earlier deadline', '2027-01-01', 'Later deadline', '2027-02-01', 'No deadline', 'Not set']);
    }

    public function test_task_details_display_the_due_date(): void
    {
        $task = Task::factory()->create(['dueDate' => '2027-01-01']);

        $this->actingAs($task->creator)->get(route('tasks.show', ['task_id' => $task->id]))
            ->assertOk()
            ->assertSeeInOrder(['Due Date', '2027-01-01']);
    }

    public function test_task_details_display_when_the_due_date_is_unset(): void
    {
        $task = Task::factory()->create(['dueDate' => null]);

        $this->actingAs($task->creator)->get(route('tasks.show', ['task_id' => $task->id]))
            ->assertOk()
            ->assertSeeInOrder(['Due Date', 'Not set']);
    }
}
