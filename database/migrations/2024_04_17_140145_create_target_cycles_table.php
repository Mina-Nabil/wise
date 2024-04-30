<?php

use App\Models\Payments\CommProfile;
use App\Models\Payments\CommProfilePayment;
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
        Schema::create('target_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CommProfile::class)->constrained()->cascadeOnDelete();
            $table->integer('day_of_month');
            $table->integer('each_month');
            $table->timestamps();
        });

        Schema::create('comm_profile_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CommProfile::class)->constrained()->cascadeOnDelete();
            $table->double('amount');
            $table->boolean('needs_approval');
            $table->enum('status', CommProfilePayment::PYMT_STATES);
            $table->enum('type', CommProfilePayment::PYMT_TYPES);
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->foreignIdFor(User::class, 'approver_id')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->text('doc_url')->nullable();
            $table->timestamps();
        });
        Schema::table('sold_policies', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'main_sales_id')->nullable()
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_cycles');
    }
};
