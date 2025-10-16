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
        Schema::table('comm_profile_confs', function (Blueprint $table) {
            $table->double('renewal_percentage')->nullable()->after('percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comm_profile_confs', function (Blueprint $table) {
            $table->dropColumn('renewal_percentage');
        });
    }
};
