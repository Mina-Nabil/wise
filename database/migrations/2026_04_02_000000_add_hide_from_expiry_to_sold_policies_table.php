<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->boolean('hide_from_expiry')->default(false)->after('is_renewed');
        });
    }

    public function down(): void
    {
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->dropColumn('hide_from_expiry');
        });
    }
};
