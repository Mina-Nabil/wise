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
        Schema::table('account_settings', function (Blueprint $table) {
            // Drop the unique constraint on key column
            $table->dropUnique(['key']);
            
            // Keep the regular index for faster lookups
            // The index was added in the original migration, so we don't need to re-add it
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_settings', function (Blueprint $table) {
            // Re-add the unique constraint if rolling back
            $table->unique('key');
        });
    }
};
