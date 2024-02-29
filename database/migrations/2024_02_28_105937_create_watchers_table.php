<?php

use App\Models\Business\SoldPolicy;
use App\Models\Offers\Offer;
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
        Schema::create('offer_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
        });

        Schema::create('policy_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_watchers');
        Schema::dropIfExists('policy_watchers');
    }
};
