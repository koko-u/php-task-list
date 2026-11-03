<?php

namespace App\Features\Tasks\Factories;

use App\Features\Tasks\Models\Task;
use App\Features\Tasks\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'longDescription' => $this->faker->optional()->paragraph(4),
            'status' => $this->faker->randomElement(TaskStatus::cases()),
        ];
    }
}
