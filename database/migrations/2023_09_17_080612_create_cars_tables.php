<?php

use App\Models\Cars\Brand;
use App\Models\Cars\Car;
use App\Models\Cars\CarModel;
use App\Models\Cars\Country;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Country::class);
        });

        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class);
            $table->string('name'); //5008
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CarModel::class);
            $table->string('category'); 
        });

        Schema::create('car_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Car::class);
            $table->unsignedInteger('model_year'); 
            $table->double('price'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars_tables');
    }
};
