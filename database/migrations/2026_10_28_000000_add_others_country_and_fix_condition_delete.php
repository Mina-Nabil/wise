<?php

use App\Models\Base\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Country::firstOrCreate(['name' => 'Others']);

        Schema::table('offer_options', function (Blueprint $table) {
            $table->dropForeign(['policy_condition_id']);
            $table->foreign('policy_condition_id')
                ->references('id')
                ->on('policy_conditions')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('offer_options', function (Blueprint $table) {
            $table->dropForeign(['policy_condition_id']);
            $table->foreign('policy_condition_id')
                ->references('id')
                ->on('policy_conditions');
        });

        Country::where('name', 'Others')->delete();
    }
};
