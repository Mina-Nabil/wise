<?php

use App\Models\Payments\CommProfile;
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
        Schema::table('targets', function(Blueprint $table){
            $table->date('next_run_date')->nullable();
            $table->boolean('is_end_of_month')->default(false);
            $table->double('sales_out_percent')->default(0);
        });
        
        Schema::table('sold_policies', function(Blueprint $table){
            $table->double('after_tax_comm')->nullable();
        });

        Schema::table('comm_profiles', function(Blueprint $table){
            $table->foreignIdFor(CommProfile::class, 'auto_override_id')->nullable();
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
