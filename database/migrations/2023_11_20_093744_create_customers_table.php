<?php

use App\Models\Cars\Car;
use App\Models\Base\Country;
use App\Models\Customers\Address;
use App\Models\Customers\Car as CustomersCar;
use App\Models\Customers\Customer;
use App\Models\Customers\Phone;
use App\Models\Customers\Profession;
use App\Models\Customers\Relative;
use App\Models\Offers\OfferOption;
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
        self::down();
        Schema::create('professions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', Customer::TYPES);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('arabic_first_name')->nullable();
            $table->string('arabic_middle_name')->nullable();
            $table->string('arabic_last_name')->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', Customer::GENDERS)->nullable();
            $table->enum('marital_status', Customer::MARITALSTATUSES)->nullable();
            $table->enum('id_type', Customer::IDTYPES)->nullable();
            $table->string('id_number')->nullable();
            $table->foreignIdFor(User::class, 'creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(Country::class, 'nationality_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignIdFor(Profession::class)->nullable()->constrained('professions')->nullOnDelete();
            $table->enum('salary_range', Customer::SALARY_RANGES)->nullable();
            $table->enum('income_source', Customer::INCOME_SOURCES)->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained('customers')->cascadeOnDelete();
            $table->enum('type', Phone::TYPES);
            $table->string('number');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained('customers')->cascadeOnDelete();
            $table->enum('type', Address::TYPES);
            $table->string('line_1');
            $table->boolean('is_default')->default(false);
            $table->string('line_2')->nullable();
            $table->string('flat')->nullable();
            $table->string('building')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained('customers')->cascadeOnDelete();
            $table->foreignIdFor(Car::class)->constrained('cars');
            $table->unsignedInteger('model_year'); 
            $table->double('sum_insured')->nullable();
            $table->double('insurance_payment')->nullable();
            $table->enum('payment_frequency', OfferOption::PAYMENT_FREQS)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained('customers')->cascadeOnDelete();
            $table->string('name');
            $table->enum('relation', Relative::RELATIONS)->nullable();
            $table->enum('gender', Customer::GENDERS)->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('birth_date')->nullable();
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
        Schema::dropIfExists('customer_relative');
        Schema::dropIfExists('customer_cars');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('professions');
    }
};
