<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;
use App\Traits\AlertFrontEnd;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PolicyIndex extends Component
{
    use WithPagination, AlertFrontEnd, WithFileUploads;

    public $search;
    public $deleteThisPolicy;
    public $policyName;
    public $policyBusiness;
    public $note;
    public $company;
    public $newPolicySec = false;

    public function openPolicySec()
    {
        $this->newPolicySec = true;
    }

    public function closePolicySec()
    {
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
            redirect()->route('policies.show', $p->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    //Import/Export functions
    public $importFileSection = false;
    public $importConfFileSection = false;
    public $policiesFile;
    public $policiesConfFile;

    public function toggleImportSection()
    {
        $this->importFileSection = !$this->importFileSection;
    }

    public function toggleImportConfSection()
    {
        $this->importConfFileSection = !$this->importConfFileSection;
    }

    public function uploadPolicies()
    {
        $this->validate([
            'policiesFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);
        $importedFile_url = $this->policiesFile->store('tmp', 'local');
        Policy::importPolicies(storage_path('app/' . $importedFile_url));
        unlink(storage_path('app/' . $importedFile_url));
        $this->policiesFile = null;
        $this->toggleImportSection();
    }

    public function downloadPoliciesExport()
    {
        return  Policy::downloadPoliciesFile();
    }

    public function uploadPoliciesConfigurations()
    {
        $this->validate([
            'policiesConfFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
        ]);
        $importedFile_url = $this->policiesConfFile->store('tmp', 'local');
        Policy::importPoliciesConf(storage_path('app/' . $importedFile_url));
        unlink(storage_path('app/' . $importedFile_url));
        $this->policiesConfFile = null;
        $this->toggleImportConfSection();
    }

    public function downloadPoliciesConfExport()
    {
        return  Policy::downloadPoliciesConfFile();
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
