<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('policy_conditions', function (Blueprint $table) {
            $table->double('gross_rate')->nullable()->after('rate');
        });
    }

    public function down()
    {
        Schema::table('policy_conditions', function (Blueprint $table) {
            $table->dropColumn('gross_rate');
        });
    }
};
