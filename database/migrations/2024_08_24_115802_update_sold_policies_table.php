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
        Schema::table('sold_policies', function(Blueprint $table){
            $table->double('after_tax_comm')->default(0)->change();
            $table->double('total_policy_comm')->default(0)->change();
            $table->double('total_client_paid')->default(0)->change();
            $table->double('total_sales_comm')->default(0)->change();
            $table->double('total_comp_paid')->default(0)->change();
            $table->double('sales_out_comm')->default(0)->change();
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
