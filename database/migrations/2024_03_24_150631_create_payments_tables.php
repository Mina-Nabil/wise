<?php

use App\Models\Business\SoldPolicy;
use App\Models\Insurance\GrossCalculation;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\SalesComm;
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
        Schema::create('policy_comm_conf', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Policy::class);
            $table->string('title');
            $table->enum("calculation_type", GrossCalculation::TYPES);
            $table->double('value');
            $table->integer('due_penalty')->nullable(); //7 days
            $table->double('penalty_percent')->nullable(); //50
        });

        Schema::create('sold_policy_comms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class);
            $table->string('title');
            $table->double('amount');
            $table->timestamps();
        });

        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class)->nullable();
            $table->foreignIdFor(User::class, 'closed_by_id')->nullable();
            $table->enum('status', ClientPayment::PYMT_STATES)->default(ClientPayment::PYMT_STATE_NEW);
            $table->enum('type', ClientPayment::PYMT_TYPES);
            $table->date('due');
            $table->double('amount');
            $table->dateTime('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->text('doc_url')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('company_comm_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'receiver_id')->nullable();
            $table->foreignIdFor(SoldPolicy::class)->nullable();
            $table->enum('status', CompanyCommPayment::PYMT_STATES)->default(CompanyCommPayment::PYMT_STATE_NEW);
            $table->enum('type', ClientPayment::PYMT_TYPES);
            $table->double('amount');
            $table->dateTime('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->text('doc_url')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sales_comms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Offer::class)->nullable();
            $table->foreignIdFor(SoldPolicy::class)->nullable();
            $table->string('title');
            $table->double('comm_percentage');
            $table->double('amount')->nullable();
            $table->enum('status', SalesComm::PYMT_STATES)->default(SalesComm::PYMT_STATE_NEW);
            $table->dateTime('payment_date')->nullable();
            $table->text('doc_url')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('sold_policies', function (Blueprint $table) {
            $table->dateTime('client_payment_date')->nullable();
            $table->double('total_policy_comm')->nullable(); //gai mn el configuration
            $table->double('total_client_paid')->nullable();
            $table->double('total_sales_comm')->nullable();
            $table->double('total_comp_paid')->nullable(); //gai mn sherket t2meen
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sold_policies');
        Schema::dropIfExists('sales_comms');
        Schema::dropIfExists('company_comm_payments');
        Schema::dropIfExists('client_payments');
        Schema::dropIfExists('sold_policy_comms');
        Schema::dropIfExists('policy_comm_conf');
    }
};
