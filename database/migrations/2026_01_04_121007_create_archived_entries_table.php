<?php

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
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
        Schema::create('archived_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JournalEntry::class, 'journal_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete();
            $table->foreignIdFor(EntryTitle::class)->constrained();
            $table->foreignIdFor(User::class, 'approver_id')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->foreignIdFor(User::class)->constrained(); //creator
            $table->boolean('is_reviewed')->default(false);
            $table->integer('day_serial'); //internal
            $table->string('receiver_name')->nullable();
            $table->enum('cash_entry_type', JournalEntry::CASH_ENTRY_TYPES)->nullable();
            $table->text('comment')->nullable();
            $table->foreignIdFor(JournalEntry::class, 'revert_entry_id')->nullable()->constrained('journal_entries')->nullOnDelete(); //internal
            $table->unsignedInteger('cash_serial')->nullable();
            $table->text('extra_note')->nullable();
            $table->dateTime('archived_at');
            $table->foreignIdFor(User::class, 'archived_by')->constrained('users');
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
        Schema::dropIfExists('archived_entries');
    }
};
