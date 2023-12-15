<?php

use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Offers\Offer;
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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained();
            $table->foreignIdFor(User::class, 'assignee_id')->nullable()->constrained();
            $table->enum('assignee_type', User::TYPES)->nullable(); //if assigned to team
            $table->morph('owner'); //customer or corporate
            $table->nullableMorphs('item'); //only car for now.. later maybe more items will be added
            $table->enum('type', Policy::LINES_OF_BUSINESS); //motor - health..
            $table->enum('status', Offer::STATUSES);
            $table->string('item_title')->nullable();
            $table->text('item_desc')->nullable();
            $table->double('item_value')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('due')->nullable();
            $table->foreignIdFor(User::class, 'closed_by_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('offer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('note');
            $table->timestamps();
        });

        Schema::create('offer_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained();
            $table->enum('status', OfferOption::STATUSES);
            $table->foreignIdFor(Policy::class)->constrained();
            $table->foreignIdFor(PolicyCondition::class)->nullable()->constrained('policy_condition');
            $table->double('insured_value')->nullable();
            $table->double('periodic_payment')->nullable();
            $table->enum('payment_frequency', OfferOption::PAYMENT_FREQS)->nullable();
            $table->foreignIdFor(User::class, 'approver_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreignIdFor(OfferOption::class, 'selected_option_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('offers', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(OfferOption::class, 'selected_option_id');
        });
        Schema::dropIfExists('offer_options'); 
        Schema::dropIfExists('offer_notes'); 
        Schema::dropIfExists('offers');
    }
};
