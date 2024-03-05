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
        Schema::table("offers", function (Blueprint $table) {
            $table->string("renewal_policy")->nullable();
        });

        Schema::table("sold_policies", function (Blueprint $table) {
            $table->boolean("is_renewed")->default(0);
            $table->boolean("is_paid")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("offers", function (Blueprint $table) {
            $table->dropColumn("renewal_policy");
        });

        Schema::table("sold_policies", function (Blueprint $table) {
            $table->dropColumn("is_renewed");
            $table->dropColumn("is_paid");
        });
    }
};
