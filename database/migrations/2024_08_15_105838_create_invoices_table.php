<?php

use App\Models\Insurance\Company;
use App\Models\Payments\Invoice;
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
        Schema::dropIfExists('invoices');

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained('insurance_companies');
            $table->foreignIdFor(User::class, 'created_by')->constrained('users');
            $table->integer('serial')->unique();
            $table->double('gross_total');
            $table->double('tax_total');
            $table->double('net_total');
            $table->timestamps();
        });

        Schema::table('company_comm_payments', function (Blueprint $table) {
            $table->integer('pymnt_perm')->nullable(); //ezn sarf 3mola
            $table->foreignIdFor(Invoice::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
