<?php

use App\Models\Business\SoldPolicy;
use App\Models\Customers\Car;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskAction;
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
        Schema::create('sold_policies', function (Blueprint $table) {
            $table->id();
            $table->morphs('client'); //customer or corporate
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->foreignIdFor(Policy::class)->constrained();
            $table->foreignIdFor(Offer::class)->nullable()->constrained();
            $table->string('policy_number');
            $table->double('insured_value');
            $table->double('net_rate');
            $table->double('net_premium');
            $table->double('gross_premium');
            $table->integer('installements_count')->default(1);
            $table->enum('payment_frequency', OfferOption::PAYMENT_FREQS)->nullable();
            $table->dateTime('start');
            $table->dateTime('expiry');
            $table->boolean('is_valid')->default(true); //valid or cancelled
            $table->foreignIdFor(Car::class, 'customer_car_id')->nullable()->constrained(); //customer car
            $table->string('car_chassis')->nullable();
            $table->string('car_plate_no')->nullable();
            $table->string('car_engine')->nullable();
            $table->double('discount')->default(0);
            $table->text('note')->nullable();
            $table->string('in_favor_to')->nullable();
            $table->string('policy_doc')->nullable();
            $table->timestamps();
        });

        Schema::create('sold_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class);
            $table->enum('benefit', PolicyBenefit::BENEFITS);
            $table->string('value');
        });

        Schema::create('sold_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SoldPolicy::class);
            $table->string('title');
            $table->string('value');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('type', Task::TYPES)->default(Task::TYPE_TASK);
        });

        Schema::create('task_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->string('title');
            $table->enum('status', TaskAction::STATUSES);
            $table->string('column_name');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('task_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class);
            $table->string('title');
            $table->string('value');
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
        Schema::dropIfExists('task_actions');
        Schema::dropIfExists('task_fields');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::dropIfExists('sold_exclusions');
        Schema::dropIfExists('sold_benefits');
        Schema::dropIfExists('sold_policies');
    }
};
