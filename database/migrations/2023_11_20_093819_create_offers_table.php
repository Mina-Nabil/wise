<?php

use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Users\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        DB::statement("ALTER TABLE policies MODIFY COLUMN business ENUM('" . implode("','", Policy::LINES_OF_BUSINESS) . "')");

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users');
            $table->foreignIdFor(User::class, 'assignee_id')->nullable()->constrained('users');
            $table->enum('assignee_type', User::TYPES)->nullable(); //if assigned to team
            $table->morphs('client'); //customer or corporate
            $table->nullableMorphs('item'); //only car for now.. later maybe more items will be added
            $table->enum('type', Policy::LINES_OF_BUSINESS); //motor - health..
            $table->enum('status', Offer::STATUSES);
            $table->double('item_value')->nullable(); //se3r el item el hn3mno
            $table->string('item_title')->nullable();
            $table->text('item_desc')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('due')->nullable();
            $table->foreignIdFor(User::class, 'closed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('offer_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->string('comment');
            $table->timestamps();
        });

        Schema::create('offer_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('name');
            $table->text('url');
            $table->timestamps();
        });

        Schema::create('offer_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Offer::class)->constrained()->cascadeOnDelete();
            $table->enum('status', OfferOption::STATUSES);
            $table->foreignIdFor(Policy::class)->constrained(); ///health one allianz - life for ever masr el ta2eme
            $table->foreignIdFor(PolicyCondition::class)->nullable()->constrained('policy_conditions');
            $table->double('insured_value')->nullable();
            $table->double('periodic_payment')->nullable();
            $table->enum('payment_frequency', OfferOption::PAYMENT_FREQS)->nullable();
            $table->foreignIdFor(User::class, 'approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('application_doc')->nullable(); //
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('option_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OfferOption::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });
        
        Schema::create('option_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OfferOption::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('name'); 
            $table->string('url');
            $table->timestamps();
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreignIdFor(OfferOption::class, 'selected_option_id')->nullable()->constrained('offer_options')->nullOnDelete();
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
        Schema::dropIfExists('option_docs');
        Schema::dropIfExists('option_fields');
        Schema::dropIfExists('offer_options');
        Schema::dropIfExists('offer_docs');
        Schema::dropIfExists('offer_comments');
        Schema::dropIfExists('offers');
    }
};
