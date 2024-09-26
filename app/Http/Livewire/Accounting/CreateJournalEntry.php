<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use Http\Client\Common\Plugin\Journal;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class CreateJournalEntry extends Component
{
    use WithFileUploads, AlertFrontEnd, AuthorizesRequests;

    public $selectedTitle;
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
                'debit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
                'credit_id' => 'required|exists:accounts,id', // Should be a valid ID from the accounts table
                'credit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
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

        if ($this->credit_doc_url) {
            $credit_doc_url = $this->credit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        } else {
            $credit_doc_url = null;
        }

        if ($this->debit_doc_url) {
            $debit_doc_url = $this->debit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        } else {
            $debit_doc_url = null;
        }

        $res = JournalEntry::newJournalEntry($this->title, $this->amount, $this->credit_id, $this->debit_id, $this->currency, $this->currency_amount, $this->currency_rate, $credit_doc_url, $debit_doc_url, comment: $this->notes, receiver_name: $this->receiver_name);

        if ($res) {
            redirect(url('/entries'));
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

    public function mount()
    {
        $this->authorize('create', JournalEntry::class);
    }

    public function selectTitle($id)
    {
        $this->selectedTitle = EntryTitle::find($id);
        $this->entry_titles = null;
    }

    public function render()
    {
        $accounts = Account::whereIn('id', [2])->get();
        $CURRENCIES = JournalEntry::CURRENCIES;
        $CASH_ENTRY_TYPES = JournalEntry::CASH_ENTRY_TYPES;

        return view('livewire.Accounting.create-journal-entry', [
            'accounts' => $accounts,
            'CURRENCIES' => $CURRENCIES,
            'CASH_ENTRY_TYPES' => $CASH_ENTRY_TYPES,
        ])->layout('layouts.accounting', ['page_title' => 'Journal Entry • New', 'entries' => 'active']);
    }
}
