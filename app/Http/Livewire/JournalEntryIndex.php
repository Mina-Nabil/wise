<?php

namespace App\Http\Livewire;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use Livewire\Component;
use Livewire\WithPagination;

class JournalEntryIndex extends Component
{
    use AlertFrontEnd , WithPagination;

    public $page_title = 'Journal Entry';

    public $searchText;
    public $isNewJournalEntryModalOpen = false;

    public function openAddNewModal(){
        $this->isNewJournalEntryModalOpen = true;
    }

    public function closeAddNewModal(){
        $this->isNewJournalEntryModalOpen = false;
    }

    public function render()
    {
        $entries = JournalEntry::paginate(50);
        $creditAccounts = Account::byNature(Account::NATURE_CREDIT)->get();
        $debitAccounts = Account::byNature(Account::NATURE_DEBIT)->get();


        return view('livewire.journal-entry-index',[
            'entries' => $entries,
            'creditAccounts' => $creditAccounts,
            'debitAccounts' => $debitAccounts

        ])
        ->layout('layouts.accounting', ['page_title' => $this->page_title, 'entries' => 'active']);
    }
}
