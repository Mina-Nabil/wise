<?php

namespace App\Models\Marketing;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Review extends Model
{
    use HasFactory;

    const MORPH_NAME = 'review';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'assignee_id',
        'title',
        'desc',
        'is_reviewed',
        'reviewed_at',
        'employee_rating',
        'client_employee_comment',
        'policy_conditions_rating', // renamed from company_rating
        'policy_conditions_comment', // renamed from client_company_comment
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
        'is_manager_reviewed',
        'need_manager_review',
        'manager_comment',
        'reviewed_by_id',
        // Claim-specific ratings
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
        'no_answer',
    ];

    protected $casts = [
        'is_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
        'employee_rating' => 'decimal:1',
        'policy_conditions_rating' => 'decimal:1',
        'service_quality_rating' => 'decimal:1',
        'pricing_rating' => 'decimal:1',
        'processing_time_rating' => 'decimal:1',
        'collection_channel_rating' => 'decimal:1',
        'is_referred' => 'boolean',
        'is_manager_reviewed' => 'boolean',
        'need_manager_review' => 'boolean',
        // Claim-specific ratings
        'insurance_company_rating' => 'decimal:1',
        'provider_rating' => 'decimal:1',
        'claims_specialist_rating' => 'decimal:1',
        'wise_rating' => 'decimal:1',
        'need_claim_manager_review' => 'boolean',
        'is_claim_manager_reviewed' => 'boolean',
        'no_answer' => 'integer',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    /**
     * Create a new review for a reviewable model
     *
     * @param Model $reviewable The model to be reviewed
     * @param string $title Review title
     * @param string $description Review description
     * @param int|null $assigneeId User ID to assign the review to
     * @return static|false
     */
    public static function createReview(Model $reviewable, string $title, string $description, ?int $assigneeId = null): static|false
    {
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('create', self::class)) {
        //     AppLog::error('Unauthorized attempt to create review', desc: 'User does not have permission to create reviews');
        //     return false;
        // }
        if($reviewable->reviews()->count() > 0) return false;

        try {
            $review = new self([
                'title' => $title,
                'desc' => $description,
                'assignee_id' => $assigneeId,
                'is_reviewed' => false,
            ]);
            $review->reviewable()->associate($reviewable);
            $review->save();
            
            AppLog::info('Review created successfully', loggable: $review);
            return $review;
        } catch (Exception $e) {
            AppLog::error('Failed to create review', desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    /**
     * Set review ratings and client comments
     *
     * @param float|null $employeeRating Rating for employee performance (0-10)
     * @param string|null $employeeComment Client comment about employee
     * @param float|null $policyConditionsRating Rating for policy conditions (0-10)
     * @param string|null $policyConditionsComment Client comment about policy conditions
     * @param float|null $serviceQualityRating Rating for service quality (0-10)
     * @param string|null $serviceQualityComment Client comment about service quality
     * @param float|null $pricingRating Rating for pricing (0-10)
     * @param string|null $pricingComment Client comment about pricing
     * @param float|null $processingTimeRating Rating for processing time (0-10)
     * @param string|null $processingTimeComment Client comment about processing time
     * @param float|null $collectionChannelRating Rating for collection channel effectiveness (0-10)
     * @param string|null $collectionChannelComment Client comment about collection channel
     * @param string|null $suggestions Client suggestions
     * @param bool|null $isReferred Whether client would refer the service
     * @param string|null $referralComment Client comment about referral
     * @param int|null $reviewedById User ID who completed the review
     * @return bool
     */
    public function setRatingsAndComments(
        ?float $employeeRating = null,
        ?string $employeeComment = null,
        ?float $policyConditionsRating = null,
        ?string $policyConditionsComment = null,
        ?float $serviceQualityRating = null,
        ?string $serviceQualityComment = null,
        ?float $pricingRating = null,
        ?string $pricingComment = null,
        ?float $processingTimeRating = null,
        ?string $processingTimeComment = null,
        ?float $collectionChannelRating = null,
        ?string $collectionChannelComment = null,
        ?string $suggestions = null,
        ?bool $isReferred = null,
        ?string $referralComment = null,
        ?int $reviewedById = null
    ): bool {
        /** @var User */
        $loggedInUser = Auth::user();
        
        // Allow admins to edit even if already reviewed, otherwise check normal permission
        if (!$loggedInUser->is_admin) {
            if (!$loggedInUser->can('receiveClientComment', $this)) {
                AppLog::error('Unauthorized attempt to update review ratings', desc: 'User does not have permission to update review ratings', loggable: $this);
                return false;
            }
        }

        try {
            $updates = [];
            
            if ($employeeRating !== null) {
                $updates['employee_rating'] = max(0, min(10, $employeeRating)); // Ensure rating is between 0-10
            }
            
            if ($employeeComment !== null) {
                $updates['client_employee_comment'] = $employeeComment;
            }
            
            if ($policyConditionsRating !== null) {
                $updates['policy_conditions_rating'] = max(0, min(10, $policyConditionsRating)); // Ensure rating is between 0-10
            }
            
            if ($policyConditionsComment !== null) {
                $updates['policy_conditions_comment'] = $policyConditionsComment;
            }
            
            if ($serviceQualityRating !== null) {
                $updates['service_quality_rating'] = max(0, min(10, $serviceQualityRating)); // Ensure rating is between 0-10
            }
            
            if ($serviceQualityComment !== null) {
                $updates['service_quality_comment'] = $serviceQualityComment;
            }
            
            if ($pricingRating !== null) {
                $updates['pricing_rating'] = max(0, min(10, $pricingRating)); // Ensure rating is between 0-10
            }
            
            if ($pricingComment !== null) {
                $updates['pricing_comment'] = $pricingComment;
            }
            
            if ($processingTimeRating !== null) {
                $updates['processing_time_rating'] = max(0, min(10, $processingTimeRating)); // Ensure rating is between 0-10
            }
            
            if ($processingTimeComment !== null) {
                $updates['processing_time_comment'] = $processingTimeComment;
            }
            
            if ($collectionChannelRating !== null) {
                $updates['collection_channel_rating'] = max(0, min(10, $collectionChannelRating)); // Ensure rating is between 0-10
            }
            
            if ($collectionChannelComment !== null) {
                $updates['collection_channel_comment'] = $collectionChannelComment;
            }
            
            if ($suggestions !== null) {
                $updates['suggestions'] = $suggestions;
            }
            
            if ($isReferred !== null) {
                $updates['is_referred'] = $isReferred;
            }
            
            if ($referralComment !== null) {
                $updates['referral_comment'] = $referralComment;
            }
            
            if ($reviewedById !== null) {
                $updates['reviewed_by_id'] = $reviewedById;
            }

            // If any ratings or comments are being set, mark as reviewed
            if (!empty($updates)) {
                $updates['is_reviewed'] = true;
                $updates['reviewed_at'] = now();
                $updates['reviewed_by_id'] = $reviewedById ?? Auth::id();
                $updates['no_answer'] = 1; // Set to 1 (answered) when ratings/comments are provided
                
                // Check if any rating is less than 8 to set need_manager_review
                $needManagerReview = false;
                $ratingFields = [
                    'employee_rating', 'policy_conditions_rating', 'service_quality_rating',
                    'pricing_rating', 'processing_time_rating', 'collection_channel_rating'
                ];
                
                foreach ($ratingFields as $field) {
                    if (isset($updates[$field]) && $updates[$field] < 8) {
                        $needManagerReview = true;
                        break;
                    }
                }
                
                $updates['need_manager_review'] = $needManagerReview;
            }

            $this->update($updates);
            $result = $this->save();
            
            AppLog::info('Review ratings and comments updated successfully', loggable: $this);
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to update review ratings and comments', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    /**
     * Mark review as manager reviewed
     *
     * @param int|null $reviewedById User ID who completed the manager review
     * @return bool
     */
    public function markAsManagerReviewed(?int $reviewedById = null, ?string $managerComment = null): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('markAsReviewed', $this)) {
            AppLog::error('Unauthorized attempt to mark review as manager reviewed', desc: 'User does not have permission to mark review as manager reviewed', loggable: $this);
            return false;
        }

        try {
            $this->update([
                'is_manager_reviewed' => true,
                'need_manager_review' => false, // No longer needs manager review
                'manager_comment' => $managerComment,
            ]);
            
            $result = $this->save();
            AppLog::info('Review marked as manager reviewed successfully', loggable: $this);
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to mark review as manager reviewed', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    /**
     * Mark review as completed
     *
     * @param int|null $reviewedById User ID who completed the review
     * @return bool
     */

    // Scopes for filtering
    public function scopeByReviewableType($query, $type)
    {
        return $query->where('reviewable_type', $type);
    }

    public function scopeCreatedBetween($query, $from, $to)
    {
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);
        return $query;
    }

    public function scopeReviewedBetween($query, $from, $to)
    {
        if ($from) $query->whereDate('reviewed_at', '>=', $from);
        if ($to) $query->whereDate('reviewed_at', '<=', $to);
        return $query;
    }

    public function scopeEmployeeRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('employee_rating', '>=', $from);
        if ($to !== null) $query->where('employee_rating', '<=', $to);
        return $query;
    }

    public function scopePolicyConditionsRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('policy_conditions_rating', '>=', $from);
        if ($to !== null) $query->where('policy_conditions_rating', '<=', $to);
        return $query;
    }

    public function scopeServiceQualityRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('service_quality_rating', '>=', $from);
        if ($to !== null) $query->where('service_quality_rating', '<=', $to);
        return $query;
    }

    public function scopePricingRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('pricing_rating', '>=', $from);
        if ($to !== null) $query->where('pricing_rating', '<=', $to);
        return $query;
    }

    public function scopeProcessingTimeRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('processing_time_rating', '>=', $from);
        if ($to !== null) $query->where('processing_time_rating', '<=', $to);
        return $query;
    }

    public function scopeCollectionChannelRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('collection_channel_rating', '>=', $from);
        if ($to !== null) $query->where('collection_channel_rating', '<=', $to);
        return $query;
    }

    public function scopeInsuranceCompanyRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('insurance_company_rating', '>=', $from);
        if ($to !== null) $query->where('insurance_company_rating', '<=', $to);
        return $query;
    }

    public function scopeProviderRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('provider_rating', '>=', $from);
        if ($to !== null) $query->where('provider_rating', '<=', $to);
        return $query;
    }

    public function scopeClaimsSpecialistRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('claims_specialist_rating', '>=', $from);
        if ($to !== null) $query->where('claims_specialist_rating', '<=', $to);
        return $query;
    }

    public function scopeWiseRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('wise_rating', '>=', $from);
        if ($to !== null) $query->where('wise_rating', '<=', $to);
        return $query;
    }

    public function scopeHasEmployeeComment($query, $hasComment)
    {
        if ($hasComment !== null) {
            if ($hasComment) {
                $query->whereNotNull('client_employee_comment')
                      ->where('client_employee_comment', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('client_employee_comment')
                      ->orWhere('client_employee_comment', '=', '');
                });
            }
        }
        return $query;
    }

    public function scopeHasPolicyConditionsComment($query, $hasComment)
    {
        if ($hasComment !== null) {
            if ($hasComment) {
                $query->whereNotNull('policy_conditions_comment')
                      ->where('policy_conditions_comment', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('policy_conditions_comment')
                      ->orWhere('policy_conditions_comment', '=', '');
                });
            }
        }
        return $query;
    }


    public function scopeByReviewStatus($query, $isReviewed)
    {
        if ($isReviewed !== null) {
            $query->where('is_reviewed', $isReviewed);
        }
        return $query;
    }

    public function scopeNeedsManagerReview($query, $needsManagerReview)
    {
        if ($needsManagerReview !== null) {
            $query->where('need_manager_review', $needsManagerReview);
        }
        return $query;
    }

    /**
     * Export reviews data to Excel
     */
    public static function exportReviews(
        $reviewableType = null,
        $createdFrom = null,
        $createdTo = null,
        $isReviewed = null,
        $reviewedFrom = null,
        $reviewedTo = null,
        $employeeRatingFrom = null,
        $employeeRatingTo = null,
        $policyConditionsRatingFrom = null,
        $policyConditionsRatingTo = null,
        $hasEmployeeComment = null,
        $hasPolicyConditionsComment = null
    ) {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->is_admin) {
            return false;
        }

        $reviews = self::with(['reviewable', 'assignee', 'reviewedBy'])
            ->when($reviewableType, fn($q) => $q->byReviewableType($reviewableType))
            ->createdBetween($createdFrom, $createdTo)
            ->byReviewStatus($isReviewed)
            ->reviewedBetween($reviewedFrom, $reviewedTo)
            ->employeeRatingBetween($employeeRatingFrom, $employeeRatingTo)
            ->policyConditionsRatingBetween($policyConditionsRatingFrom, $policyConditionsRatingTo)
            ->hasEmployeeComment($hasEmployeeComment)
            ->hasPolicyConditionsComment($hasPolicyConditionsComment)
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'ID', 'Reviewable Type', 'Reviewable ID', 'Title', 'Description',
            'Assignee', 'Is Reviewed', 'Reviewed At', 'Reviewed By',
            'Employee Rating', 'Employee Comment', 
            'Policy Conditions Rating', 'Policy Conditions Comment',
            'Service Quality Rating', 'Service Quality Comment',
            'Pricing Rating', 'Pricing Comment',
            'Processing Time Rating', 'Processing Time Comment',
            'Collection Channel Rating', 'Collection Channel Comment',
            'Suggestions', 'Is Referred', 'Referral Comment',
            'Is Manager Reviewed', 'Need Manager Review', 'Manager Comment',
            'Created At', 'Updated At'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $activeSheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Data
        $row = 2;
        foreach ($reviews as $review) {
            $col = 'A';
            $activeSheet->setCellValue($col++ . $row, $review->id);
            $activeSheet->setCellValue($col++ . $row, class_basename($review->reviewable_type));
            $activeSheet->setCellValue($col++ . $row, $review->reviewable_id);
            $activeSheet->setCellValue($col++ . $row, $review->title);
            $activeSheet->setCellValue($col++ . $row, $review->desc);
            $activeSheet->setCellValue($col++ . $row, $review->assignee?->full_name);
            $activeSheet->setCellValue($col++ . $row, $review->is_reviewed ? 'Yes' : 'No');
            $activeSheet->setCellValue($col++ . $row, $review->reviewed_at?->format('Y-m-d H:i:s'));
            $activeSheet->setCellValue($col++ . $row, $review->reviewedBy?->full_name);
            
            // Client ratings and comments
            $activeSheet->setCellValue($col++ . $row, $review->employee_rating);
            $activeSheet->setCellValue($col++ . $row, $review->client_employee_comment);
            $activeSheet->setCellValue($col++ . $row, $review->policy_conditions_rating);
            $activeSheet->setCellValue($col++ . $row, $review->policy_conditions_comment);
            $activeSheet->setCellValue($col++ . $row, $review->service_quality_rating);
            $activeSheet->setCellValue($col++ . $row, $review->service_quality_comment);
            $activeSheet->setCellValue($col++ . $row, $review->pricing_rating);
            $activeSheet->setCellValue($col++ . $row, $review->pricing_comment);
            $activeSheet->setCellValue($col++ . $row, $review->processing_time_rating);
            $activeSheet->setCellValue($col++ . $row, $review->processing_time_comment);
            $activeSheet->setCellValue($col++ . $row, $review->collection_channel_rating);
            $activeSheet->setCellValue($col++ . $row, $review->collection_channel_comment);
            $activeSheet->setCellValue($col++ . $row, $review->suggestions);
            $activeSheet->setCellValue($col++ . $row, $review->is_referred ? 'Yes' : ($review->is_referred === false ? 'No' : ''));
            $activeSheet->setCellValue($col++ . $row, $review->referral_comment);
            $activeSheet->setCellValue($col++ . $row, $review->is_manager_reviewed ? 'Yes' : 'No');
            $activeSheet->setCellValue($col++ . $row, $review->need_manager_review ? 'Yes' : 'No');
            $activeSheet->setCellValue($col++ . $row, $review->manager_comment);
            
            // Timestamps
            $activeSheet->setCellValue($col++ . $row, $review->created_at->format('Y-m-d H:i:s'));
            $activeSheet->setCellValue($col++ . $row, $review->updated_at->format('Y-m-d H:i:s'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'reviews_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        $filePath = storage_path('exports/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /**
     * Check if this review is for a claim (Task with type 'claim')
     *
     * @return bool
     */
    public function getIsClaimReviewAttribute(): bool
    {
        return $this->reviewable_type === 'task' 
            && $this->reviewable 
            && $this->reviewable->type === 'claim';
    }

    /**
     * Set claim-specific ratings and comments
     *
     * @param float|null $insuranceCompanyRating
     * @param string|null $insuranceCompanyComment
     * @param float|null $providerRating
     * @param string|null $providerComment
     * @param float|null $claimsSpecialistRating
     * @param string|null $claimsSpecialistComment
     * @param float|null $wiseRating
     * @param string|null $wiseComment
     * @param int|null $reviewedById
     * @return bool
     */
    public function setClaimRatingsAndComments(
        ?float $insuranceCompanyRating = null,
        ?string $insuranceCompanyComment = null,
        ?float $providerRating = null,
        ?string $providerComment = null,
        ?float $claimsSpecialistRating = null,
        ?string $claimsSpecialistComment = null,
        ?float $wiseRating = null,
        ?string $wiseComment = null,
        ?int $reviewedById = null
    ): bool {
        /** @var User */
        $loggedInUser = Auth::user();
        
        // Allow admins to edit even if already reviewed, otherwise check normal permission
        if (!$loggedInUser->is_admin) {
            if (!$loggedInUser->can('receiveClientComment', $this)) {
                AppLog::error('Unauthorized attempt to update claim review ratings', desc: 'User does not have permission to update claim review ratings', loggable: $this);
                return false;
            }
        }

        try {
            $updates = [];

            if ($insuranceCompanyRating !== null) {
                $updates['insurance_company_rating'] = max(0, min(10, $insuranceCompanyRating));
            }
            
            if ($insuranceCompanyComment !== null) {
                $updates['insurance_company_comment'] = $insuranceCompanyComment;
            }
            
            if ($providerRating !== null) {
                $updates['provider_rating'] = max(0, min(10, $providerRating));
            }
            
            if ($providerComment !== null) {
                $updates['provider_comment'] = $providerComment;
            }
            
            if ($claimsSpecialistRating !== null) {
                $updates['claims_specialist_rating'] = max(0, min(10, $claimsSpecialistRating));
            }
            
            if ($claimsSpecialistComment !== null) {
                $updates['claims_specialist_comment'] = $claimsSpecialistComment;
            }
            
            if ($wiseRating !== null) {
                $updates['wise_rating'] = max(0, min(10, $wiseRating));
            }
            
            if ($wiseComment !== null) {
                $updates['wise_comment'] = $wiseComment;
            }

            // If any ratings or comments are being set, mark as reviewed
            if (!empty($updates)) {
                $updates['is_reviewed'] = true;
                $updates['reviewed_at'] = now();
                $updates['reviewed_by_id'] = $reviewedById ?? Auth::id();
                $updates['no_answer'] = 1; // Set to 1 (answered) when ratings/comments are provided
                
                // Check if any rating is less than 8 to set need_claim_manager_review
                $needClaimManagerReview = false;
                $claimRatingFields = [
                    'insurance_company_rating', 'provider_rating', 
                    'claims_specialist_rating', 'wise_rating'
                ];
                
                foreach ($claimRatingFields as $field) {
                    if (isset($updates[$field]) && $updates[$field] < 8) {
                        $needClaimManagerReview = true;
                        break;
                    }
                }
                
                $updates['need_claim_manager_review'] = $needClaimManagerReview;
            }

            $this->update($updates);
            $result = $this->save();
            
            AppLog::info('Claim review ratings and comments updated successfully', loggable: $this);
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to update claim review ratings and comments', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    /**
     * Mark claim review as claim manager reviewed
     *
     * @param int|null $reviewedById User ID who completed the claim manager review
     * @param string|null $claimManagerComment
     * @return bool
     */
    public function markAsClaimManagerReviewed(?int $reviewedById = null, ?string $claimManagerComment = null): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('markAsReviewed', $this)) {
            AppLog::error('Unauthorized attempt to mark claim review as claim manager reviewed', desc: 'User does not have permission to mark claim review as claim manager reviewed', loggable: $this);
            return false;
        }

        try {
            $updates = [
                'is_claim_manager_reviewed' => true,
                'need_claim_manager_review' => false,
            ];

            if ($claimManagerComment !== null) {
                $updates['claim_manager_comment'] = $claimManagerComment;
            }

            if ($reviewedById !== null) {
                $updates['reviewed_by_id'] = $reviewedById;
            }

            $this->update($updates);
            $result = $this->save();
            
            AppLog::info('Claim review marked as claim manager reviewed successfully', loggable: $this);
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to mark claim review as claim manager reviewed', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    /**
     * Set the no answer flag for a review
     *
     * @param int|null $noAnswer 0 = no answer, 1 = answered, 2 = sent whatsapp, 3 = wrong number, 4 = callback, null = no call yet
     * @return bool
     */
    public function setNoAnswerFlag(?int $noAnswer): bool
    {
        try {
            // Validate the value
            if ($noAnswer !== null && !in_array($noAnswer, [0, 1, 2, 3, 4])) {
                AppLog::error('Invalid no_answer value provided', [
                    'no_answer' => $noAnswer,
                    'review_id' => $this->id
                ], loggable: $this);
                return false;
            }
            
            $this->no_answer = $noAnswer;
            $result = $this->save();
            
            AppLog::info('Review no answer flag updated successfully', [
                'no_answer' => $noAnswer,
                'review_id' => $this->id
            ], loggable: $this);
            
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to update review no answer flag', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    /**
     * Delete a review (admin only)
     *
     * @return bool
     */
    public function deleteReview(): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->is_admin) {
            AppLog::error('Unauthorized attempt to delete review', desc: 'User does not have permission to delete reviews', loggable: $this);
            return false;
        }

        try {
            $reviewId = $this->id;
            $result = $this->delete();
            
            AppLog::info('Review deleted successfully', [
                'review_id' => $reviewId,
                'deleted_by' => $loggedInUser->id
            ]);
            
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to delete review', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }
}
