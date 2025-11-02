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
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->string('policy_doc_2')->nullable()->after('policy_doc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->dropColumn('policy_doc_2');
        });
    }
};
