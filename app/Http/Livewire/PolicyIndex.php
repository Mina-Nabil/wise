<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;

class PolicyIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $search;
    public $deleteThisPolicy;
    public $policyName;
    public $policyBusiness;
    public $note;
    public $company;
    public $newPolicySec = false;

    public function openPolicySec(){
        $this->newPolicySec = true;
    }

    public function closePolicySec(){
        $this->newPolicySec = false;
    }

    public function openDeletePolicy($id)
    {
        $this->deleteThisPolicy = $id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function add()
    {
        $this->validate([
            'company' => 'required|exists:insurance_companies,id',
            'policyName' => 'required|string|unique:policies,name',
            'policyBusiness' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            'note' => 'nullable|string',
        ]);
        $p = Policy::newPolicy($this->company, $this->policyName, $this->policyBusiness, $this->note);

        if ($p) {
            $this->company = null;
            $this->policyName = null;
            $this->policyBusiness = null;
            $this->note = null;
            redirect()->route('policies.show',$p->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function render()
    {
        $companies = Company::all();
        $linesOfBusiness = Policy::LINES_OF_BUSINESS;
        $policies = Policy::tableData()
            ->SearchBy($this->search)
            ->paginate(12);

        return view('livewire.policy-index', [
            'policies' => $policies,
            'linesOfBusiness' => $linesOfBusiness,
            'companies' => $companies,
        ]);
    }
}
