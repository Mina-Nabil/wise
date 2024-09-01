<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use Http\Client\Common\Plugin\Journal;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateJournalEntry extends Component
{
    use WithFileUploads ,AlertFrontEnd;

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
        $this->validate([
            'title' => 'required|string|max:255', // Assuming title is a string and required
            'receiver_name' => 'required|string|max:255', 
            'amount' => 'required|numeric|min:0', // Must be a positive number
            'debit_id' => 'required|exists:accounts,id', // Should be a valid ID from the accounts table
            'debit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'credit_id' => 'required|exists:accounts,id', // Should be a valid ID from the accounts table
            'credit_doc_url' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
            'currency' => 'required|in:' . implode(',', JournalEntry::CURRENCIES),
            'currency_amount' => 'nullable|numeric|min:0', // Must be a positive number
            'currency_rate' => 'nullable|numeric|min:0', // Must be a positive number
            'notes' => 'nullable|string', // Optional field; if provided, must be a string
        ],attributes:[
            'debit_id' => 'debit account',
            'credit_id' =>'credit account',
        ]);

        if($this->credit_doc_url){
            $credit_doc_url = $this->credit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        }else{
            $credit_doc_url = null;
        }

        if($this->debit_doc_url){
            $debit_doc_url = $this->debit_doc_url->store(JournalEntry::FILES_DIRECTORY, 's3');
        }else{
            $debit_doc_url = null;
        }
        
        // dd(
        //     $this->title,
        //     $this->amount,
        //     $this->credit_id,
        //     $this->credit_id,
        //     $this->currency,
        //     $this->currency_amount,
        //     $this->currency_rate,
        //     $credit_doc_url,
        //     $debit_doc_url,
        //     $this->notes,
        //     $this->receiver_name,

        // );


        $res = JournalEntry::newJournalEntry(
            $this->title,
            $this->amount,
            $this->credit_id,
            $this->credit_id,
            $this->currency,
            $this->currency_amount,
            $this->currency_rate,
            $credit_doc_url,
            $debit_doc_url,
            comment: $this->notes,
            receiver_name: $this->receiver_name,
            approver_id: auth()->id(),
        );

        if($res){
            redirect(url('/entries'));
        }else{
            $this->alert('failed','server error');
        }
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

        return view('livewire.accounting.create-journal-entry', [
            'accounts' => $accounts,
            'CURRENCIES' => $CURRENCIES,
        ])->layout('layouts.accounting', ['page_title' => 'Journal Entry • New', 'entries' => 'active']);
    }
}
