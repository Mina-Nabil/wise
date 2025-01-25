<?php

use App\Models\Business\SoldPolicy;
use App\Models\Offers\Offer;
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
        Schema::create('offer_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->string('field');
            $table->text('value')->nullable();
        });

        Schema::create('sold_policy_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class)->constrained()->cascadeOnDelete();
            $table->string('field');
            $table->text('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_fields');
    }
};
