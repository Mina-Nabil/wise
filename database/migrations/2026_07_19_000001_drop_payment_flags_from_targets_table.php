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
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn(['is_full_amount', 'add_as_payment', 'add_to_balance']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->boolean('is_full_amount')->default(false);
            $table->double('add_as_payment')->default(100);
            $table->double('add_to_balance')->default(100);
        });
    }
};
