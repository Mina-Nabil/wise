<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\MainAccount;
use App\Traits\AlertFrontEnd;
use Livewire\Component;

class AccountIndex extends Component
{
    use AlertFrontEnd;

    public $page_title = 'Accounts';
    public $isAddNewModalOpen = false;
    public $acc_name;
    public $acc_desc;
    public $nature;
    public $main_account_id;
    public $limit;

    // filters properties
    public $account_nature;
    public $mainAccID;

    // Method to open the modal
    public function openAddNewModal()
    {
        $this->reset(['acc_name', 'acc_desc', 'nature', 'main_account_id', 'limit']);
        $this->isAddNewModalOpen = true;
    }

    // Method to close the modal
    public function closeAddNewModal()
    {
        $this->isAddNewModalOpen = false;
    }

    // Define variables to hold options
    public $natures;

    public function updateNature($text){
        $this->account_nature = $text;
    }

    public function updateMainAccountID($text){
        $this->mainAccID = $text;
    }

    public function mount()
    {
        $this->natures = Account::NATURES;
    }

    public function save()
    {
        $this->validate([
            'acc_name' => 'required|string|max:100',
            'nature' => 'required|in:' . implode(',', Account::NATURES),
            'main_account_id' => 'required|exists:main_accounts,id',
            'limit' => 'required|numeric',
            'acc_desc' => 'nullable|string',
        ]);

        $res = Account::newAccount($this->acc_name, $this->nature, $this->main_account_id, $this->limit, $this->acc_desc);

        if ($res) {
            $this->closeAddNewModal();
            $this->alert('success', 'Account successfully created');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        $accounts = Account::orderBy('id', 'desc')
        ->when($this->account_nature,function ($q){
            return $q->byNature($this->account_nature);
        })
        ->when($this->mainAccID,function ($q){
            return $q->byMainAccount($this->mainAccID);
        })
        ->get();
        $main_accounts = MainAccount::all();

        return view('livewire.accounting.account-index', [
            'accounts' => $accounts,
            'main_accounts' => $main_accounts
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
