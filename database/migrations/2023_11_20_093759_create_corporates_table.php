<?php

use App\Models\Corporates\Address;
use App\Models\Corporates\BankAccount;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Phone;
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
        Schema::create('corporates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', Corporate::TYPES);
            $table->string('name');
            $table->string('arabic_name')->nullable();
            $table->string('email')->nullable(); 
            $table->string('commercial_record')->nullable(); 
            $table->string('commercial_record_doc')->nullable(); 
            $table->string('tax_id')->nullable(); 
            $table->string('tax_id_doc')->nullable(); 
            $table->string('kyc')->nullable(); 
            $table->string('kyc_doc')->nullable(); 
            $table->string('contract_doc')->nullable(); 
            $table->string('main_bank_evidence')->nullable(); 
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('corporate_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained('corporates')->cascadeOnDelete();
            $table->enum('type', Phone::TYPES);
            $table->string('number');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('corporate_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained('corporates')->cascadeOnDelete();
            $table->enum('type', Address::TYPES);
            $table->string('line_1');
            $table->boolean('is_default')->default(false);
            $table->string('line_2')->nullable();
            $table->string('flat')->nullable();
            $table->string('building')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        Schema::create('corporate_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained('corporates')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('corporate_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained('corporates')->cascadeOnDelete();
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
        Schema::dropIfExists('corporate_bank_accounts');
        Schema::dropIfExists('corporate_contacts');
        Schema::dropIfExists('corporate_addresses');
        Schema::dropIfExists('corporate_phones');
        Schema::dropIfExists('corporates');
    }
};
