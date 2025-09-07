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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable');
            $table->foreignIdFor(User::class, 'assignee_id')->nullable()->constrained('users');
            $table->string('title');
            $table->text('desc');
            $table->foreignIdFor(User::class, 'reviewed_by_id')->nullable()->constrained('users');
            $table->boolean('is_reviewed')->default(false);
            $table->dateTime('reviewed_at')->nullable();
            $table->decimal('employee_rating', 10, 1)->default(0);
            $table->text('client_employee_comment')->nullable();
            $table->decimal('company_rating', 10, 1)->default(0);
            $table->text('client_company_comment')->nullable();
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
        Schema::dropIfExists('review');
    }
};
