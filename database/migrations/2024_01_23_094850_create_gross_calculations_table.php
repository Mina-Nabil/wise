<?php

use App\Models\Insurance\GrossCalculation;
use App\Models\Insurance\Policy;
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
        Schema::create('gross_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Policy::class)->constrained();
            $table->string("title");
            $table->enum("calculation_type", GrossCalculation::TYPES);
            $table->double("value");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gross_calculations');
    }
};
