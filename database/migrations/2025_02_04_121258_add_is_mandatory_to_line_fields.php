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
        Schema::table('line_fields', function (Blueprint $table) {
            $table->boolean('is_mandatory')->default(false)->after('field'); 
        });

        Schema::table('offer_fields', function (Blueprint $table) {
            $table->boolean('is_mandatory')->default(false)->after('field'); 
        });

        Schema::table('sold_policy_fields', function (Blueprint $table) {
            $table->boolean('is_mandatory')->default(false)->after('field'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_policy_fields', function (Blueprint $table) {
            $table->dropColumn('is_mandatory');
        });

        Schema::table('offer_fields', function (Blueprint $table) {
            $table->dropColumn('is_mandatory');
        });

        Schema::table('line_fields', function (Blueprint $table) {
            $table->dropColumn('is_mandatory');
        });
    }
};
