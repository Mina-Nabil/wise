<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\ArchivedEntry;
use Livewire\Component;
use Livewire\WithPagination;

class ArchivedEntryIndex extends Component
{
    use WithPagination;
    
    public $page_title = '• Archived Entries';

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

    public function render()
    {
        $entries = ArchivedEntry::with(['entry_title', 'creator', 'archivedBy', 'accounts.main_account'])
            ->orderBy('archived_at', 'desc')
            ->paginate(50);
            
        return view('livewire.Accounting.archived-entry-index',[
            'entries' => $entries
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'archived_entries' => 'active']);
    }
}

