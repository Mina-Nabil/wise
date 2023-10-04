<?php

use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
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
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        //each company can have multiple emails - only one primary
        Schema::create('insurance_companies_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class);
            $table->enum('type', CompanyEmail::TYPES);
            $table->string('email');
            $table->boolean('is_primary')->default(0);
            $table->string('contact_first_name')->nullable();
            $table->string('contact_last_name')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class);
            $table->enum('business', Policy::LINES_OF_BUSINESS); //line of business
            $table->string('name');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('policy_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Policy::class);
            $table->enum('scope', PolicyCondition::SCOPES);
            $table->enum('operator', PolicyCondition::OPERATORS);
            $table->double('value');
            $table->double('rate');
            $table->integer('order');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('policies');
    }
};
