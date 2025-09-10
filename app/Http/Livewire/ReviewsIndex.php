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
    public $needs_manager_review = false;

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
    public $selectedReviewId;
    public $selectedReview;
    
    // Ratings modal form data
    public $form_employee_rating;
    public $form_employee_comment;
    public $form_company_rating;
    public $form_company_comment;
    
    // Manager modal form data
    public $manager_employee_rating;
    public $manager_employee_comment;
    public $manager_company_rating;
    public $manager_company_comment;

    public function mount()
    {
        $this->is_reviewed = false; // Default to unreviewed only
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

    public function toggleManagerReview()
    {
        $this->toggle($this->needs_manager_review);
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

    public function clearManagerReview()
    {
        $this->needs_manager_review = false;
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
                $this->has_company_comment,
                $this->needs_manager_review
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
        
        // Pre-fill with existing data if available
        $this->form_employee_rating = $this->selectedReview->employee_rating ?? null;
        $this->form_employee_comment = $this->selectedReview->client_employee_comment ?? '';
        $this->form_company_rating = $this->selectedReview->company_rating ?? null;
        $this->form_company_comment = $this->selectedReview->client_company_comment ?? '';
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
        
        // Pre-fill with existing manager data if available
        $this->manager_employee_rating = $this->selectedReview->manager_employee_rating ?? null;
        $this->manager_employee_comment = $this->selectedReview->manager_client_employee_comment ?? '';
        $this->manager_company_rating = $this->selectedReview->manager_company_rating ?? null;
        $this->manager_company_comment = $this->selectedReview->manager_client_company_comment ?? '';
    }

    public function closeManagerModal()
    {
        $this->showManagerModal = false;
        $this->resetManagerForm();
    }

    private function resetRatingsForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->form_employee_rating = null;
        $this->form_employee_comment = '';
        $this->form_company_rating = null;
        $this->form_company_comment = '';
    }

    private function resetManagerForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->manager_employee_rating = null;
        $this->manager_employee_comment = '';
        $this->manager_company_rating = null;
        $this->manager_company_comment = '';
    }

    // Review Action Methods
    public function setRatingsAndComments()
    {
        $this->validate([
            'form_employee_rating' => 'nullable|numeric|min:0|max:10',
            'form_company_rating' => 'nullable|numeric|min:0|max:10',
            'form_employee_comment' => 'nullable|string|max:1000',
            'form_company_comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->setRatingsAndComments(
                $this->form_employee_rating,
                $this->form_employee_comment,
                $this->form_company_rating,
                $this->form_company_comment
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

    public function markAsManagerReviewed()
    {
        $this->validate([
            'manager_employee_rating' => 'nullable|numeric|min:0|max:10',
            'manager_company_rating' => 'nullable|numeric|min:0|max:10',
            'manager_employee_comment' => 'nullable|string|max:1000',
            'manager_company_comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review = Review::findOrFail($this->selectedReviewId);
            
            if ($review->markAsManagerReviewed(
                null,
                $this->manager_employee_rating,
                $this->manager_employee_comment,
                $this->manager_company_rating,
                $this->manager_company_comment
            )) {
                $this->alert('success', 'Review marked as manager reviewed successfully.');
                $this->closeManagerModal();
            } else {
                $this->alert('failed', 'Failed to mark review as manager reviewed.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the review.');
        }
    }

    public function render()
    {
        $reviews = Review::with(['reviewable', 'assignee', 'reviewedBy'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%')
                      ->orWhere('client_employee_comment', 'like', '%' . $this->search . '%')
                      ->orWhere('client_company_comment', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->reviewable_type, fn($q) => $q->byReviewableType($this->reviewable_type))
            ->createdBetween($this->created_from, $this->created_to)
            ->byReviewStatus($this->is_reviewed)
            ->reviewedBetween($this->reviewed_from, $this->reviewed_to)
            ->employeeRatingBetween($this->employee_rating_from, $this->employee_rating_to)
            ->companyRatingBetween($this->company_rating_from, $this->company_rating_to)
            ->hasEmployeeComment($this->has_employee_comment)
            ->hasCompanyComment($this->has_company_comment)
            ->when($this->needs_manager_review, fn($q) => $q->needsManagerReview())
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('livewire.reviews-index', [
            'reviews' => $reviews
        ]);
    }
}