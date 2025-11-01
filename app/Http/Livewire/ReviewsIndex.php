<?php

namespace App\Http\Livewire;

use App\Models\Business\SoldPolicy;
use App\Models\Customers\Customer;
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
    public $service_quality_rating_from;
    public $service_quality_rating_to;
    public $pricing_rating_from;
    public $pricing_rating_to;
    public $processing_time_rating_from;
    public $processing_time_rating_to;
    public $collection_channel_rating_from;
    public $collection_channel_rating_to;
    public $policy_conditions_rating_from;
    public $policy_conditions_rating_to;
    public $insurance_company_rating_from;
    public $insurance_company_rating_to;
    public $provider_rating_from;
    public $provider_rating_to;
    public $claims_specialist_rating_from;
    public $claims_specialist_rating_to;
    public $wise_rating_from;
    public $wise_rating_to;
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
    public $Eservice_quality_rating_from;
    public $Eservice_quality_rating_to;
    public $Epricing_rating_from;
    public $Epricing_rating_to;
    public $Eprocessing_time_rating_from;
    public $Eprocessing_time_rating_to;
    public $Ecollection_channel_rating_from;
    public $Ecollection_channel_rating_to;
    public $Epolicy_conditions_rating_from;
    public $Epolicy_conditions_rating_to;
    public $Einsurance_company_rating_from;
    public $Einsurance_company_rating_to;
    public $Eprovider_rating_from;
    public $Eprovider_rating_to;
    public $Eclaims_specialist_rating_from;
    public $Eclaims_specialist_rating_to;
    public $Ewise_rating_from;
    public $Ewise_rating_to;

    // Available reviewable types
    public $reviewableTypes = [];

    // Modal states and form data
    public $showRatingsModal = false;
    public $showManagerModal = false;
    public $showClaimManagerModal = false;
    public $showNoAnswerModal = false;
    public $showInfoModal = false;
    public $showContactsModal = false;

    // Filter modal states
    public $serviceQualityRatingSection = false;
    public $pricingRatingSection = false;
    public $processingTimeRatingSection = false;
    public $collectionChannelRatingSection = false;
    public $policyConditionsRatingSection = false;
    public $insuranceCompanyRatingSection = false;
    public $providerRatingSection = false;
    public $claimsSpecialistRatingSection = false;
    public $wiseRatingSection = false;
    public $selectedReviewId;
    public $selectedReview;
    public $selectedReviewContacts = [];

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


    protected $listeners = ['showConfirmation'];

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

    public function showConfirmation($message, $color, $action, $reviewId = null)
    {
        if ($action === 'deleteReview' && $reviewId) {
            $this->selectedReviewId = $reviewId;
            $this->deleteReview();
        }
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

    public function toggleServiceQualityRating()
    {
        $this->toggle($this->serviceQualityRatingSection);
        if ($this->serviceQualityRatingSection) {
            $this->Eservice_quality_rating_from = $this->service_quality_rating_from;
            $this->Eservice_quality_rating_to = $this->service_quality_rating_to;
        }
    }

    public function togglePricingRating()
    {
        $this->toggle($this->pricingRatingSection);
        if ($this->pricingRatingSection) {
            $this->Epricing_rating_from = $this->pricing_rating_from;
            $this->Epricing_rating_to = $this->pricing_rating_to;
        }
    }

    public function toggleProcessingTimeRating()
    {
        $this->toggle($this->processingTimeRatingSection);
        if ($this->processingTimeRatingSection) {
            $this->Eprocessing_time_rating_from = $this->processing_time_rating_from;
            $this->Eprocessing_time_rating_to = $this->processing_time_rating_to;
        }
    }

    public function toggleCollectionChannelRating()
    {
        $this->toggle($this->collectionChannelRatingSection);
        if ($this->collectionChannelRatingSection) {
            $this->Ecollection_channel_rating_from = $this->collection_channel_rating_from;
            $this->Ecollection_channel_rating_to = $this->collection_channel_rating_to;
        }
    }

    public function togglePolicyConditionsRating()
    {
        $this->toggle($this->policyConditionsRatingSection);
        if ($this->policyConditionsRatingSection) {
            $this->Epolicy_conditions_rating_from = $this->policy_conditions_rating_from;
            $this->Epolicy_conditions_rating_to = $this->policy_conditions_rating_to;
        }
    }

    public function toggleInsuranceCompanyRating()
    {
        $this->toggle($this->insuranceCompanyRatingSection);
        if ($this->insuranceCompanyRatingSection) {
            $this->Einsurance_company_rating_from = $this->insurance_company_rating_from;
            $this->Einsurance_company_rating_to = $this->insurance_company_rating_to;
        }
    }

    public function toggleProviderRating()
    {
        $this->toggle($this->providerRatingSection);
        if ($this->providerRatingSection) {
            $this->Eprovider_rating_from = $this->provider_rating_from;
            $this->Eprovider_rating_to = $this->provider_rating_to;
        }
    }

    public function toggleClaimsSpecialistRating()
    {
        $this->toggle($this->claimsSpecialistRatingSection);
        if ($this->claimsSpecialistRatingSection) {
            $this->Eclaims_specialist_rating_from = $this->claims_specialist_rating_from;
            $this->Eclaims_specialist_rating_to = $this->claims_specialist_rating_to;
        }
    }

    public function toggleWiseRating()
    {
        $this->toggle($this->wiseRatingSection);
        if ($this->wiseRatingSection) {
            $this->Ewise_rating_from = $this->wise_rating_from;
            $this->Ewise_rating_to = $this->wise_rating_to;
        }
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

    public function setServiceQualityRating()
    {
        $this->service_quality_rating_from = $this->Eservice_quality_rating_from;
        $this->service_quality_rating_to = $this->Eservice_quality_rating_to;
        $this->toggle($this->serviceQualityRatingSection);
    }

    public function setPricingRating()
    {
        $this->pricing_rating_from = $this->Epricing_rating_from;
        $this->pricing_rating_to = $this->Epricing_rating_to;
        $this->toggle($this->pricingRatingSection);
    }

    public function setProcessingTimeRating()
    {
        $this->processing_time_rating_from = $this->Eprocessing_time_rating_from;
        $this->processing_time_rating_to = $this->Eprocessing_time_rating_to;
        $this->toggle($this->processingTimeRatingSection);
    }

    public function setCollectionChannelRating()
    {
        $this->collection_channel_rating_from = $this->Ecollection_channel_rating_from;
        $this->collection_channel_rating_to = $this->Ecollection_channel_rating_to;
        $this->toggle($this->collectionChannelRatingSection);
    }

    public function setPolicyConditionsRating()
    {
        $this->policy_conditions_rating_from = $this->Epolicy_conditions_rating_from;
        $this->policy_conditions_rating_to = $this->Epolicy_conditions_rating_to;
        $this->toggle($this->policyConditionsRatingSection);
    }

    public function setInsuranceCompanyRating()
    {
        $this->insurance_company_rating_from = $this->Einsurance_company_rating_from;
        $this->insurance_company_rating_to = $this->Einsurance_company_rating_to;
        $this->toggle($this->insuranceCompanyRatingSection);
    }

    public function setProviderRating()
    {
        $this->provider_rating_from = $this->Eprovider_rating_from;
        $this->provider_rating_to = $this->Eprovider_rating_to;
        $this->toggle($this->providerRatingSection);
    }

    public function setClaimsSpecialistRating()
    {
        $this->claims_specialist_rating_from = $this->Eclaims_specialist_rating_from;
        $this->claims_specialist_rating_to = $this->Eclaims_specialist_rating_to;
        $this->toggle($this->claimsSpecialistRatingSection);
    }

    public function setWiseRating()
    {
        $this->wise_rating_from = $this->Ewise_rating_from;
        $this->wise_rating_to = $this->Ewise_rating_to;
        $this->toggle($this->wiseRatingSection);
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

    public function clearServiceQualityRating()
    {
        $this->service_quality_rating_from = null;
        $this->service_quality_rating_to = null;
    }

    public function clearPricingRating()
    {
        $this->pricing_rating_from = null;
        $this->pricing_rating_to = null;
    }

    public function clearProcessingTimeRating()
    {
        $this->processing_time_rating_from = null;
        $this->processing_time_rating_to = null;
    }

    public function clearCollectionChannelRating()
    {
        $this->collection_channel_rating_from = null;
        $this->collection_channel_rating_to = null;
    }

    public function clearPolicyConditionsRating()
    {
        $this->policy_conditions_rating_from = null;
        $this->policy_conditions_rating_to = null;
    }

    public function clearInsuranceCompanyRating()
    {
        $this->insurance_company_rating_from = null;
        $this->insurance_company_rating_to = null;
    }

    public function clearProviderRating()
    {
        $this->provider_rating_from = null;
        $this->provider_rating_to = null;
    }

    public function clearClaimsSpecialistRating()
    {
        $this->claims_specialist_rating_from = null;
        $this->claims_specialist_rating_to = null;
    }

    public function clearWiseRating()
    {
        $this->wise_rating_from = null;
        $this->wise_rating_to = null;
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

    public function openNoAnswerModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::findOrFail($reviewId);
        $this->showNoAnswerModal = true;
    }

    public function closeNoAnswerModal()
    {
        $this->showNoAnswerModal = false;
        $this->resetNoAnswerForm();
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

    public function openContactsModal($reviewId)
    {
        $this->selectedReviewId = $reviewId;
        $this->selectedReview = Review::with(['reviewable'])->findOrFail($reviewId);
        $this->selectedReviewContacts = $this->getReviewContacts($this->selectedReview);
        $this->showContactsModal = true;
    }

    public function closeContactsModal()
    {
        $this->showContactsModal = false;
        $this->selectedReviewId = null;
        $this->selectedReview = null;
        $this->selectedReviewContacts = [];
    }

    private function getReviewContacts($review)
    {
        $contacts = [];

        if (!$review->reviewable) {
            return $contacts;
        }

        // Try to get client from direct relationship (SoldPolicy)
        if ($review->reviewable->client) {
            $client = $review->reviewable->client;
            $contacts[] = [
                'type' => 'Client',
                'name' => $client->name ?? 'Unknown',
                'phone' => $client->telephone1,
                'phone2' => $client->telephone2,
                'phone3' => $client->telephone3,
                'client_type' => 'customer',
                'client_id' => $client->id,
            ];
        }
        // Try to get client from indirect relationship (Task -> SoldPolicy -> Client)
        elseif ($review->reviewable->taskable && $review->reviewable->taskable->client) {
            $client = $review->reviewable->taskable->client;
            $contacts[] = [
                'type' => 'Client (via Task)',
                'name' => $client->name ?? 'Unknown',
                'phone' => $client->telephone1,
                'phone2' => $client->telephone2,
                'phone3' => $client->telephone3,
                'client_type' => 'customer',
                'client_id' => $client->id,
            ];
        }

        return $contacts;
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

    private function resetNoAnswerForm()
    {
        $this->selectedReviewId = null;
        $this->selectedReview = null;
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

    public function setNoAnswerFlag($noAnswer)
    {
        try {
            $review = Review::findOrFail($this->selectedReviewId);

            if ($review->setNoAnswerFlag($noAnswer)) {
                $message = match ($noAnswer) {
                    null => 'Review marked as not yet called.',
                    0 => 'Review marked as no answer.',
                    1 => 'Review marked as answered.',
                    2 => 'Review marked as sent WhatsApp.',
                    3 => 'Review marked as wrong number.',
                    4 => 'Review marked as callback.',
                    default => 'Review call status updated successfully.'
                };
                $this->alert('success', $message);
                $this->closeNoAnswerModal();
            } else {
                $this->alert('failed', 'Failed to update review call status.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while updating the review.');
        }
    }

    public function goToClaim($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);

            // Check if the reviewable is a claim (Task with type claim)
            if ($review->reviewable_type === 'task' && $review->reviewable) {
                $task = $review->reviewable;
                if ($task->type === 'claim') {
                    // Redirect to the claim/task page
                    $this->dispatchBrowserEvent('openNewTab', ['url' => route('tasks.show', ['id' => $task->id])]);
                    return;
                }
            }

            $this->alert('failed', 'This review is not associated with a claim.');
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while navigating to the claim.');
        }
    }

    public function goToPolicy($reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);

            // Check if the reviewable is a sold policy directly
            if ($review->reviewable_type === 'sold_policy') {
                $this->dispatchBrowserEvent('openNewTab', ['url' => route('sold.policy.show', ['id' => $review->reviewable_id])]);
                return;
            }

            // Check if the reviewable is a claim (Task with type claim) that has a taskable (sold policy)
            if ($review->reviewable_type === 'task' && $review->reviewable) {
                $task = $review->reviewable;
                if ($task->type === 'claim' && $task->taskable) {
                    // Redirect to the sold policy page
                    return redirect()->route('sold.policy.show', $task->taskable->id);
                }
            }

            $this->alert('failed', 'This review is not associated with a policy.');
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while navigating to the policy.');
        }
    }

    public function deleteReview()
    {
        if (false && !Auth::user()->is_admin) {
            $this->alert('failed', 'You do not have permission to delete reviews.');
            return;
        }

        try {
            $review = Review::findOrFail($this->selectedReviewId);

            if ($review->deleteReview()) {
                $this->alert('success', 'Review deleted successfully.');
            } else {
                $this->alert('failed', 'Failed to delete review.');
            }
        } catch (\Exception $e) {
            $this->alert('failed', 'An error occurred while deleting the review.');
        }
    }


    public function render()
    {
        $reviews = Review::with([
            'assignee',
            'reviewedBy'
        ])
            ->leftjoin('sold_policies', 'sold_policies.id', '=', 'reviewable_id')
            ->leftjoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', '=', Customer::MORPH_TYPE);
            })
            ->leftjoin('customer_phones', 'customer_phones.customer_id', '=', 'customers.id')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('desc', 'like', '%' . $this->search . '%')
                        ->orWhere(function ($q) {
                            $q->where('reviewable_type', '=', SoldPolicy::MORPH_TYPE)
                                ->where(function ($qq) {
                                    $qq->where('customers.first_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customers.last_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customers.middle_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customers.arabic_first_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customers.arabic_last_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customers.arabic_middle_name', 'like', '%' . $this->search . '%')
                                        ->orWhere('customer_phones.number', 'like', '%' . $this->search . '%');
                                });
                        });
                });
            })
            ->when($this->reviewable_type, fn($q) => $q->byReviewableType($this->reviewable_type))
            ->createdBetween($this->created_from, $this->created_to)
            ->byReviewStatus($this->is_reviewed)
            ->reviewedBetween($this->reviewed_from, $this->reviewed_to)
            ->employeeRatingBetween($this->employee_rating_from, $this->employee_rating_to)
            ->policyConditionsRatingBetween($this->policy_conditions_rating_from, $this->policy_conditions_rating_to)
            ->serviceQualityRatingBetween($this->service_quality_rating_from, $this->service_quality_rating_to)
            ->pricingRatingBetween($this->pricing_rating_from, $this->pricing_rating_to)
            ->processingTimeRatingBetween($this->processing_time_rating_from, $this->processing_time_rating_to)
            ->collectionChannelRatingBetween($this->collection_channel_rating_from, $this->collection_channel_rating_to)
            ->insuranceCompanyRatingBetween($this->insurance_company_rating_from, $this->insurance_company_rating_to)
            ->providerRatingBetween($this->provider_rating_from, $this->provider_rating_to)
            ->claimsSpecialistRatingBetween($this->claims_specialist_rating_from, $this->claims_specialist_rating_to)
            ->wiseRatingBetween($this->wise_rating_from, $this->wise_rating_to)
            ->hasEmployeeComment($this->has_employee_comment)
            ->hasPolicyConditionsComment($this->has_company_comment)
            ->needsManagerReview($this->need_manager_review)
            ->orderBy('reviews.created_at', 'reviews.desc')
            ->paginate(25);

        return view('livewire.reviews-index', [
            'reviews' => $reviews
        ]);
    }
}
