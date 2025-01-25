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
    public $issuedSection = false;
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

    public $Estart_from;
    public $Estart_to;
    public $Eissued_from;
    public $Eissued_to;
    public $Eexpiry_from;
    public $Eexpiry_to;
    public $Emain_sales_id;
    public $Eline_of_business;
    public $Evalue_from;
    public $Evalue_to;
    public $Enet_premium_to;
    public $Enet_premium_from;
    public $Ebrand_ids = [];
    public $Ecompany_ids = [];
    public $Epolicy_ids = [];

    public $FilteredCreators = [];
    public $selectedCreators = [];
    public $creatorSection = false;
    public $usersSearchText;



    public $isWelcomedClientId;
    public $isWelcomedClientType;
    public $isWelcomed;
    public $welcomedNote;

    public $commProfilesSection;
    public $Eprofiles = [];
    public $profiles = [];

    public function openCreatorSection()
    {
        if (!empty($this->FilteredCreators)) {
            $this->selectedCreators = $this->FilteredCreators->pluck('id')->toArray();
        }
        $this->creatorSection = true;
    }

    public function closeCreatorSection()
    {
        $this->creatorSection = false;
        $this->selectedCreators = [];
        $this->usersSearchText = null;
    }

    public function clearCreator(){
        $this->FilteredCreators = [];
    }

    public function setCtreators(){
        if (empty($this->selectedCreators)) {
            $this->FilteredCreators = [];
        } else {
            $this->FilteredCreators = User::whereIn('id', $this->selectedCreators)->get();
        }
        $this->closeCreatorSection();
        // $this->resetPage();
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

    public function clearrenwal()
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
        $this->InsurancePolicies = Policy::tableData()->searchBy($this->searchPolicy)
            ->take(5)
            ->get();
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


    public function exportReport()
    {
        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_map(function($creator) {
                return $creator['id'];
            }, $this->FilteredCreators->toArray());
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
            );
        }
    }

    public function exportHay2aReport()
    {
        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_map(function($creator) {
                return $creator['id'];
            }, $this->FilteredCreators->toArray());
        } else {
            $creators_ids = [];
        }

        if (Auth::user()->can('viewCommission', SoldPolicy::class)) {
            return SoldPolicy::exportHay2aReport(
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
            );
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

        $COMM_PROFILES = CommProfile::select('title', 'id')->get();

        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;

        if (!empty($this->FilteredCreators)) {
            $creators_ids = array_map(function($creator) {
                return $creator['id'];
            }, $this->FilteredCreators->toArray());
        } else {
            $creators_ids = [];
        }

        if ($this->creatorSection) {
            $users = User::search($this->usersSearchText)->take(5)->get();
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
        )->paginate(30);
        return view('livewire.sold-policy-report', [
            'policies' => $policies,
            'users' => $users,
            'COMM_PROFILES' => $COMM_PROFILES,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
        ]);
    }
}
