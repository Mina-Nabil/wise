<?php

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
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->dateTime('reviewed_at')->nullable();
            $table->boolean('is_reviewed')->default(false);
            $table->boolean('is_valid_data')->default(true);
            $table->text('review_comment')->nullable();
            $table->decimal('penalty_amount', 10, 2)->default(0);
        });

        Schema::table('client_payments', function (Blueprint $table) {
            $table->dateTime('collected_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->dropColumn('is_reviewed');
        });
    }
};
