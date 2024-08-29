<?php

namespace App\Http\Livewire;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use Http\Client\Common\Plugin\Journal;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class JournalEntryIndex extends Component
{
    use AlertFrontEnd , WithPagination;

    public $page_title = '• Journal Entry';

    public $searchText;
    public $isNewJournalEntryModalOpen = false;
    public $isOpenFilterAccountModal = false;
    public $searchAccountText;
    public $fetched_accounts;
    public $selectedAccount;

    public function updatedSearchAccountText(){
        $this->fetched_accounts = Account::searchBy($this->searchAccountText)->limit(10)->get();
    }

    public function reviewEntry($id){
        $res = JournalEntry::findOrFail($id)->reviewEntry();
        if ($res) {
            $this->alert('success' , 'Entry Successfuly Reviewed!');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function clearAccountFilter(){
        $this->selectedAccount = null;
    }

    public function downloadCreditDoc($id)
    {
        $entry = JournalEntry::find($id);
        $fileContents = Storage::disk('s3')->get($entry->credit_doc_url);
        $extension = pathinfo($entry->credit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' .'#'. $this->entry->day_serial . '_credit_doc.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function downloadDebitDoc($id)
    {
        $entry = JournalEntry::find($id);
        $fileContents = Storage::disk('s3')->get($entry->debit_doc_url);
        $extension = pathinfo($entry->debit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' .'#'. $this->entry->day_serial . '_debit_doc.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function openSelectAccountModel(){
        $this->isOpenFilterAccountModal = true;
    }

    public function closeSelectAccountModel(){
        $this->isOpenFilterAccountModal = false;
    }

    public function openAddNewModal(){
        $this->isNewJournalEntryModalOpen = true;
    }

    public function closeAddNewModal(){
        $this->isNewJournalEntryModalOpen = false;
    }

    public function selectAccount($id){
        
        $this->selectedAccount = Account::findOrFail($id);
        $this->closeSelectAccountModel();
    }

    public function render()
    {
        $entries = JournalEntry::
        when($this->selectedAccount,function($q){
            return $q->byAccount($this->selectedAccount->id);
        })
        ->paginate(50);
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
