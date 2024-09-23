<?php

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\MainAccount;
use App\Models\Accounting\UnapprovedEntry;
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
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('unapproved_entries');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('entry_titles');
        Schema::dropIfExists('main_accounts');

        Schema::create('main_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code'); 
            $table->string('name')->unique(); //masrofat - revenue - clients
            $table->enum('type', MainAccount::TYPES);
            $table->text('desc')->nullable();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code'); 
            $table->string('name'); //dyafa - 7sab allianz - 7sab motorcity
            $table->text('desc')->nullable();
            $table->foreignIdFor(MainAccount::class)->constrained();
            $table->foreignIdFor(Account::class, 'parent_account_id')->constrained('accounts');
            $table->enum('nature', Account::NATURES);
            $table->string('default_currency')->default(JournalEntry::CURRENCY_EGP);
            $table->double('balance')->default(0);
            $table->double('foreign_balance')->default(0);
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignIdFor(Account::class, 'parent_account_id')->nullable()->constrained('accounts');
        });


        Schema::create('entry_titles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //shai w cake 
            $table->text('desc')->nullable();
        });

        Schema::create('titles_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EntryTitle::class)->constrained('entry_titles');
            $table->foreignIdFor(Account::class)->constrained();
            $table->enum('nature', Account::NATURES);
            $table->double('limit')->nullable();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EntryTitle::class)->constrained();
            $table->foreignIdFor(User::class, 'approver_id')->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->foreignIdFor(User::class)->constrained(); //creator
            $table->boolean('is_reviewed')->default(false);
            $table->integer('day_serial'); //internal
            $table->string('receiver_name')->nullable();
            $table->enum('cash_entry_type', JournalEntry::CASH_ENTRY_TYPES)->nullable();
            $table->text('comment')->nullable();
            $table->foreignIdFor(JournalEntry::class, 'revert_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete(); //internal
            $table->timestamps();
        });

        Schema::create('entry_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JournalEntry::class)->constrained('journal_entries');
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



        Schema::create('unapproved_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EntryTitle::class)->constrained();
            $table->foreignIdFor(User::class)->constrained(); //creator
            $table->string('receiver_name')->nullable();
            $table->enum('cash_entry_type', JournalEntry::CASH_ENTRY_TYPES)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('unapproved_entry_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UnapprovedEntry::class)->constrained('unapproved_entries');
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
        //
    }
};
