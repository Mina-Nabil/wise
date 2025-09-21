<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, set all existing no_answer values to null
        DB::table('reviews')->update(['no_answer' => null]);
        
        // Then make the column nullable
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('no_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // First, set all null values to false
        DB::table('reviews')->whereNull('no_answer')->update(['no_answer' => false]);
        
        // Then make the column not nullable
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('no_answer')->default(false)->change();
        });
    }
};
