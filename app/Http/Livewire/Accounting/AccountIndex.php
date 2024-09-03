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
    public $searchText;
    public $account_nature;
    public $mainAccID;

    //edit info
    public $accountID;

    // Method to open the modal
    public function openAddNewModal()
    {
        $this->reset(['acc_name', 'acc_desc', 'nature', 'main_account_id', 'limit']);
        $this->isAddNewModalOpen = true;
    }

    // Method to open edit modal
    public function openEditModal($id)
    {
        $a = Account::findOrFail($id);
        $this->acc_name = $a->name;
        $this->acc_desc = $a->desc;
        $this->nature = $a->nature;
        $this->main_account_id = $a->main_account_id ;
        $this->limit = $a->limit;
        $this->accountID = $id;
    }

    // Method to close edit modal
    public function closeEditModal()
    {
        $this->reset(['acc_name', 'acc_desc', 'nature', 'main_account_id', 'limit' ,'accountID']);
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

    public function saveEdit(){
        $this->validate([
            'acc_name' => 'required|string|max:100',
            'nature' => 'required|in:' . implode(',', Account::NATURES),
            'main_account_id' => 'required|exists:main_accounts,id',
            'limit' => 'required|numeric',
            'acc_desc' => 'nullable|string',
        ]);

        $res = Account::newAccount($this->acc_name, $this->nature, $this->main_account_id, $this->limit, $this->acc_desc);
        if ($res) {
            $this->closeEditModal();
            $this->alert('success', 'Account successfully updated');
        } else {
            $this->alert('failed', 'server error');
        }
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
        ->when($this->searchText,function ($q){
            return $q->searchBy($this->searchText);
        })
        ->get();
        $main_accounts = MainAccount::all();

        return view('livewire.Accounting.account-index', [
            'accounts' => $accounts,
            'main_accounts' => $main_accounts
        ])->layout('layouts.Accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
