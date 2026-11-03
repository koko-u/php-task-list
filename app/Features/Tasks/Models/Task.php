<?php

namespace App\Features\Tasks\Models;

use App\Features\Tasks\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table(keyType: 'string', incrementing: false)]
#[UseFactory(TaskFactory::class)]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
        ];
    }
}
