<?php

use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyBenefit;
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
        Schema::create('policy_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Policy::class)->constrained();
            $table->enum('benefit', PolicyBenefit::BENEFITS);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_benefits');
    }
};
