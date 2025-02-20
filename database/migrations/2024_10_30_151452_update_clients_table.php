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
        Schema::table('customers', function(Blueprint $table){
            $table->boolean('is_welcomed')->default(false);
            $table->string('welcome_note')->nullable();
        });
        Schema::table('corporates', function(Blueprint $table){
            $table->boolean('is_welcomed')->default(false);
            $table->string('welcome_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
