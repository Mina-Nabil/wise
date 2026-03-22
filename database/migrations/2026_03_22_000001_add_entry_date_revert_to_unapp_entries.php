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
        Schema::table('unapp_entries', function (Blueprint $table) {
            $table->date('entry_date')->nullable()->after('cash_entry_type');
            $table->foreignId('revert_entry_id')->nullable()->after('entry_date')
                ->constrained('journal_entries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unapp_entries', function (Blueprint $table) {
            $table->dropForeign(['revert_entry_id']);
            $table->dropColumn(['entry_date', 'revert_entry_id']);
        });
    }
};
