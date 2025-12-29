<?php

use App\Models\Payments\CommProfilePayment;
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
        Schema::table('comm_payments_details', function (Blueprint $table) {
            
            // Add foreign key with cascade delete
            $table->foreign('comm_profile_payment_id')
                ->references('id')
                ->on('comm_profile_payments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comm_payments_details', function (Blueprint $table) {
            // Drop the cascading foreign key
            $table->dropForeign(['comm_profile_payment_id']);
            
            // Restore original foreign key without cascade
            $table->foreign('comm_profile_payment_id')
                ->references('id')
                ->on('comm_profile_payments');
        });
    }
};
