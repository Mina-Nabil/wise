<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Corporates\Corporate;
use App\Traits\AlertFrontEnd;
use Livewire\WithPagination;
use App\Traits\ToggleSectionLivewire;


class CorporateIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire;

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
    public $kyc;
    public $kycDoc;
    public $contractDoc;
    public $mainBandEvidence;

    public $LeadName;

    public function addLead(){
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $corporate = new Corporate();

        $res = $corporate->newLead(
            $this->name,
            null,null,null,null,null,null,null,null,null,null,null
        );

        if($res){
            $this->alert('success', 'Lead Added');
            $this->toggleAddLead();
        }else{
            $this->alert('failed', 'server error');
        }
        
    }


    public function addCorporate(){
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
            'mainBandEvidence' => 'nullable|string|max:255'
        ]);

        $corporate = new Corporate();

        $res = $corporate->newCorporate(
            auth()->id(),
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
            $this->mainBandEvidence
        );

        if($res){
            redirect()->route('corporates.show',$res->id);
        }else{
            $this->alert('failed', 'server error');
        }

    }

    public function redirectToShowPage($id)
    {
        return redirect(route('corporates.show',  $id));
    }



    public function toggleAddLead(){
        $this->toggle($this->addLeadSection);
    }

    public function toggleAddCorporate(){
        $this->toggle($this->addCorporateSection);
    }


    public function render()
    {
        $corporates = Corporate::paginate(10);
        return view('livewire.corporate-index',[
            'corporates' => $corporates,
        ]);
    }
}