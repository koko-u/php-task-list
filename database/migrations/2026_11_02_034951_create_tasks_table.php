<?php

use App\Features\Tasks\Models\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 100)->nullable(false);
            $table->string('description', 1000)->nullable(false);
            $table->text('longDescription')->nullable(true);
            $table->enum('status', TaskStatus::cases())->default(TaskStatus::Pending->value);
            $table->timestampsTz();

            $table->index('title');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
