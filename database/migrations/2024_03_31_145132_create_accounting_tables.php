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
            $table->string('name')->unique();
            $table->text('desc')->nullable();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->enum('nature', Account::NATURES);
            $table->enum('type', Account::TYPES);
            $table->double('balance');
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained();
            $table->enum('currency', JournalEntry::CURRENCIES);
            $table->double('credit')->default(0);
            $table->double('debit')->default(0);
            $table->text('doc_url')->nullable();
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
