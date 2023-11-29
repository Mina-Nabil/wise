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
        // Schema::create('offers', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
        //     $table->morph('owner');
        //     $table->enum('status', []);
        //     $table->timestamps();
        // });

        // Schema::create('offer_options', function (Blueprint $table) {
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
};
