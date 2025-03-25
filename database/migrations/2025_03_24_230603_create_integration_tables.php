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
        Schema::table('comm_profiles', function (Blueprint $table) {
            $table->foreignIdFor(Account::class)->nullable()->constrained('accounts')->onDelete('set null');
        });

        Schema::table('comm_profile_payments', function (Blueprint $table) {
            $table->foreignIdFor(JournalEntry::class)->nullable()->constrained('journal_entries')->onDelete('set null');
        });

        Schema::table('insurance_companies', function (Blueprint $table) {
            $table->foreignIdFor(Account::class)->nullable()->constrained('accounts')->onDelete('set null');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignIdFor(JournalEntry::class, 'created_journal_entry_id')->nullable()->constrained('journal_entries')->onDelete('set null');
            $table->foreignIdFor(JournalEntry::class, 'paid_journal_entry_id')->nullable()->constrained('journal_entries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comm_profiles', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });

        Schema::table('comm_profile_payments', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
        });

        Schema::table('insurance_companies', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_journal_entry_id']);
            $table->dropForeign(['paid_journal_entry_id']);
        });
    }
};
