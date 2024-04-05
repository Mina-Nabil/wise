<?php

use App\Models\Insurance\Policy;
use App\Models\Users\User;
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
        Schema::create('sales_profile', function(Blueprint $table){
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['sales_in', 'sales_out']);
            $table->boolean('per_policy');
        });
        
        Schema::create('sales_comm_conf', function (Blueprint $table) {
            $table->foreignIdFor('sales_profile')->constrained()->cascadeOnDelete();
            $table->id();
            $table->double('percentage');
            $table->enum('from', ['net_premium', 'net_commission']);
            $table->enum('line_of_business', Policy::LINES_OF_BUSINESS)->nullable();
            $table->nullableMorphs('condition');
            

            $table->timestamps();
        });
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('targets');
    }
};
