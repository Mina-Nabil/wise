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
        Schema::create('archived_entry_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archived_entry_id')->constrained('archived_entries');
            $table->foreignIdFor(Account::class)->constrained();
            $table->enum('nature', Account::NATURES);
            $table->double('amount')->default(0);
            $table->double('account_foreign_balance'); //internal
            $table->double('account_balance'); //internal
            $table->enum('currency', JournalEntry::CURRENCIES);
            $table->double('currency_amount')->default(0);
            $table->double('currency_rate')->default(0);
            $table->text('doc_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archived_entry_accounts');
    }
};
