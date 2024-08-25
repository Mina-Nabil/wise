<?php

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); //masrofat - revenue - clients
            $table->text('desc')->nullable();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //dyafa - 7sab allianz - 7sab motorcity
            $table->text('desc')->nullable();
            $table->enum('nature', Account::NATURES);
            $table->enum('type', Account::TYPES);
            $table->double('balance');
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class, 'credit_id')->constrained();
            $table->foreignIdFor(Account::class, 'debit_id')->constrained();
            $table->foreignIdFor(JournalEntry::class, 'revert_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete(); //internal
            $table->double('amount')->default(0);
            $table->text('credit_doc_url')->nullable();
            $table->text('debit_doc_url')->nullable();
            $table->double('credit_balance'); //internal
            $table->double('debit_balance'); //internal
            $table->enum('currency', JournalEntry::CURRENCIES);
            $table->double('currency_amount')->default(0);
            $table->double('currency_rate')->default(0);
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
        Schema::dropIfExists('accounting_tables');
    }
};
