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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('offers')->nullable();
            $table->string('goal')->nullable();
            $table->string('target_audience')->nullable();
            $table->string('marketing_channels')->nullable();
            $table->string('handler')->nullable();
            $table->string('budget')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns');
        });

        Schema::table('corporates', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns');
        });

        Schema::table('followups', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('campaigns');
    }
};
