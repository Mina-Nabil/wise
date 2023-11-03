<?php

use App\Models\Tasks\Task;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('taskable');
            $table->string('title');
            $table->text('desc')->nullable();
            $table->foreignIdFor(User::class, 'open_by_id')->nullable();
            $table->foreignIdFor(User::class, 'assigned_to_id')->nullable();
            $table->foreignIdFor(User::class, 'last_action_by_id')->nullable();
            $table->dateTime('due')->nullable();
            $table->enum("status", Task::STATUSES);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Task::class);
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
