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
        Schema::table('reviews', function (Blueprint $table) {
            // Change no_answer from boolean to tinyint
            // null = no call yet, 0 = no answer, 1 = answered, 2 = sent whatsapp
            $table->integer('no_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Revert back to boolean
            $table->boolean('no_answer')->nullable()->change();
        });
    }
};
