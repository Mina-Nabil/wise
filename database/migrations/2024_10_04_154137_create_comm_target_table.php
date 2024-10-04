<?php

use App\Models\Payments\SalesComm;
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
        Schema::create('comm_target_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SalesComm::class);
            $table->foreignIdFor(Target::class);
            $table->double('percentage');
            $table->double('amount');
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
        Schema::dropIfExists('comm_target_runs');
    }
};
