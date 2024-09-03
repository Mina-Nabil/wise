<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\UnapprovedEntry;
use App\Traits\AlertFrontEnd;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class UpdateUnapprovedEntry extends Component
{

    use WithFileUploads, AlertFrontEnd, AuthorizesRequests;

    public $page_title = '• Unapproved Entries';

    public $entryId;
    public $title;
    public $amount;
    public $debit_id;
    public $debit_doc_url;
    public $credit_id;
    public $credit_doc_url;
    public $currency;
    public $currency_amount;
    public $currency_rate;
    public $notes;
    public $cash_entry_type;
    public $receiver_name;
    public $new_debit_doc_url;
    public $new_credit_doc_url;
    

    public $entry_titles;

    public function updatedTitle()
    {
        $title = $this->title;
        $this->entry_titles = EntryTitle::where('name', 'like', "%$title%")
            ->limit(5)
            ->get();
    }

    public function save()
    {
        if (!$this->currency) $this->currency = JournalEntry::CURRENCIES[0];
        if ($this->cash_entry_type) {
            $this->validate([
                'receiver_name' => 'required|string|max:255',
                'cash_entry_type' =>  'required|in:' . implode(',', JournalEntry::CASH_ENTRY_TYPES),
            ]);
        } else {
            $this->receiver_name = null;
        }

        $this->validate(
            [
                'title' => 'required|string|max:255', // Assuming title is a string and required
                'amount' => 'required|numeric|min:0', // Must be a positive number
                'debit_id' => 'required|exists:accounts,id', // Should be a valid ID from the accounts table
                'new_debit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
                'credit_id' => 'required|exists:accounts,id', // Should be a valid ID from the accounts table
                'new_credit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
                'currency' => 'required|in:' . implode(',', JournalEntry::CURRENCIES),
                'currency_amount' => 'nullable|numeric|min:0', // Must be a positive number
                'currency_rate' => 'nullable|numeric|min:0', // Must be a positive number
                'notes' => 'nullable|string', // Optional field; if provided, must be a string
            ],
            attributes: [
                'debit_id' => 'debit account',
                'credit_id' => 'credit account',
            ],
        );

        if ($this->new_credit_doc_url) {
            $credit_doc_url = $this->new_credit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        } else {
            $credit_doc_url = $this->credit_doc_url;
        }

        if ($this->new_debit_doc_url) {
            $debit_doc_url = $this->new_debit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        } else {
            $debit_doc_url = $this->debit_doc_url;
        }

        $res = UnapprovedEntry::findOrFail($this->entryId)->editRecord(
            $this->title,
            $this->amount,
            $this->credit_id,
            $this->debit_id,
            $credit_doc_url,
            $debit_doc_url,
            $this->currency,
            $this->currency_amount,
            $this->currency_rate,
            comment: $this->notes,
            receiver_name: $this->receiver_name,
            cash_type: $this->cash_entry_type
        );

        if ($res) {
            $this->alert('success', 'Entry updated!');
            sleep(2);
            $this->redirect(route('entries.unapproved', ['id' => $this->entryId]));
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function updatedCashEntryType()
    {
        if ($this->cash_entry_type === '') {
            $this->reset('receiver_name');
        }
    }

    public function downloadCreditDoc()
    {
        $entry = JournalEntry::find($this->entryId);
        $this->authorize('update', $entry);
        $fileContents = Storage::disk('s3')->get($entry->credit_doc_url);
        $extension = pathinfo($entry->credit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . '#' . $entry->day_serial . '_credit_doc.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function downloadDebitDoc()
    {
        $entry = JournalEntry::find($this->entryId);
        $this->authorize('update', $entry);
        $fileContents = Storage::disk('s3')->get($entry->debit_doc_url);
        $extension = pathinfo($entry->debit_doc_url, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . '#' . $entry->day_serial . '_debit_doc.' . $extension . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function mount($id)
    {
        $e = UnapprovedEntry::find($id);
        $this->entryId = $e->id;
        $this->title = $e->entry_title->name ;
        $this->amount = $e->amount;
        $this->debit_id = $e->debit_id ;
        $this->debit_doc_url = $e->debit_doc_url;
        $this->credit_id = $e->credit_id ;
        $this->credit_doc_url = $e->credit_doc_url;
        $this->currency = $e->currency;
        $this->currency_amount = $e->currency_amount;
        $this->currency_rate = $e->currency_rate;
        $this->notes = $e->comment;
        $this->cash_entry_type = $e->cash_entry_type;
        $this->receiver_name = $e->receiver_name;
    }

    public function selectTitle($v)
    {
        $this->title = $v;
        $this->entry_titles = null;
    }



    public function render()
    {
        $accounts = Account::all();
        $CURRENCIES = JournalEntry::CURRENCIES;
        $CASH_ENTRY_TYPES = JournalEntry::CASH_ENTRY_TYPES;
        return view('livewire.accounting.update-unapproved-entry',[
            'accounts' => $accounts,
            'CURRENCIES' => $CURRENCIES,
            'CASH_ENTRY_TYPES' => $CASH_ENTRY_TYPES,
        ])->layout('layouts.Accounting', ['page_title' => $this->page_title, 'unapproved_entries' => 'active']);
    }
}
