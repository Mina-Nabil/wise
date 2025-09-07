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
        'company_rating',
        'client_company_comment',
        'reviewed_by_id',
    ];

    protected $casts = [
        'is_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
        'employee_rating' => 'decimal:1',
        'company_rating' => 'decimal:1',
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

        try {
            $review = static::create([
                'reviewable_type' => get_class($reviewable),
                'reviewable_id' => $reviewable->id,
                'title' => $title,
                'desc' => $description,
                'assignee_id' => $assigneeId,
                'is_reviewed' => false,
            ]);
            
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
     * @param float|null $companyRating Rating for company (0-10)
     * @param string|null $companyComment Client comment about company
     * @param int|null $reviewedById User ID who completed the review
     * @return bool
     */
    public function setRatingsAndComments(
        ?float $employeeRating = null,
        ?string $employeeComment = null,
        ?float $companyRating = null,
        ?string $companyComment = null,
        ?int $reviewedById = null
    ): bool {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('receiveClientComment', $this)) {
            AppLog::error('Unauthorized attempt to update review ratings', desc: 'User does not have permission to update review ratings', loggable: $this);
            return false;
        }

        try {
            $updates = [];
            
            if ($employeeRating !== null) {
                $updates['employee_rating'] = max(0, min(10, $employeeRating)); // Ensure rating is between 0-10
            }
            
            if ($employeeComment !== null) {
                $updates['client_employee_comment'] = $employeeComment;
            }
            
            if ($companyRating !== null) {
                $updates['company_rating'] = max(0, min(10, $companyRating)); // Ensure rating is between 0-10
            }
            
            if ($companyComment !== null) {
                $updates['client_company_comment'] = $companyComment;
            }
            
            if ($reviewedById !== null) {
                $updates['reviewed_by_id'] = $reviewedById;
            }

            // If any ratings or comments are being set, mark as reviewed
            if (!empty($updates)) {
                $updates['is_reviewed'] = true;
                $updates['reviewed_at'] = now();
                $updates['reviewed_by_id'] = $reviewedById ?? Auth::id();
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
     * Mark review as completed
     *
     * @param int|null $reviewedById User ID who completed the review
     * @return bool
     */
    public function markAsManagerReviewed(?int $reviewedById = null, ?float $employeeRating = null, ?string $employeeComment = null, ?float $companyRating = null, ?string $companyComment = null): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('markAsReviewed', $this)) {
            AppLog::error('Unauthorized attempt to mark review as completed', desc: 'User does not have permission to update review status', loggable: $this);
            return false;
        }

        try {
            $this->update([
                'is_manager_reviewed' => true,
                'manager_reviewed_at' => now(),
                'manager_reviewed_by_id' => $reviewedById ?? Auth::id(),
                'manager_employee_rating' => $employeeRating,
                'manager_client_employee_comment' => $employeeComment,
                'manager_company_rating' => $companyRating,
                'manager_client_company_comment' => $companyComment,
            ]);
            
            $result = $this->save();
            AppLog::info('Manager review marked as completed', loggable: $this);
            return $result;
        } catch (Exception $e) {
            AppLog::error('Failed to mark review as completed', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

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

    public function scopeCompanyRatingBetween($query, $from, $to)
    {
        if ($from !== null) $query->where('company_rating', '>=', $from);
        if ($to !== null) $query->where('company_rating', '<=', $to);
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

    public function scopeHasCompanyComment($query, $hasComment)
    {
        if ($hasComment !== null) {
            if ($hasComment) {
                $query->whereNotNull('client_company_comment')
                      ->where('client_company_comment', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('client_company_comment')
                      ->orWhere('client_company_comment', '=', '');
                });
            }
        }
        return $query;
    }

    public function scopeNeedsManagerReview($query)
    {
        return $query->where('is_reviewed', true)
                    ->where('is_manager_reviewed', false)
                    ->where(function ($q) {
                        $q->where('employee_rating', '<', 8)
                          ->orWhere('company_rating', '<', 8);
                    });
    }

    public function scopeByReviewStatus($query, $isReviewed)
    {
        if ($isReviewed !== null) {
            $query->where('is_reviewed', $isReviewed);
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
        $companyRatingFrom = null,
        $companyRatingTo = null,
        $hasEmployeeComment = null,
        $hasCompanyComment = null,
        $needsManagerReview = false
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
            ->companyRatingBetween($companyRatingFrom, $companyRatingTo)
            ->hasEmployeeComment($hasEmployeeComment)
            ->hasCompanyComment($hasCompanyComment)
            ->when($needsManagerReview, fn($q) => $q->needsManagerReview())
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'ID', 'Reviewable Type', 'Reviewable ID', 'Title', 'Description',
            'Assignee', 'Is Reviewed', 'Reviewed At', 'Reviewed By',
            'Employee Rating', 'Employee Comment', 'Company Rating', 'Company Comment',
            'Needs Manager Review', 'Is Manager Reviewed', 'Manager Reviewed At',
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
            $needsManagerReview = ($review->is_reviewed && !$review->is_manager_reviewed && 
                                 ($review->employee_rating < 8 || $review->company_rating < 8));

            $activeSheet->setCellValue('A' . $row, $review->id);
            $activeSheet->setCellValue('B' . $row, class_basename($review->reviewable_type));
            $activeSheet->setCellValue('C' . $row, $review->reviewable_id);
            $activeSheet->setCellValue('D' . $row, $review->title);
            $activeSheet->setCellValue('E' . $row, $review->desc);
            $activeSheet->setCellValue('F' . $row, $review->assignee?->full_name);
            $activeSheet->setCellValue('G' . $row, $review->is_reviewed ? 'Yes' : 'No');
            $activeSheet->setCellValue('H' . $row, $review->reviewed_at?->format('Y-m-d H:i:s'));
            $activeSheet->setCellValue('I' . $row, $review->reviewedBy?->full_name);
            $activeSheet->setCellValue('J' . $row, $review->employee_rating);
            $activeSheet->setCellValue('K' . $row, $review->client_employee_comment);
            $activeSheet->setCellValue('L' . $row, $review->company_rating);
            $activeSheet->setCellValue('M' . $row, $review->client_company_comment);
            $activeSheet->setCellValue('N' . $row, $needsManagerReview ? 'Yes' : 'No');
            $activeSheet->setCellValue('O' . $row, $review->is_manager_reviewed ? 'Yes' : 'No');
            $activeSheet->setCellValue('P' . $row, $review->manager_reviewed_at?->format('Y-m-d H:i:s'));
            $activeSheet->setCellValue('Q' . $row, $review->created_at->format('Y-m-d H:i:s'));
            $activeSheet->setCellValue('R' . $row, $review->updated_at->format('Y-m-d H:i:s'));
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
}
