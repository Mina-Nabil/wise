<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Business\SoldPolicy;
use App\Models\Users\User;
use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Insurance\Company;
use App\Models\Payments\CommProfile;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SoldPolicyReport extends Component
{
    use WithPagination, ToggleSectionLivewire, AlertFrontEnd;

    public $startSection = false;
    public $expirySection = false;
    public $mainSalesSection = false;
    public $lobSection = false;
    public $valueSection = false;
    public $netPremSection = false;
    public $brandSection = false;
    public $companySection = false;
    public $PolicySection = false;
    public $paidSection = false;
    public $issuedSection = false;
    public $cancelTimeSection = false;
    public $bankPaymentTimeSection = false;
    public $mainSalesName;

    public $brands;
    public $searchBrand;

    public $companies;
    public $searchCompany;

    public $InsurancePolicies;
    public $searchPolicy;

    public $search;
    public $start_from;
    public $start_to;
    public $issued_from;
    public $issued_to;
    public $expiry_from;
    public $expiry_to;
    public $paid_from;
    public $paid_to;
    public $main_sales_id;
    public $line_of_business;
    public $value_from;
    public $value_to;
    public $net_premium_to;
    public $net_premium_from;
    public $brand_ids = [];
    public $company_ids = [];
    public $policy_ids = [];
    public $is_valid;
    public $is_paid;
    public $is_renewal;
    public $is_welcomed;
    public $is_penalized;
    public $is_cancelled;
    public $cancel_time_from;
    public $cancel_time_to;
    public $bank_payment_time_from;
    public $bank_payment_time_to;

    public $Estart_from;
    public $Estart_to;
    public $Eissued_from;
    public $Eissued_to;
    public $Eexpiry_from;
    public $Eexpiry_to;
    public $Epaid_from;
    public $Epaid_to;
    public $Emain_sales_id;
    public $Eline_of_business;
    public $Evalue_from;
    public $Evalue_to;
    public $Enet_premium_to;
    public $Enet_premium_from;
    public $Ebrand_ids = [];
    public $Ecompany_ids = [];
    public $Epolicy_ids = [];
    public $Ecancel_time_from;
    public $Ecancel_time_to;
    public $Ebank_payment_time_from;
    public $Ebank_payment_time_to;

    public $FilteredCreators = [];
    public $selectedCreators = [];
    public $creatorSection = false;
    public $usersSearchText;
    public $isAddCommProfiles = false;
    public $selectAUser;

    public $isWelcomedClientId;
    public $isWelcomedClientType;
    public $isWelcomed;
    public $welcomedNote;

    public $commProfilesSection;
    public $Eprofiles = [];
    public $profiles = [];

    public function selectChildrens()
    {
        $children = [];
        foreach ($this->selectedCreators as $creator) {
            $childUsers = User::find($creator['id'])->children_ids_array;
            $childUsers = array_filter($childUsers, function ($childId) use ($creator) {
                return $childId !== $creator['id'];
            });
            foreach ($childUsers as $childId) {
                $child = User::find($childId); // Fetch user data
                $children[$childId] = [
                    'id' => $childId,
                    'name' => $child->full_name, // Access the full_name property
                ];
            }
        }

        $this->selectedCreators = array_values(array_merge($this->selectedCreators, $children));
    }

    public function clearSelectedCreatorst()
    {
        $this->reset(['selectedCreators']);
    }

    public function openCreatorSection()
    {
        if (!empty($this->FilteredCreators)) {
            $this->selectedCreators = $this->FilteredCreators;
        }
        $this->creatorSection = true;
    }

    public function updatedSelectAUser($value)
    {
        $selectedCreator = User::findOrFail($value);

        $userExists = collect($this->selectedCreators)->contains('id', $selectedCreator->id);

        if (!$userExists) {
            $this->selectedCreators[] = [
                'id' => $selectedCreator->id,
                'name' => $selectedCreator->full_name,
            ];
        }

        $this->reset('selectAUser');
    }

    public function closeCreatorSection()
    {
        $this->creatorSection = false;
        $this->selectedCreators = [];
        $this->usersSearchText = null;
    }

    public function clearCreator()
    {
        $this->FilteredCreators = [];
    }

    public function setCtreators()
    {
        if (empty($this->selectedCreators)) {
            $this->FilteredCreators = [];
        } else {
            $this->FilteredCreators = $this->selectedCreators;
        }

        if ($this->isAddCommProfiles) {
            $commProfiles = CommProfile::byUserIds(array_column($this->selectedCreators, 'id'))->get();
            $this->Eprofiles = [];

            foreach ($commProfiles as $p) {
                $this->Eprofiles[] = json_encode([
                    'id' => $p->id,
                    'title' => $p->title,
                ]);
            }

            $this->FilteredCreators = [];
            $this->profiles = $this->Eprofiles;
        }
        $this->closeCreatorSection();
    }

    public function removeCreator($index)
    {
        unset($this->selectedCreators[$index]);
        $this->selectedCreators = array_values($this->selectedCreators);
    }

    public function toggleProfiles()
    {
        $this->toggle($this->commProfilesSection);
        if ($this->commProfilesSection) {
            $this->Eprofiles = $this->profiles;
        }
    }

    public function clearProfiles()
    {
        $this->profiles = [];
    }

    public function setProfiles()
    {
        $this->profiles = $this->Eprofiles;
        $this->toggle($this->commProfilesSection);
    }

    public function openEditIsWelcomed($id, $clientType)
    {
        $this->isWelcomedClientId = $id;
        $this->isWelcomedClientType = $clientType;
        $isWelcomed = 'no';

        if ($clientType === 'customer') {
            $client = Customer::findOrFail($id);
        } elseif ($clientType === 'corporate') {
            $client = Corporate::findOrFail($id);
        }

        $isWelcomed = $client->is_welcomed;
        $this->welcomedNote = $client->welcome_note;

        if ($isWelcomed) {
            $this->isWelcomed = 'yes';
        } else {
            $this->isWelcomed = 'no';
        }
    }

    public function closeEditIsWelcomed()
    {
        $this->isWelcomedClientId = null;
        $this->isWelcomedClientType = null;
        $this->isWelcomed = null;
        $this->welcomedNote = null;
    }

    public function updateIsWelcomed()
    {
        if ($this->isWelcomed === 'yes') {
            $status = true;
        } else {
            $status = false;
        }
        if ($this->isWelcomedClientType === 'customer') {
            $res = Customer::findOrFail($this->isWelcomedClientId)->setIsWelcomed($status, $this->welcomedNote);
        } elseif ($this->isWelcomedClientType === 'corporate') {
            $res = Corporate::findOrFail($this->isWelcomedClientId)->setIsWelcomed($status, $this->welcomedNote);
        }

        if ($res) {
            $this->closeEditIsWelcomed();
            $this->mount();
            $this->alert('success', 'Changed Welcome status!');
        } else {
            $this->alert('failed', 'server error!');
        }
    }

    public function updatedSearchCompany()
    {
        $this->companies = Company::when($this->searchCompany, fn($q) => $q->SearchBy($this->searchCompany))->get()->take(5);
    }

    public function clearpaid()
    {
        $this->is_paid = null;
    }

    public function clearvalid()
    {
        $this->is_valid = null;
    }

    public function clearcancelled()
    {
        $this->is_cancelled = null;
    }

    public function clearpenalized()
    {
        $this->is_penalized = null;
    }

    public function clearrenewal()
    {
        $this->is_renewal = null;
    }

    public function clearwelcomed()
    {
        $this->is_welcomed = null;
    }

    public function togglePaid()
    {
        $this->toggle($this->is_paid);
    }

    public function toggleCancelled()
    {
        $this->toggle($this->is_cancelled);
    }

    public function togglePenalized()
    {
        $this->toggle($this->is_penalized);
    }

    public function toggleRenewal()
    {
        $this->toggle($this->is_renewal);
    }

    public function toggleWelcomed()
    {
        $this->toggle($this->is_welcomed);
    }

    public function toggleValidated()
    {
        $this->toggle($this->is_valid);
    }

    public function togglePolicy()
    {
        $this->toggle($this->PolicySection);
        if ($this->PolicySection) {
            $this->Epolicy_ids = $this->policy_ids;
        }
    }

    public function updatedSearchPolicy()
    {
        $this->InsurancePolicies = Policy::tableData()->searchBy($this->searchPolicy)->take(5)->get();
    }

    public function pushPolicy($id)
    {
        $this->Epolicy_ids[] = $id;
    }

    public function setPolicy()
    {
        $this->policy_ids = $this->Epolicy_ids;
        $this->togglePolicy();
    }

    public function clearPolicy()
    {
        $this->policy_ids = [];
    }

    public function toggleCompany()
    {
        $this->toggle($this->companySection);
        if ($this->companySection) {
            $this->Ecompany_ids = $this->company_ids;
        }
    }

    public function pushCompany($id)
    {
        $this->Ecompany_ids[] = $id;
    }

    public function setCompany()
    {
        $this->company_ids = $this->Ecompany_ids;
        $this->toggleCompany();
    }

    public function clearCompany()
    {
        $this->company_ids = [];
    }

    public function pushBrand($id)
    {
        $this->Ebrand_ids[] = $id;
    }

    public function setBrands()
    {
        $this->brand_ids = $this->Ebrand_ids;
        $this->toggleBrands();
    }

    public function updatedSearchBrand()
    {
        $this->brands = Brand::where('name', 'like', '%' . $this->searchBrand . '%')
            ->take(5)
            ->get();
    }

    public function toggleBrands()
    {
        $this->toggle($this->brandSection);
        if ($this->brandSection) {
            $this->Ebrand_ids = $this->brand_ids;
        }
    }

    public function clearBrands()
    {
        $this->brand_ids = [];
    }

    public function toggleNetPrem()
    {
        $this->toggle($this->netPremSection);
        if ($this->netPremSection) {
            $this->Enet_premium_from = $this->net_premium_from;
            $this->Enet_premium_to = $this->net_premium_to;
        }
    }

    public function setNetPrem()
    {
        $this->net_premium_from = $this->Enet_premium_from;
        $this->net_premium_to = $this->Enet_premium_to;
        $this->toggle($this->netPremSection);
    }

    public function clearNetPrems()
    {
        $this->net_premium_from = null;
        $this->net_premium_to = null;
    }

    public function toggleValues()
    {
        $this->toggle($this->valueSection);
        if ($this->valueSection) {
            $this->Evalue_from = $this->value_from;
            $this->Evalue_to = $this->value_to;
        }
    }

    public function setValues()
    {
        $this->value_from = $this->Evalue_from;
        $this->value_to = $this->Evalue_to;
        $this->toggle($this->valueSection);
    }

    public function clearValues()
    {
        $this->value_from = null;
        $this->value_to = null;
    }

    public function toggleLob()
    {
        $this->toggle($this->lobSection);
        if ($this->lobSection) {
            $this->Eline_of_business = $this->line_of_business;
        }
    }

    public function setLob()
    {
        $this->line_of_business = $this->Eline_of_business;
        $this->toggle($this->lobSection);
    }

    public function clearLob()
    {
        $this->line_of_business = null;
    }

    public function toggleMainSales()
    {
        $this->toggle($this->mainSalesSection);
        if ($this->mainSalesSection) {
            $this->Emain_sales_id = $this->main_sales_id;
        }
    }

    public function setMainSales()
    {
        $this->main_sales_id = $this->Emain_sales_id;
        $this->toggle($this->mainSalesSection);
    }

    public function clearMainSales()
    {
        $this->main_sales_id = null;
    }

    public function toggleExpiryDate()
    {
        $this->toggle($this->expirySection);
        if ($this->expirySection) {
            $this->Eexpiry_from = Carbon::parse($this->expiry_from)->toDateString();
            $this->Eexpiry_to = Carbon::parse($this->expiry_to)->toDateString();
        }
    }

    public function setExpiryDates()
    {
        $this->expiry_from = Carbon::parse($this->Eexpiry_from);
        $this->expiry_to = Carbon::parse($this->Eexpiry_to);
        $this->toggle($this->expirySection);
    }

    public function clearExpiryDates()
    {
        $this->expiry_from = null;
        $this->expiry_to = null;
    }

    public function togglePaidDate()
    {
        $this->toggle($this->paidSection);
        if ($this->paidSection) {
            $this->Epaid_from = Carbon::parse($this->paid_from)->toDateString();
            $this->Epaid_to = Carbon::parse($this->paid_to)->toDateString();
        }
    }

    public function setPaidDates()
    {
        $this->paid_from = Carbon::parse($this->Epaid_from);
        $this->paid_to = Carbon::parse($this->Epaid_to);
        $this->toggle($this->paidSection);
    }

    public function clearPaidDates()
    {
        $this->paid_from = null;
        $this->paid_to = null;
    }

    public function toggleStartDate()
    {
        $this->toggle($this->startSection);
        if ($this->startSection) {
            $this->Estart_from = Carbon::parse($this->start_from)->toDateString();
            $this->Estart_to = Carbon::parse($this->start_to)->toDateString();
        }
    }

    public function setStartDates()
    {
        $this->start_from = Carbon::parse($this->Estart_from);
        $this->start_to = Carbon::parse($this->Estart_to);
        $this->toggle($this->startSection);
    }

    public function clearStartDates()
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    public function toggleIssuedDate()
    {
        $this->toggle($this->issuedSection);
        if ($this->issuedSection) {
            $this->Eissued_from = Carbon::parse($this->issued_from)->toDateString();
            $this->Eissued_to = Carbon::parse($this->issued_to)->toDateString();
        }
    }

    public function setIssuedDates()
    {
        $this->issued_from = Carbon::parse($this->Eissued_from);
        $this->issued_to = Carbon::parse($this->Eissued_to);
        $this->toggle($this->issuedSection);
    }

    public function clearIssuedDates()
    {
        $this->issued_from = null;
        $this->issued_to = null;
    }

    public function toggleCancelTime()
    {
        $this->toggle($this->cancelTimeSection);
        if ($this->cancelTimeSection) {
            $this->Ecancel_time_from = Carbon::parse($this->cancel_time_from)->toDateString();
            $this->Ecancel_time_to = Carbon::parse($this->cancel_time_to)->toDateString();
        }
    }

    public function setCancelTimeDates()
    {
        $this->cancel_time_from = Carbon::parse($this->Ecancel_time_from);
        $this->cancel_time_to = Carbon::parse($this->Ecancel_time_to);
        $this->toggle($this->cancelTimeSection);
    }

    public function clearCancelTimeDates()
    {
        $this->cancel_time_from = null;
        $this->cancel_time_to = null;
    }

    public function toggleBankPaymentTime()
    {
        $this->toggle($this->bankPaymentTimeSection);
    }

    public function setBankPaymentTimeDates()
    {
        $this->bank_payment_time_from = Carbon::parse($this->Ebank_payment_time_from);
        $this->bank_payment_time_to = Carbon::parse($this->Ebank_payment_time_to);
        $this->toggle($this->bankPaymentTimeSection);
    }

    public function clearBankPaymentTimeDates()
    {
        $this->bank_payment_time_from = null;
        $this->bank_payment_time_to = null;
    }

    public function exportReport()
    {
        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_column($this->FilteredCreators, 'id');
        } else {
            $creators_ids = [];
        }

        if (Auth::user()->is_admin) {
            return SoldPolicy::exportReport(
                $this->start_from,
                $this->start_to,
                $this->expiry_from,
                $this->expiry_to,
                $creators_ids,
                $this->line_of_business,
                $this->value_from,
                $this->value_to,
                $this->net_premium_to,
                $this->net_premium_from,
                $this->brand_ids,
                $this->company_ids,
                $this->policy_ids,
                $this->is_valid,
                $this->is_paid,
                $this->search,
                $this->is_renewal,
                $this->main_sales_id,
                $this->issued_from,
                $this->issued_to,
                collect($this->profiles)->map(fn($profile) => json_decode($profile, true)['id'])->all(),
                $this->is_welcomed,
                $this->is_penalized,
                $this->is_cancelled,
                $this->paid_from,
                $this->paid_to,
                $this->cancel_time_from,
                $this->cancel_time_to,
                $this->bank_payment_time_from,
                $this->bank_payment_time_to
            );
        }
    }

    public function exportHay2aReport()
    {
        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_column($this->FilteredCreators, 'id');
        } else {
            $creators_ids = [];
        }

        if (Auth::user()->can('viewCommission', SoldPolicy::class)) {
            return SoldPolicy::exportHay2aReport($this->start_from, $this->start_to, $this->expiry_from, $this->expiry_to, $creators_ids, $this->line_of_business, $this->value_from, $this->value_to, $this->net_premium_to, $this->net_premium_from, $this->brand_ids, $this->company_ids, $this->policy_ids, $this->is_valid, $this->is_paid, $this->search, $this->is_renewal, $this->main_sales_id, $this->issued_from, $this->issued_to, collect($this->profiles)->map(fn($profile) => json_decode($profile, true)['id'])->all(), $this->is_welcomed, $this->is_penalized, $this->is_cancelled, $this->paid_from, $this->paid_to, $this->cancel_time_from, $this->cancel_time_to);
        }
    }


    // Add review modal properties
    public $reviewModal = false;
    public $policyToReview = null;
    public $reviewStatus = false;
    public $reviewValidData = false;
    public $reviewComment = null;
    ///Review Functions

    // Add review modal methods
    public function openReviewModal($policyId)
    {
        $this->policyToReview = SoldPolicy::find($policyId);
        if ($this->policyToReview) {
            $this->reviewModal = true;
            $this->reviewStatus = $this->policyToReview->is_reviewed ?? false;
            $this->reviewValidData = $this->policyToReview->is_valid_data ?? false;
            $this->reviewComment = $this->policyToReview->review_comment;
        }
    }

    public function closeReviewModal()
    {
        $this->reviewModal = false;
        $this->policyToReview = null;
        $this->reviewStatus = false;
        $this->reviewValidData = false;
        $this->reviewComment = null;
    }

    public function saveReview()
    {
        if (!$this->policyToReview) {
            $this->alert('failed', 'Policy not found');
            return;
        }

        $this->validate([
            'reviewComment' => 'nullable|string|max:500',
        ]);

        $res = $this->policyToReview->setIsReviewed(
            $this->reviewStatus,
            $this->reviewValidData,
            $this->reviewComment
        );

        if ($res !== false) {
            $this->closeReviewModal();
            $this->alert('success', 'Review status updated successfully!');
        } else {
            $this->alert('failed', 'You do not have permission to review this policy.');
        }
    }

    public function mount()
    {
        $this->brands = Brand::all()->take(5);
        $this->companies = Company::when($this->searchCompany, fn($q) => $q->SearchBy($this->searchCompany))->get()->take(5);
        $this->InsurancePolicies = Policy::all()->take(5);
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->main_sales_id) {
            $c = User::find($this->main_sales_id);
            $this->mainSalesName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        if ($this->commProfilesSection) {
            $COMM_PROFILES = CommProfile::select('title', 'id')->get();
        } else {
            $COMM_PROFILES = null;
        }

        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;

        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_column($this->FilteredCreators, 'id'); // Directly assign the array of ids
        } else {
            $creators_ids = [];
        }

        if ($this->creatorSection) {
            $users = User::search($this->usersSearchText)->get();
        } else {
            $users = User::all();
        }

        $policies = SoldPolicy::report(
            $this->start_from,
            $this->start_to,
            $this->expiry_from,
            $this->expiry_to,
            $creators_ids,
            $this->line_of_business,
            $this->value_from,
            $this->value_to,
            $this->net_premium_to,
            $this->net_premium_from,
            $this->brand_ids,
            $this->company_ids,
            $this->policy_ids,
            $this->is_valid,
            $this->is_paid,
            $this->search,
            $this->is_renewal,
            $this->main_sales_id,
            $this->issued_from,
            $this->issued_to,
            collect($this->profiles)->map(fn($profile) => json_decode($profile, true)['id'])->all(),
            $this->is_welcomed,
            $this->is_penalized,
            $this->is_cancelled,
            $this->paid_from,
            $this->paid_to,
            $this->cancel_time_from,
            $this->cancel_time_to,
            $this->bank_payment_time_from,
            $this->bank_payment_time_to
        )->simplePaginate(30);
        return view('livewire.sold-policy-report', [
            'policies' => $policies,
            'users' => $users,
            'COMM_PROFILES' => $COMM_PROFILES,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
        ]);
    }
}
