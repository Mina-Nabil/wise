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
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('mob_number1')->nullable();
            $table->string('mob_number2')->nullable();
            $table->string('home_number1')->nullable();
            $table->string('home_number2')->nullable();
            $table->string('work_number1')->nullable();
            $table->string('work_number2')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_district')->nullable();
            $table->string('address_governate')->nullable();
            $table->string('address_country')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_info');
    }
};
