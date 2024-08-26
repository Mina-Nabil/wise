<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class AccountIndex extends Component
{
    use AlertFrontEnd;

    public $page_title = 'Accounts';
    public $isAddNewModalOpen = false;
    public $Type_name;
    public $Type_desc;
    public $nature;
    public $type;
    public $limit;

    // filters properties
    public $account_nature = 'all';
    public $account_type = 'all';

    // Method to open the modal
    public function openAddNewModal()
    {
        $this->reset(['Type_name', 'Type_desc', 'nature', 'type', 'limit']);
        $this->isAddNewModalOpen = true;
    }

    // Method to close the modal
    public function closeAddNewModal()
    {
        $this->isAddNewModalOpen = false;
    }

    // Define variables to hold options
    public $types;
    public $natures;

    public function updateNature($text){
        $this->account_nature = $text;
    }

    public function updateType($text){
        $this->account_type = $text;
    }

    public function mount()
    {
        $this->types = Account::TYPES;
        $this->natures = Account::NATURES;
    }

    public function save()
    {
        $this->validate([
            'Type_name' => 'required|string|max:100',
            'nature' => 'required|in:' . implode(',', Account::NATURES),
            'type' => 'required|in:' . implode(',', Account::TYPES),
            'limit' => 'required|numeric',
            'Type_desc' => 'nullable|string',
        ]);

        $res = Account::newAccount($this->Type_name, $this->nature, $this->type, $this->limit, $this->Type_desc);

        if ($res) {
            $this->closeAddNewModal();
            $this->alert('success', 'Account successfully created');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        $accounts = Account::orderBy('id', 'desc')->get();

        return view('livewire.accounting.account-index', [
            'accounts' => $accounts,
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
