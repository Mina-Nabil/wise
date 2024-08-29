<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\UnapprovedEntry;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class UnapprovedEntryIndex extends Component
{
    use AlertFrontEnd;

    public $page_title = '• Unapproved Entries';

    protected $listeners = ['approveEntry']; //functions need confirmation

    public function approveEntry($id){
        $res = UnapprovedEntry::findOrFail($id)->approveRecord();
        if($res){
            $this->alert('success' , 'Entry approved');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function render()
    {
        $entries = UnapprovedEntry::paginate(50);
        return view('livewire.accounting.unapproved-entry-index',[
            'entries' => $entries
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'unapproved_entries' => 'active']);
    }
}
