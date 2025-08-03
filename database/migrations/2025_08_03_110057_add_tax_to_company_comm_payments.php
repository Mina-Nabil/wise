<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('company_comm_payments', function (Blueprint $table) {
            $table->decimal('tax_amount', 10, 2)->default(0);
        });

        DB::table('company_comm_payments')->update(['tax_amount' => DB::raw('amount * 0.05')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_comm_payments', function (Blueprint $table) {
            $table->dropColumn('tax_amount');
        });
    }
};
