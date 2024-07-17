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
        //merge targets with target cycles then add
        // base cycle payment
        // add_to_balance percentage
        // add_payment percentage
        // min_income_target
        // max_income_target
        // add target runs -> include added to balance / added payment / cycle-id

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
