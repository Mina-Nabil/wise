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
        Schema::table('customer_cars', function (Blueprint $table) {
            $table->string('car_chassis')->nullable()->after('car_id');
            $table->string('car_engine')->nullable()->after('car_chassis');
            $table->string('car_plate_no')->nullable()->after('car_engine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_cars', function (Blueprint $table) {
            $table->dropColumn(['car_chassis', 'car_engine', 'car_plate_no']);
        });
    }
};
