<?php

use App\Models\Tasks\Task;
use App\Models\Tasks\TaskTempAssignee;
use App\Models\Users\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("tasks", function (Blueprint $table) {
            $table->string('assigned_to_type')->nullable(); // use if assigned to user_type
        });

        Schema::create("task_files", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->foreignIdFor(User::class);
            $table->string('name');
            $table->string('file_url');
        });

        Schema::create("task_watchers", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->foreignIdFor(User::class);
        });

        Schema::create("task_temp_assignee", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->foreignIdFor(User::class);
            $table->enum('status', TaskTempAssignee::STATUSES);
            $table->date('end_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::table("users", function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'manager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("tasks", function (Blueprint $table) {
            $table->dropColumn('file_url');
        });
    }
};
