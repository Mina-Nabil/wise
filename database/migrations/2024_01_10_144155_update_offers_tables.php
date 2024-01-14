<?php

use App\Models\Offers\Offer;
use App\Models\Offers\OfferDiscount;
use App\Models\Users\User;
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
        Schema::table('offers', function(Blueprint $table){
            $table->boolean('is_renewal')->default(false);
        });

        Schema::table('offer_options', function(Blueprint $table){
            $table->dropColumn('periodic_payment'); //kan 2est
            $table->double('net_premium')->nullable(); //50000
            $table->double('gross_premium')->nullable(); //48000
            $table->boolean('is_renewal')->default(false);
            $table->integer('installements_count')->nullable();
        });

        Schema::create('offer_discounts', function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->enum('type', OfferDiscount::TYPES);
            $table->double('value');
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_discounts');
        Schema::table('offer_options', function(Blueprint $table){
            $table->dropColumn('net_premium');
            $table->dropColumn('gross_premium');
            $table->dropColumn('is_renewal');
            $table->dropColumn('installements_count');
        });
        Schema::table('offers', function(Blueprint $table){
            $table->dropColumn('is_renewal');
        });
    }
};
