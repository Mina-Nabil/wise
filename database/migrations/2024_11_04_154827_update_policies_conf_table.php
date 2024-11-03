<?php

use App\Models\Payments\CommProfileConf;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('policy_comm_conf', function(Blueprint $table){
            $table->boolean('is_main_penalty')->default(false); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policy_comm_conf', function(Blueprint $table){
            $table->dropColumn('is_main_penalty'); 
        });
    }
};
