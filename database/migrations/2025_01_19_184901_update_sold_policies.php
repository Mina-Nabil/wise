<?php

use App\Models\Business\SoldPolicy;
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
            $table->enum('delivery_type', SoldPolicy::DELIVERY_TYPES)->nullable();
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
            $table->dropColumn('delivery_type');
        });
    }
};
