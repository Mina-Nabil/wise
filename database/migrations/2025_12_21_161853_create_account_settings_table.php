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
        Schema::create('account_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->comment('Setting key identifier');
            $table->unsignedBigInteger('account_id')->nullable()->comment('Reference to account ID');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
            
            // Index for faster lookups (not unique to allow multiple accounts per key)
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_settings');
    }
};
