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
        Schema::table('sales_comms', function (Blueprint $table) {
            $table->double('unapproved_balance_offset')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_comms', function (Blueprint $table) {
            $table->dropColumn('unapproved_balance_offset'); 
        });
    }
};
