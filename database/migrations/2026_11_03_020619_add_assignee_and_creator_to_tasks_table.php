<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('tasks')->exists()) {
            throw new RuntimeException('Existing tasks need creators before adding the required creator_id column.');
        }

        Schema::table('tasks', function (Blueprint $table): void {
            $table->foreignUuid('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('creator_id')->constrained('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('assignee_id');
            $table->dropConstrainedForeignId('creator_id');
        });
    }
};
