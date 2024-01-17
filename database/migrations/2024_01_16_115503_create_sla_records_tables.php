<?php

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
        Schema::create('sla_records', function (Blueprint $table) {
            $table->id();
            $table->morphs('action_item');
            $table->foreignIdFor(User::class, 'created_by')->constrained();
            $table->foreignIdFor(User::class, 'assigned_to_id')->nullable()->constrained();
            $table->string('assigned_to_team')->nullable();
            $table->string("action_title");
            $table->dateTime('due');
            $table->foreignIdFor(User::class, 'reply_by')->constrained();
            $table->string("reply_action")->nullable();
            $table->dateTime('reply_date')->nullable();
            $table->boolean('is_ignore')->default(false);
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
        Schema::dropIfExists('sla_records');
    }
};
