<?php

use App\Models\Payments\CommProfilePayment;
use App\Models\Payments\SalesComm;
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
        Schema::create('comm_payments_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SalesComm::class);
            $table->foreignIdFor(CommProfilePayment::class);
            $table->double('paid_percentage');
            $table->double('amount');
        });

        Schema::table('sold_policy_comms', function (Blueprint $table) {
            $table->boolean('is_manual')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comm_payments_details');
    }
};
