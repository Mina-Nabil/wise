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
        Schema::table('comm_profile_payments', function (Blueprint $table) {
            $table->date('target_date')->nullable()->after('updated_at');
            $table->decimal('partial_paid', 15, 2)->nullable()->after('target_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comm_profile_payments', function (Blueprint $table) {
            $table->dropColumn(['target_date', 'partial_paid']);
        });
    }
};
