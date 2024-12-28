<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Corporates\Corporate;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;
use Livewire\WithFileUploads;

class CorporateIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $search;

    public $addCorporateSection = false;
    public $addLeadSection = false;

    public $name;
    public $arabicName;
    public $email;
    public $commercialRecord;
    public $commercialRecordDoc;
    public $taxId;
    public $taxIdDoc;
    public $ownerId;
    public $kyc;
    public $kycDoc;
    public $contractDoc;
    public $mainBandEvidence;
    public $followupCallDateTime;
    public $LeadNote;
    public $note;

    public $LeadName;

    public $leadsImportFile;
    public $downloadUserLeadsID;

    public function addLead(){

        if($this->followupCallDateTime === ''){$this->followupCallDateTime = null ;}

        $this->validate([
            'name' => 'required|string|max:255',
            'ownerId' => 'nullable|integer|exists:users,id',
            'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
            'LeadNote' => 'nullable|string|max:255',
        ]);

        $corporate = new Corporate();

        $res = $corporate->newLead(
            $this->name,
            owner_id: $this->ownerId,
            note: $this->LeadNote
        );

        if ($this->followupCallDateTime) {
            $fres = $res->addFollowup('Initial Contact',new \DateTime($this->followupCallDateTime),$this->LeadNote);
        }else{
            $fres = true;
        }

        if($res && $fres){
            $this->alert('success', 'Lead Added');
            $this->name = null;
            $this->ownerId = null;
            $this->toggleAddLead();
        }else{
            $this->alert('failed', 'server error');
        }
        
    }


    public function addCorporate(){

        if($this->followupCallDateTime === ''){$this->followupCallDateTime = null ;}

        $this->validate([
            'name' => 'required|string|max:255',
            'arabicName' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'commercialRecord' => 'nullable|string|max:255',
            'commercialRecordDoc' => 'nullable|string|max:255',
            'taxId' => 'nullable|string|max:255',
            'taxIdDoc' => 'nullable|string|max:255',
            'kyc' => 'nullable|string|max:255',
            'kycDoc' => 'nullable|string|max:255',
            'contractDoc' => 'nullable|string|max:255',
            'mainBandEvidence' => 'nullable|string|max:255',
            'ownerId' => 'nullable|integer|exists:users,id',
            'followupCallDateTime' => 'nullable|date_format:Y-m-d\TH:i',
            'note' => 'nullable|string|max:255',
        ]);

        $corporate = new Corporate();

        $res = $corporate->newCorporate(
            $this->ownerId,
            $this->name,
            $this->arabicName,
            $this->email,
            $this->commercialRecord,
            $this->commercialRecordDoc,
            $this->taxId,
            $this->taxIdDoc,
            $this->kyc,
            $this->kycDoc,
            $this->contractDoc,
            $this->mainBandEvidence,
            $this->note
        );

        if ($this->followupCallDateTime) {
            $fres = $res->addFollowup('Initial Contact',new \DateTime($this->followupCallDateTime),$this->note);
        }else{
            $fres = true;
        }

        if($res && $fres){
            redirect()->route('corporates.show',$res->id);
        }else{
            $this->alert('failed', 'server error');
        }

    }

    public function redirectToShowPage($id)
    {
        return redirect(route('corporates.show',  $id));
    }

     ///import/export functions
     public function importLeads()
     {
         $this->validate([
             'leadsImportFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
         ]);
         $importedFile_url = $this->leadsImportFile->store('tmp', 'local');
         Corporate::importLeads(storage_path('app/' . $importedFile_url));
         unlink(storage_path('app/' . $importedFile_url));
         $this->leadsImportFile = null;
         $this->toggleAddLead();
         $this->resetPage();
     }
 
     public function downloadLeadsFile()
     {
         $this->validate([
             'downloadUserLeadsID' => 'nullable|integer|exists:users,id',
         ]);
 
         return Corporate::exportLeads($this->downloadUserLeadsID);
     }
 
     public function downloadTemplate()
     {
         return Corporate::downloadTemplate();
     }
 



    public function toggleAddLead(){
        $this->toggle($this->addLeadSection);
    }

    public function toggleAddCorporate(){
        $this->toggle($this->addCorporateSection);
    }


    public function render()
    {
        $corporates = Corporate::userData($this->search)->paginate(10);
        $users = User::all();

        return view('livewire.corporate-index',[
            'corporates' => $corporates,
            'users' => $users,
        ]);
    }
}
