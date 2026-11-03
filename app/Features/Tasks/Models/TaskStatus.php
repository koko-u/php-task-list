<?php

namespace App\Features\Tasks\Models;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function toLabel(): string
    {
        return match ($this) {
            TaskStatus::Pending => 'Pending',
            TaskStatus::InProgress => 'In Progress',
            TaskStatus::Completed => 'Completed'
        };
    }
}
