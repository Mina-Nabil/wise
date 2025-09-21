<?php

namespace App\Http\Livewire;

use App\Models\Marketing\Review;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewsIndex extends Component
{
    use WithPagination, ToggleSectionLivewire, AlertFrontEnd;

    // Filter sections
    public $reviewableTypeSection = false;
    public $createdDateSection = false;
    public $reviewDateSection = false;
    public $employeeRatingSection = false;
    public $companyRatingSection = false;

    // Filter values
    public $search;
    public $reviewable_type;
    public $created_from;
    public $created_to;
    public $is_reviewed;
    public $reviewed_from;
    public $reviewed_to;
    public $employee_rating_from;
    public $employee_rating_to;
    public $company_rating_from;
    public $company_rating_to;
    public $has_employee_comment;
    public $has_company_comment;
    public $need_manager_review;

    // Edit filter values
    public $Ecreated_from;
    public $Ecreated_to;
    public $Ereviewed_from;
    public $Ereviewed_to;
    public $Eemployee_rating_from;
    public $Eemployee_rating_to;
    public $Ecompany_rating_from;
    public $Ecompany_rating_to;

    // Available reviewable types
    public $reviewableTypes = [];

    // Modal states and form data
    public $showRatingsModal = false;
    public $showManagerModal = false;
    public $showClaimManagerModal = false;
    public $showInfoModal = false;
    public $selectedReviewId;
    public $selectedReview;
    
    // Ratings modal form data
    public $form_employee_rating;
    public $form_employee_comment;
    public $form_policy_conditions_rating;
    public $form_policy_conditions_comment;
    public $form_service_quality_rating;
    public $form_service_quality_comment;
    public $form_pricing_rating;
    public $form_pricing_comment;
    public $form_processing_time_rating;
    public $form_processing_time_comment;
    public $form_collection_channel_rating;
    public $form_collection_channel_comment;
    public $form_suggestions;
    public $form_is_referred;
    public $form_referral_comment;
    
    // Claim-specific ratings modal form data
    public $form_insurance_company_rating;
    public $form_insurance_company_comment;
    public $form_provider_rating;
    public $form_provider_comment;
    public $form_claims_specialist_rating;
    public $form_claims_specialist_comment;
    public $form_wise_rating;
    public $form_wise_comment;
    
    // Manager modal form data
    public $manager_comment;
    
    // Claim manager modal form data
    public $claim_manager_comment;
    

    public function mount()
    {
        $this->is_reviewed = false; // Default to unreviewed only
        
        // Set need_manager_review to true by default if user can review reviews
        if (Auth::user() && Auth::user()->can_review_reviews) {
            $this->is_reviewed = true;
            $this->need_manager_review = true;
        }
        
        $this->loadReviewableTypes();
    }

    public function loadReviewableTypes()
    {
        $this->reviewableTypes = Review::distinct()
            ->whereNotNull('reviewable_type')
            ->pluck('reviewable_type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => class_basename($type)
                ];
            })
            ->toArray();
    }

    // Toggle methods for sections
    public function toggleReviewableType()
    {
        $this->toggle($this->reviewableTypeSection);
    }

    public function toggleCreatedDate()
    {
        $this->toggle($this->createdDateSection);
        if ($this->createdDateSection) {
            $this->Ecreated_from = $this->created_from ? Carbon::parse($this->created_from)->toDateString() : null;
            $this->Ecreated_to = $this->created_to ? Carbon::parse($this->created_to)->toDateString() : null;
        }
    }

    public function toggleReviewDate()
    {
        $this->toggle($this->reviewDateSection);
        if ($this->reviewDateSection) {
            $this->Ereviewed_from = $this->reviewed_from ? Carbon::parse($this->reviewed_from)->toDateString() : null;
            $this->Ereviewed_to = $this->reviewed_to ? Carbon::parse($this->reviewed_to)->toDateString() : null;
        }
    }

    public function toggleEmployeeRating()
    {
        $this->toggle($this->employeeRatingSection);
        if ($this->employeeRatingSection) {
            $this->Eemployee_rating_from = $this->employee_rating_from;
            $this->Eemployee_rating_to = $this->employee_rating_to;
        }
    }

    public function toggleCompanyRating()
    {
        $this->toggle($this->companyRatingSection);
        if ($this->companyRatingSection) {
            $this->Ecompany_rating_from = $this->company_rating_from;
            $this->Ecompany_rating_to = $this->company_rating_to;
        }
    }

    public function toggleReviewStatus()
    {
        $this->toggle($this->is_reviewed);
    }

    public function toggleEmployeeComment()
    {
        $this->toggle($this->has_employee_comment);
    }

    public function toggleCompanyComment()
    {
        $this->toggle($this->has_company_comment);
    }

    public function toggleNeedManagerReview()
    {
        $this->toggle($this->need_manager_review);
    }


    // Set methods for filters
    public function setCreatedDates()
    {
        $this->created_from = $this->Ecreated_from ? Carbon::parse($this->Ecreated_from) : null;
        $this->created_to = $this->Ecreated_to ? Carbon::parse($this->Ecreated_to) : null;
        $this->toggle($this->createdDateSection);
    }

    public function setReviewDates()
    {
        $this->reviewed_from = $this->Ereviewed_from ? Carbon::parse($this->Ereviewed_from) : null;
        $this->reviewed_to = $this->Ereviewed_to ? Carbon::parse($this->Ereviewed_to) : null;
        $this->toggle($this->reviewDateSection);
    }

    public function setEmployeeRating()
    {
        $this->employee_rating_from = $this->Eemployee_rating_from;
        $this->employee_rating_to = $this->Eemployee_rating_to;
        $this->toggle($this->employeeRatingSection);
    }

    public function setCompanyRating()
    {
        $this->company_rating_from = $this->Ecompany_rating_from;
        $this->company_rating_to = $this->Ecompany_rating_to;
        $this->toggle($this->companyRatingSection);
    }

    // Clear methods for filters
    public function clearReviewableType()
    {
        $this->reviewable_type = null;
    }

    public function clearCreatedDates()
    {
        $this->created_from = null;
        $this->created_to = null;
    }

    public function clearReviewDates()
    {
        $this->reviewed_from = null;
        $this->reviewed_to = null;
    }

    public function clearEmployeeRating()
    {
        $this->employee_rating_from = null;
        $this->employee_rating_to = null;
    }

    public function clearCompanyRating()
    {
        $this->company_rating_from = null;
        $this->company_rating_to = null;
    }

    public function clearReviewStatus()
    {
        $this->is_reviewed = null;
    }

    public function clearEmployeeComment()
    {
        $this->has_employee_comment = null;
    }

    public function clearCompanyComment()
    {
        $this->has_company_comment = null;
    }

    public function clearNeedManagerReview()
    {
        $this->need_manager_review = null;
    }


    // Export functionality
    public function exportReviews()
    {
        if (!Auth::user()->is_admin) {
            $this->alert('failed', 'You do not have permission to export reviews.');
            return;
        }

        try {
            return Review::exportReviews(
                $this->reviewable_type,
                $this->created_from,
                $this->created_to,
                $this->is_reviewed,
                $this->reviewed_from,
                $this->reviewed_to,
                $this->employee_rating_from,
                $this->employee_rating_to,
                $this->company_rating_from,
                $this->company_rating_to,
                $this->has_employee_comment,
                $this->has_company_comment
            );
        } catch (\Exception $e) {
            $this->alert('failed', 'Export failed. Please try again.');
        }
    }

    // Reset page when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Modal Methods
    public function openRatingsModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::findOrFail($reviewId);
        $this->showRatingsModal = true;
        
        if ($this->selectedReview->is_claim_review) {
            // Pre-fill claim-specific data
            $this->form_insurance_company_rating = $this->selectedReview->insurance_company_rating ?? null;
            $this->form_insurance_company_comment = $this->selectedReview->insurance_company_comment ?? '';
            $this->form_provider_rating = $this->selectedReview->provider_rating ?? null;
            $this->form_provider_comment = $this->selectedReview->provider_comment ?? '';
            $this->form_claims_specialist_rating = $this->selectedReview->claims_specialist_rating ?? null;
            $this->form_claims_specialist_comment = $this->selectedReview->claims_specialist_comment ?? '';
            $this->form_wise_rating = $this->selectedReview->wise_rating ?? null;
            $this->form_wise_comment = $this->selectedReview->wise_comment ?? '';
        } else {
            // Pre-fill sold policy data
            $this->form_employee_rating = $this->selectedReview->employee_rating ?? null;
            $this->form_employee_comment = $this->selectedReview->client_employee_comment ?? '';
            $this->form_policy_conditions_rating = $this->selectedReview->policy_conditions_rating ?? null;
            $this->form_policy_conditions_comment = $this->selectedReview->policy_conditions_comment ?? '';
            $this->form_service_quality_rating = $this->selectedReview->service_quality_rating ?? null;
            $this->form_service_quality_comment = $this->selectedReview->service_quality_comment ?? '';
            $this->form_pricing_rating = $this->selectedReview->pricing_rating ?? null;
            $this->form_pricing_comment = $this->selectedReview->pricing_comment ?? '';
            $this->form_processing_time_rating = $this->selectedReview->processing_time_rating ?? null;
            $this->form_processing_time_comment = $this->selectedReview->processing_time_comment ?? '';
            $this->form_collection_channel_rating = $this->selectedReview->collection_channel_rating ?? null;
            $this->form_collection_channel_comment = $this->selectedReview->collection_channel_comment ?? '';
            $this->form_suggestions = $this->selectedReview->suggestions ?? '';
            $this->form_is_referred = $this->selectedReview->is_referred ?? null;
            $this->form_referral_comment = $this->selectedReview->referral_comment ?? '';
        }
    }

    public function closeRatingsModal()
    {
        $this->showRatingsModal = false;
        $this->resetRatingsForm();
    }

    public function openManagerModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::findOrFail($reviewId);
        $this->showManagerModal = true;
        
        // Pre-fill with existing manager comment if available
        $this->manager_comment = $this->selectedReview->manager_comment ?? '';
    }

    public function closeManagerModal()
    {
        $this->showManagerModal = false;
        $this->resetManagerForm();
    }

    public function openClaimManagerModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::findOrFail($reviewId);
        $this->showClaimManagerModal = true;
        
        // Pre-fill with existing claim manager comment if available
        $this->claim_manager_comment = $this->selectedReview->claim_manager_comment ?? '';
    }

    public function closeClaimManagerModal()
    {
        $this->showClaimManagerModal = false;
        $this->resetClaimManagerForm();
    }

    public function openInfoModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::with(['reviewable', 'assignee', 'reviewedBy'])->findOrFail($reviewId);
        $this->showInfoModal = true;
    }

    public function closeInfoModal()
    {
        $this->showInfoModal = false;
        $this->selectedReviewId = null;
        $this->selectedReview = null;
    }


    private function resetRatingsForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->form_employee_rating = null;
        $this->form_employee_comment = '';
        $this->form_policy_conditions_rating = null;
        $this->form_policy_conditions_comment = '';
        $this->form_service_quality_rating = null;
        $this->form_service_quality_comment = '';
        $this->form_pricing_rating = null;
        $this->form_pricing_comment = '';
        $this->form_processing_time_rating = null;
        $this->form_processing_time_comment = '';
        $this->form_collection_channel_rating = null;
        $this->form_collection_channel_comment = '';
        $this->form_suggestions = '';
        $this->form_is_referred = null;
        $this->form_referral_comment = '';
        
        // Reset claim-specific fields
        $this->form_insurance_company_rating = null;
        $this->form_insurance_company_comment = '';
        $this->form_provider_rating = null;
        $this->form_provider_comment = '';
        $this->form_claims_specialist_rating = null;
        $this->form_claims_specialist_comment = '';
        $this->form_wise_rating = null;
        $this->form_wise_comment = '';
    }

    private function resetManagerForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->manager_comment = '';
    }

    private function resetClaimManagerForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->claim_manager_comment = '';
    }


    // Review Action Methods
    public function setRatingsAndComments()
    {
        $this->validate([
            'form_employee_rating' => 'nullable|numeric|min:0|max:10',
            'form_employee_comment' => 'nullable|string|max:1000',
            'form_policy_conditions_rating' => 'nullable|numeric|min:0|max:10',
            'form_policy_conditions_comment' => 'nullable|string|max:1000',
            'form_service_quality_rating' => 'nullable|numeric|min:0|max:10',
            'form_service_quality_comment' => 'nullable|string|max:1000',
            'form_pricing_rating' => 'nullable|numeric|min:0|max:10',
            'form_pricing_comment' => 'nullable|string|max:1000',
            'form_processing_time_rating' => 'nullable|numeric|min:0|max:10',
            'form_processing_time_comment' => 'nullable|string|max:1000',
            'form_collection_channel_rating' => 'nullable|numeric|min:0|max:10',
            'form_collection_channel_comment' => 'nullable|string|max:1000',
            'form_suggestions' => 'nullable|string|max:2000',
            'form_is_referred' => 'nullable|boolean',
            'form_referral_comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->setRatingsAndComments(
                $this->form_employee_rating,
                $this->form_employee_comment,
                $this->form_policy_conditions_rating,
                $this->form_policy_conditions_comment,
                $this->form_service_quality_rating,
                $this->form_service_quality_comment,
                $this->form_pricing_rating,
                $this->form_pricing_comment,
                $this->form_processing_time_rating,
                $this->form_processing_time_comment,
                $this->form_collection_channel_rating,
                $this->form_collection_channel_comment,
                $this->form_suggestions,
                $this->form_is_referred,
                $this->form_referral_comment
            )) {
                $this->alert('success', 'Review ratings and comments updated successfully.');
                $this->closeRatingsModal();
            } else {
                $this->alert('failed', 'Failed to update review ratings and comments.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the review.');
        }
    }

    public function setClaimRatingsAndComments()
    {
        $this->validate([
            'form_insurance_company_rating' => 'nullable|numeric|min:0|max:10',
            'form_insurance_company_comment' => 'nullable|string|max:1000',
            'form_provider_rating' => 'nullable|numeric|min:0|max:10',
            'form_provider_comment' => 'nullable|string|max:1000',
            'form_claims_specialist_rating' => 'nullable|numeric|min:0|max:10',
            'form_claims_specialist_comment' => 'nullable|string|max:1000',
            'form_wise_rating' => 'nullable|numeric|min:0|max:10',
            'form_wise_comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->setClaimRatingsAndComments(
                $this->form_insurance_company_rating,
                $this->form_insurance_company_comment,
                $this->form_provider_rating,
                $this->form_provider_comment,
                $this->form_claims_specialist_rating,
                $this->form_claims_specialist_comment,
                $this->form_wise_rating,
                $this->form_wise_comment
            )) {
                $this->alert('success', 'Claim review ratings and comments updated successfully.');
                $this->closeRatingsModal();
            } else {
                $this->alert('failed', 'Failed to update claim review ratings and comments.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the claim review.');
        }
    }

    public function markAsManagerReviewed()
    {
        $this->validate([
            'manager_comment' => 'nullable|string|max:2000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->markAsManagerReviewed(null, $this->manager_comment)) {
                $this->alert('success', 'Review marked as manager reviewed successfully.');
                $this->closeManagerModal();
            } else {
                $this->alert('failed', 'Failed to mark review as manager reviewed.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the review.');
        }
    }

    public function markAsClaimManagerReviewed()
    {
        $this->validate([
            'claim_manager_comment' => 'nullable|string|max:2000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->markAsClaimManagerReviewed(null, $this->claim_manager_comment)) {
                $this->alert('success', 'Claim review marked as claim manager reviewed successfully.');
                $this->closeClaimManagerModal();
            } else {
                $this->alert('failed', 'Failed to mark claim review as claim manager reviewed.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the claim review.');
        }
    }

    public function goToClaim($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);
            
            // Check if the reviewable is a claim (Task with type claim)
            if ($review->reviewable_type === 'App\Models\Tasks\Task' && $review->reviewable) {
                $task = $review->reviewable;
                if ($task->type === 'claim') {
                    // Redirect to the claim/task page
                    return redirect()->route('tasks.show', $task->id);
                }
            }
            
            $this->alert('failed', 'This review is not associated with a claim.');
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while navigating to the claim.');
        }
    }


    public function render()
    {
        $reviews = Review::with([
            'assignee', 
            'reviewedBy'
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%')
                      ->orWhere('client_employee_comment', 'like', '%' . $this->search . '%')
                      ->orWhere('policy_conditions_comment', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->reviewable_type, fn($q) => $q->byReviewableType($this->reviewable_type))
            ->createdBetween($this->created_from, $this->created_to)
            ->byReviewStatus($this->is_reviewed)
            ->reviewedBetween($this->reviewed_from, $this->reviewed_to)
            ->employeeRatingBetween($this->employee_rating_from, $this->employee_rating_to)
            ->policyConditionsRatingBetween($this->company_rating_from, $this->company_rating_to)
            ->hasEmployeeComment($this->has_employee_comment)
            ->hasPolicyConditionsComment($this->has_company_comment)
            ->needsManagerReview($this->need_manager_review)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('livewire.reviews-index', [
            'reviews' => $reviews
        ]);
    }
}