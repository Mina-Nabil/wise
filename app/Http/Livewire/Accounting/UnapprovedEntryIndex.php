<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\UnapprovedEntry;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UnapprovedEntryIndex extends Component
{
    use AlertFrontEnd, WithFileUploads;

    public $page_title = '• Unapproved Entries';

    protected $listeners = ['approveEntry','deleteEntry']; //functions need confirmation

    public $entryId;
    public $entryInfo;

    //to show child accounts
    public $showChildAccounts = [];

    public $accountDoc;
    public $uploadFileEntryId;
    public $uploadFileAccountId;

    public function updatedAccountDoc()
    {
        $this->validate([
            'accountDoc' => 'required|mimes:pdf,jpg,jpeg,png,xlsx,xls,doc,docx,rar,zip|max:10120', // 10MB Max
        ]);

        $file_url = $this->accountDoc->store(UnapprovedEntry::FILES_DIRECTORY, 's3');

        $res = UnapprovedEntry::findOrFail($this->uploadFileEntryId)->uploadDoc($this->uploadFileAccountId, $file_url);

        if ($res) {
            $this->alert('success', 'file uploaded!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function uploadAccountDoc($entryId, $accountId)
    {
        $this->uploadFileEntryId = $entryId;
        $this->uploadFileAccountId = $accountId;
    }

    public function downloadAccountDoc($entry_id, $account_id)
    {
        return UnapprovedEntry::findOrFail($entry_id)->downloadDoc($account_id);
    }

    public function downloadCreditDoc($id)
    {
        $entry = UnapprovedEntry::findOrFail($id);
        $fileContents = Storage::disk('s3')->get($entry->credit_doc_url);
        $extension = pathinfo($entry->credit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . '#' . $entry->id . '_credit_doc.' . $extension . '"',
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
        $entry = UnapprovedEntry::findOrFail($id);
        $fileContents = Storage::disk('s3')->get($entry->debit_doc_url);
        $extension = pathinfo($entry->debit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . '#' . $entry->id . '_debit_doc.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

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
