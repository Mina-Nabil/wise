<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Business\SoldPolicy;
use App\Models\Users\User;
use App\Models\Insurance\Policy;
use App\Models\Cars\Brand;
use App\Models\Insurance\Company;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SoldPolicyReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $startSection = false;
    public $expirySection = false;
    public $creatorSection = false;
    public $mainSalesSection = false;
    public $lobSection = false;
    public $valueSection = false;
    public $netPremSection = false;
    public $brandSection = false;
    public $companySection = false;
    public $PolicySection = false;
    public $issuedSection = false;
    public $creatorName;
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
    public $creator_id;
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

    public $Estart_from;
    public $Estart_to;
    public $Eissued_from;
    public $Eissued_to;
    public $Eexpiry_from;
    public $Eexpiry_to;
    public $Ecreator_id;
    public $Emain_sales_id;
    public $Eline_of_business;
    public $Evalue_from;
    public $Evalue_to;
    public $Enet_premium_to;
    public $Enet_premium_from;
    public $Ebrand_ids = [];
    public $Ecompany_ids = [];
    public $Epolicy_ids = [];
    public $Eis_valid;
    public $Eis_renewal;

    public function clearpaid()
    {
        $this->is_paid = null;
    }

    public function clearvalid()
    {
        $this->is_valid = null;
    }

    public function clearrenwal()
    {
        $this->is_renewal = null;
    }

    public function togglePaid()
    {
        $this->toggle($this->is_paid);
    }

    public function toggleRenewal()
    {
        $this->toggle($this->is_renewal);
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

    public function toggleCreator()
    {
        $this->toggle($this->creatorSection);
        if ($this->creatorSection) {
            $this->Ecreator_id = $this->creator_id;
        }
    }

    public function setCreator()
    {
        $this->creator_id = $this->Ecreator_id;
        $this->toggle($this->creatorSection);
    }

    public function clearCreator()
    {
        $this->creator_id = null;
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

    public function toggleIssuedDate()
    {
        $this->toggle($this->issuedSection);
        if ($this->issuedSection) {
            $this->Eissued_from = Carbon::parse($this->issued_from)->toDateString();
            $this->Eissued_to = Carbon::parse($this->issued_to)->toDateString();
        }
    }

    public function exportReport()
    {
        if (Auth::user()->is_admin) {
            return SoldPolicy::exportReport(
                $this->start_from,
                $this->start_to,
                $this->expiry_from,
                $this->expiry_to,
                $this->creator_id,
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
            );
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

    public function mount()
    {
        $this->brands = Brand::all()->take(5);
        $this->companies = Company::all()->take(5);
        $this->InsurancePolicies = Policy::all()->take(5);
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        if ($this->creator_id) {
            $c = User::find($this->creator_id);
            $this->creatorName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }
        if ($this->main_sales_id) {
            $c = User::find($this->main_sales_id);
            $this->mainSalesName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $users = User::all();
        $policies = SoldPolicy::report(
            $this->start_from,
            $this->start_to,
            $this->expiry_from,
            $this->expiry_to,
            $this->creator_id,
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
        )->paginate(30);
        return view('livewire.sold-policy-report', [
            'policies' => $policies,
            'users' => $users,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
        ]);
    }
}
