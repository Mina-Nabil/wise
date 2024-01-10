<?php

use App\Models\Customers\BankAccount;
use App\Models\Customers\Customer;
use App\Models\Customers\Relative;
use App\Models\Insurance\Company;
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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('id_doc')->nullable();
            $table->string('driver_license_doc')->nullable();
        });
        
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

        Schema::create('cust_cust_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class, 'relative_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('relation', Relative::RELATIONS)->nullable();
        });

        Schema::table('customer_cars', function (Blueprint $table) {
            $table->foreignIdFor(Company::class, 'insurance_company_id')->nullable()->constrained('insurance_companies')->nullOnDelete(); 
            $table->dateTime('renewal_date')->nullable();
            $table->boolean('wise_insured')->default(false);
        });

        Schema::create('customer_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->enum('business', Policy::LINES_OF_BUSINESS);
            $table->boolean('interested')->default(true);
            $table->string('note')->nullable();
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
