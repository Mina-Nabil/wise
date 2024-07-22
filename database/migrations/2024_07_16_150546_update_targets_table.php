<?php

use App\Models\Payments\Target;
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

        Schema::dropIfExists('target_cycles');

        Schema::table('targets', function (Blueprint $table) {
            // $table->dropColumn('period');
            $table->integer('day_of_month');
            $table->integer('each_month');
            $table->double('base_payment')->nullable();
            $table->double('add_to_balance')->default(0);
            $table->double('add_as_payment')->default(0);
            $table->renameColumn('income_target', 'min_income_target');
            $table->double('max_income_target')->nullable();
        });

        Schema::create('target_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Target::class)->constrained('targets')->cascadeOnDelete();
            $table->double('added_to_balance')->default(0);
            $table->double('added_to_payments')->default(0);
            $table->timestamps();
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
