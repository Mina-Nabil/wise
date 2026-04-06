<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\UnapprovedEntry;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class UnapprovedEntryIndex extends Component
{
    use AlertFrontEnd;

    public $page_title = '• Unapproved Entries';

    protected $listeners = ['approveEntry','deleteEntry']; //functions need confirmation

    public $entryId;
    public $entryInfo;

    //to show child accounts
    public $showChildAccounts = [];

    public function showThisChildAccount($entryId)
    {
        // Check if the ID is already in the array, to avoid duplicates
        if (!in_array($entryId, $this->showChildAccounts)) {
            // Add the account ID to the array
            $this->showChildAccounts[] = $entryId;
        }
    }

    public function hideThisChildAccount($entryId)
    {
        $this->showChildAccounts = array_filter($this->showChildAccounts, function($id) use ($entryId) {
            return $id !== $entryId;
        });
    }

    public function showEntry($id)
    {
        $this->entryId = $id;
        $this->entryInfo = UnapprovedEntry::with('creator')->findOrFail($id);
    }

    public function closeShowInfo()
    {
        $this->entryId = null;
        $this->entryInfo = null;
    }

    public function approveEntry($id){
        $res = UnapprovedEntry::findOrFail($id)->approveRecord();
        if($res){
            $this->alert('success' , 'Entry approved');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function deleteEntry($id){
        $res = UnapprovedEntry::findOrFail($id)->deleteRecord();
        if($res){
            $this->alert('success' , 'Entry deleted');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function render()
    {
        $entries = UnapprovedEntry::with(['creator', 'entry_title', 'accounts.main_account'])->paginate(50);
        return view('livewire.Accounting.unapproved-entry-index',[
            'entries' => $entries
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'unapproved_entries' => 'active']);
    }
}
