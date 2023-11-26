<?php

use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
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
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->foreignIdFor(Customer::class)->constrained('customers');
            $table->string('title');
            $table->enum('status', Followup::STATUSES)->default(Followup::STATUS_NEW);
            $table->dateTime('call_time')->nullable();
            $table->dateTime('action_time')->nullable();
            $table->string('desc')->nullable();
            $table->string('caller_note')->nullable();
            $table->foreignIdFor(User::class, 'owner_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('followups');
    }
};
