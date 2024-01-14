<?php

use App\Models\Corporates\Corporate;
use App\Models\Corporates\Status as CorporatesStatus;
use App\Models\Customers\Customer;
use App\Models\Customers\Status as CustomersStatus;
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
   
        Schema::create('customer_status', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(); 
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete(); 
            $table->enum('status', CustomersStatus::STATUSES);
            $table->enum('reason', CustomersStatus::REASONS);
            $table->string('note')->default(true);
            $table->timestamps();
        });

        Schema::create('corporate_status', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(); 
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete(); 
            $table->enum('status', CorporatesStatus::STATUSES);
            $table->enum('reason', CorporatesStatus::REASONS)->nullable();
            $table->string('note')->default(true);
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
        Schema::dropIfExists('corporate_status');
        Schema::dropIfExists('customer_status');
    }
};
