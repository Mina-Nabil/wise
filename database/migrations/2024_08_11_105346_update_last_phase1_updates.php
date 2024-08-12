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
        Schema::update('targets', function(Blueprint $table){
            $table->date('next_run_date')->nullable();
            $table->boolean('is_end_of_month')->default(false);
        });
        
        Schema::update('', function(Blueprint $table){

        });
        Schema::update('', function(Blueprint $table){

        });
        Schema::update('', function(Blueprint $table){

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
