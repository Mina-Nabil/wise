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
        Schema::table('invoices', function (Blueprint $table) {
            $table->double('trans_fees')->default(0)->after('net_total');
            $table->string('trans_fees_notes')->nullable()->after('trans_fees');
            $table->boolean('is_declare_debit')->nullable()->after('trans_fees_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['trans_fees', 'trans_fees_notes', 'is_declare_debit']);
        });
    }
};
