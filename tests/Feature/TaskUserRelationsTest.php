<?php

namespace Tests\Feature;

use App\Features\Auth\Models\User;
use App\Features\Tasks\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TaskUserRelationsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_task_relates_to_creator_and_optional_assignee(): void
    {
        $creator = User::factory()->create();
        $assignee = User::factory()->create();
        $task = Task::factory()->create(['creator_id' => $creator->id, 'assignee_id' => $assignee->id]);

        $this->assertTrue($task->creator->is($creator));
        $this->assertTrue($task->assignee->is($assignee));
        $this->assertTrue($creator->createdTasks->contains($task));
        $this->assertTrue($assignee->assignedTasks->contains($task));
    }

    public function test_assignee_can_be_unset_and_is_cleared_when_user_is_deleted(): void
    {
        $assignee = User::factory()->create();
        $task = Task::factory()->create(['assignee_id' => $assignee->id]);
        $unassignedTask = Task::factory()->create();

        $assignee->delete();

        $this->assertNull($task->fresh()->assignee_id);
        $this->assertNull($unassignedTask->assignee);
    }

    public function test_creator_cannot_be_deleted_while_their_task_exists(): void
    {
        $task = Task::factory()->create();

        $this->expectException(QueryException::class);
        $task->creator->delete();
    }

    public function test_creator_is_required(): void
    {
        $this->expectException(QueryException::class);

        Task::factory()->create(['creator_id' => null]);
    }
}
