<?php

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
        Schema::create('line_fields', function (Blueprint $table) {
            $table->id();
            $table->enum('line_of_business', Policy::LINES_OF_BUSINESS);
            $table->string('field');
        });
    }
    
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_fields');
    }
};
