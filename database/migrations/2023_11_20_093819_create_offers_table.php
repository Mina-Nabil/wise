<?php

use App\Models\Insurance\Policy;
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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->morph('owner'); //customer or corporate
            $table->enum('type', []); //motor - health..
            $table->enum('status', []);
            $table->double('item_value')->nullable();
            $table->double('final_insured_value')->nullable();
            $table->double('final_periodic_payment')->nullable();
            $table->double('final_payment_frequency')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('offer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor("Offer")->constrained('offers');
            $table->foreignIdFor(User::class)->constrained('users');
            $table->string('note');
            $table->timestamps();
        });
        Schema::create('offer_options', function (Blueprint $table) {
            $table->id();
            $table->enum('status', []);
            $table->foreignIdFor(Policy::class)->constrained('insurance_companies');
            $table->double('insured_value')->nullable();
            $table->double('periodic_payment')->nullable();
            $table->double('payment_frequency')->nullable();
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
        Schema::dropIfExists('offer_options');
        Schema::dropIfExists('offers');
    }
};
