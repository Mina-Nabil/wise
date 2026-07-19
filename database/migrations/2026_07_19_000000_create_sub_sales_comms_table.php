<?php

use App\Models\Payments\ClientPayment;
use App\Models\Payments\SalesComm;
use App\Models\Payments\SubSalesComm;
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
        Schema::create('sub_sales_comms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SalesComm::class)->constrained()->cascadeOnDelete();
            $table->enum('source', SubSalesComm::SOURCES);
            $table->foreignIdFor(Target::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ClientPayment::class)->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->double('percentage')->nullable();
            $table->double('amount');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['sales_comm_id', 'client_payment_id']);
            $table->unique(['sales_comm_id', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_sales_comms');
    }
};
