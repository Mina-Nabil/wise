<?php

use App\Models\Customers\BankAccount;
use App\Models\Customers\Customer;
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
 
        Schema::create('customer_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->enum('type', BankAccount::TYPES);
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('owner_name');
            $table->boolean('is_default')->default(false);
            $table->string('bank_branch')->nullable();
            $table->string('iban')->nullable();
            $table->string('evidence_doc')->nullable();
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
        Schema::dropIfExists('customer_bank_accounts');
    }
};
