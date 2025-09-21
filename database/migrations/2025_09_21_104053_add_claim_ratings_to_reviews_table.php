<?php

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
        Schema::table('reviews', function (Blueprint $table) {
            // Claim-specific ratings
            $table->decimal('insurance_company_rating', 3, 1)->nullable();
            $table->text('insurance_company_comment')->nullable();
            $table->decimal('provider_rating', 3, 1)->nullable();
            $table->text('provider_comment')->nullable();
            $table->decimal('claims_specialist_rating', 3, 1)->nullable();
            $table->text('claims_specialist_comment')->nullable();
            $table->decimal('wise_rating', 3, 1)->nullable();
            $table->text('wise_comment')->nullable();
            
            // Claim manager review fields
            $table->boolean('need_claim_manager_review')->default(false);
            $table->text('claim_manager_comment')->nullable();
            $table->boolean('is_claim_manager_reviewed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop claim-specific ratings
            $table->dropColumn([
                'insurance_company_rating',
                'insurance_company_comment',
                'provider_rating',
                'provider_comment',
                'claims_specialist_rating',
                'claims_specialist_comment',
                'wise_rating',
                'wise_comment',
                'need_claim_manager_review',
                'claim_manager_comment',
                'is_claim_manager_reviewed',
            ]);
        });
    }
};
