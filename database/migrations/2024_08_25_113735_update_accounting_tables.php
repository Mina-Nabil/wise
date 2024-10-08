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
        Schema::create('entry_titles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //shai w cake 
            $table->text('desc')->nullable();
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            $table->foreignIdFor(EntryTitle::class)->constrained();
            $table->foreignIdFor(User::class)->constrained(); //creator
            $table->text('comment')->nullable();
            $table->boolean('is_reviewed')->default(false);
            $table->integer('day_serial'); //internal
            $table->string('receiver_name')->nullable();
            $table->enum('cash_entry_type', JournalEntry::CASH_ENTRY_TYPES)->nullable();
            $table->foreignIdFor(User::class, 'approver_id')->constrained('users');
            $table->dateTime('approved_at')->nullable();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->double('limit')->default(50000);
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
