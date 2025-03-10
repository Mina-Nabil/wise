<?php

use App\Models\Customers\Relative;
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
        Schema::table('medical_offer_clients', function (Blueprint $table) {
            $table->enum('relation', Relative::RELATIONS)->default(Relative::RELATION_MAIN);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_offer_clients', function (Blueprint $table) {
            $table->dropColumn('relation');
        });
    }
};
