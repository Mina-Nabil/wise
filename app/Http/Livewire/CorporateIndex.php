<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Status;
use App\Models\Customers\Interest;
use App\Models\Customers\Followup;
use App\Models\Marketing\Campaign;
use App\Models\Users\User;
use App\Models\Insurance\Policy;
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

    //status change
    public $statusFilter;
    public $changeStatusId;
    public $status;
    public $statusReason;
    public $statusNote;

    public $LeadName;
    public $leadCampaignId;
    public $campaignId;

    public $leadsImportFile;
    public $downloadUserLeadsID;

    // Interest management properties
    public $interestManagementModalOpen = false;
    public $selectedCorporate = null;
    public $editInteresetSec = false;
    public $editedLob;
    public $interested;
    public $interestNote;
    public $isCreateFollowup = false;

    // Followup properties
    public $addFollowupSection = false;
    public $followupTitle;
    public $followupCallDate;
    public $followupCallTime;
    public $followupDesc;
    public $FollowupLineOfBussiness = Policy::BUSINESS_CORPORATE_MOTOR;
    public $is_meeting = false;

    public function changeStatus()
    {
        $this->validate([
            'status' => 'required|string|max:255',
            'statusReason' => 'nullable|string|max:255',
            'statusNote' => 'nullable|string|max:255',
        ]);

        $corporate = Corporate::findOrFail($this->changeStatusId);
        $res = $corporate->setStatus($this->status, $this->statusReason, $this->statusNote);

        if ($res) {
            $this->alert('success', 'Status Updated');
            $this->closeChangeStatus();
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function openChangeStatus($id)
    {
        $this->changeStatusId = $id;
        $statuss = Corporate::findOrFail($id)?->status;
        if ($statuss) {
            $this->status = $statuss->status;
            $this->statusReason = $statuss->reason;
            $this->statusNote = $statuss->note;
        }
    }

    public function closeChangeStatus()
    {
        $this->changeStatusId = null;
        $this->status = null;
        $this->statusReason = null;
        $this->statusNote = null;
    }

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

    // Interest Management Functions
    public function openInterestManagement($corporateId)
    {
        $this->selectedCorporate = Corporate::with('interests')->findOrFail($corporateId);
        $this->interestManagementModalOpen = true;
    }

    public function closeInterestManagement()
    {
        $this->interestManagementModalOpen = false;
        $this->selectedCorporate = null;
        $this->editInteresetSec = false;
        $this->editedLob = null;
        $this->interested = null;
        $this->interestNote = null;
        $this->isCreateFollowup = false;
        // Also close followup section
        $this->closeFollowupSection();
    }

    public function removeInterest($id)
    {
        $res = Interest::find($id)->delete();
        if ($res) {
            // Refresh the selected corporate with updated interests
            $this->selectedCorporate = Corporate::with('interests')->findOrFail($this->selectedCorporate->id);
            $this->alert('success', 'Interest removed!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function editThisInterest($status, $lob)
    {
        $this->editInteresetSec = true;
        $this->editedLob = $lob;
        $this->interested = $status;
    }

    public function closeEditInterest()
    {
        $this->editInteresetSec = false;
        $this->editedLob = null;
        $this->interested = null;
        $this->interestNote = null;
        $this->isCreateFollowup = false;
    }

    public function editInterest()
    {
        if ($this->interested === 'YES') {
            $this->interested = true;
        } else {
            $this->interested = false;
        }

        $this->validate([
            'editedLob' => 'required|in:' . implode(',', Policy::CORPORATE_TYPES),
            'interested' => 'required|boolean',
            'interestNote' => 'nullable|string|max:255'
        ]);

        $res = $this->selectedCorporate->addInterest($this->editedLob, $this->interested, $this->interestNote);

        if ($res) {
            $this->editInteresetSec = false;
            $this->editedLob = null;
            $this->interested = null;
            $this->interestNote = null;
            // Refresh the selected corporate with updated interests
            $this->selectedCorporate = Corporate::with('interests')->findOrFail($this->selectedCorporate->id);
            $this->alert('success', 'Interest edited!');
            
            if ($this->isCreateFollowup) {
                $this->FollowupLineOfBussiness = $res->business;
                $this->is_meeting = true;
                $this->OpenAddFollowupSection();
            }
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    // Followup Management Functions
    public function OpenAddFollowupSection()
    {
        $this->addFollowupSection = true;
    }

    public function closeFollowupSection()
    {
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
        $this->addFollowupSection = false;
        $this->is_meeting = false;
        $this->FollowupLineOfBussiness = Policy::BUSINESS_CORPORATE_MOTOR;
        $this->isCreateFollowup = false;
    }

    public function addFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255',
            'FollowupLineOfBussiness' => 'nullable|in:' . implode(',', Policy::CORPORATE_TYPES),
            'is_meeting' => 'nullable|boolean',
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $res = $this->selectedCorporate->addFollowup(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc,
            $this->is_meeting,
            $this->FollowupLineOfBussiness,
        );

        if ($res) {
            $this->alert('success', 'Followup added successfully');
            $this->closeFollowupSection();
            // Refresh the selected corporate
            $this->selectedCorporate = Corporate::with('interests')->findOrFail($this->selectedCorporate->id);
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function render()
    {
        $corporates = Corporate::userData($this->search, $this->statusFilter)->paginate(10);
        $users = User::all();
        $customerStatus = Status::STATUSES;
        $LINES_OF_BUSINESS = Policy::CORPORATE_TYPES;
        
        $campaigns = Campaign::all();
        
        return view('livewire.corporate-index',[
            'corporates' => $corporates,
            'customerStatus' => $customerStatus,
            'users' => $users,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'campaigns' => $campaigns,
        ]);
    }
}
