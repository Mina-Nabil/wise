<?php

use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Payments\CommProfile;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\Target;
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
        Schema::create('comm_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', CommProfile::TYPES);
            $table->boolean('per_policy');
            $table->double('balance')->default(0);
            $table->double('unapproved_balance')->default(0);
            $table->text('desc')->nullable();
            $table->timestamps();
        });

        Schema::create('comm_profile_confs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CommProfile::class)->constrained()->cascadeOnDelete();
            $table->double('percentage');
            $table->enum('from', CommProfileConf::FROMS);
            $table->enum('line_of_business', Policy::LINES_OF_BUSINESS)->nullable();
            $table->nullableMorphs('condition');
            $table->integer('order');
        });

        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CommProfile::class)->constrained()->cascadeOnDelete();
            $table->double('amount');
            $table->double('extra_percentage');
            $table->integer('order');
        });

        Schema::create('offer_comm_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(CommProfile::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
};
