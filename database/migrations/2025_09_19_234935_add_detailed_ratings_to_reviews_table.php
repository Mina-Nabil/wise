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
            // Rename company_rating to policy_conditions_rating
            $table->renameColumn('company_rating', 'policy_conditions_rating');
            $table->renameColumn('client_company_comment', 'policy_conditions_comment');
            
            // Add new rating and comment fields
            $table->decimal('service_quality_rating', 10, 1)->nullable();
            $table->text('service_quality_comment')->nullable();
            
            $table->decimal('pricing_rating', 10, 1)->nullable();
            $table->text('pricing_comment')->nullable();
            
            $table->decimal('processing_time_rating', 10, 1)->nullable();
            $table->text('processing_time_comment')->nullable();
            
            $table->decimal('collection_channel_rating', 10, 1)->nullable();
            $table->text('collection_channel_comment')->nullable();
            
            $table->text('suggestions')->nullable();
            
            $table->boolean('is_referred')->nullable();
            $table->text('referral_comment')->nullable();
            
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
            // Reverse the column renames
            $table->renameColumn('policy_conditions_rating', 'company_rating');
            $table->renameColumn('policy_conditions_comment', 'client_company_comment');
            
            // Drop new columns
            $table->dropColumn([
                'service_quality_rating',
                'service_quality_comment',
                'pricing_rating',
                'pricing_comment',
                'processing_time_rating',
                'processing_time_comment',
                'collection_channel_rating',
                'collection_channel_comment',
                'suggestions',
                'is_referred',
                'referral_comment',
            ]);
        });
    }
};
