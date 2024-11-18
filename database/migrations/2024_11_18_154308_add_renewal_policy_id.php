<?php

use App\Models\Business\SoldPolicy;
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
            $table->foreignIdFor(SoldPolicy::class, 'renewal_policy_id')->nullable()->constrained('sold_policies');
        });
        Schema::table('sold_policies', function(Blueprint $table){
            $table->foreignIdFor(SoldPolicy::class, 'renewal_policy_id')->nullable()->constrained('sold_policies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
