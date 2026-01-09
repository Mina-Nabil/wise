<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AccountShow extends Component
{
    use WithPagination, ToggleSectionLivewire, AlertFrontEnd;
    public $page_title = 'Account';
    public $searchText;

    public $account;
    public $accountId;
    public $fromDate = '2024-01-01';
    public $toDate = '2024-12-01';
    protected $listeners = ['dateRangeSelected'];

    public $is_open_edit = true;

    // Opening balance modal
    public $isOpeningBalanceModalOpen = false;
    public $openingBalance;
    public $openingForeignBalance;

    public function openOpeningBalanceModal()
    {
        $this->openingBalance = 0;
        $this->openingForeignBalance = 0;
        $this->isOpeningBalanceModalOpen = true;
    }

    public function closeOpeningBalanceModal()
    {
        $this->isOpeningBalanceModalOpen = false;
        $this->reset(['openingBalance', 'openingForeignBalance']);
    }

    public function dateRangeSelected($startDate, $endDate)
    {
        $this->fromDate = $startDate;
        $this->toDate = $endDate;

        $this->resetPage();
    }

    public function mount($id)
    {
        $this->accountId = $id;
        $this->account = Account::findOrFail($id);
    }

    public function downloadJournalEntries()
    {
        $res = Account::findOrFail($this->accountId)->downloadAccountDetails(Carbon::parse($this->fromDate), Carbon::parse($this->toDate), $this->searchText);
        if ($res) {
            $this->alert('success', 'Account details downloaded!');
            return $res;
        } else {
            $this->alert('failed', 'Failed to download account details');
        }
    }

    public function setOpeningBalance()
    {
        $this->validate([
            'openingBalance' => 'required|numeric',
            'openingForeignBalance' => 'nullable|numeric',
        ]);

        $account = Account::findOrFail($this->accountId);
        
        $result = $account->setOpeningBalance(
            (float) $this->openingBalance,
            $this->openingForeignBalance ? (float) $this->openingForeignBalance : null
        );

        if ($result['success']) {
            $this->alert('success', 'Opening balance set successfully! ' . $result['accounts_processed'] . ' accounts processed.');
            $this->account = $account->fresh();
            $this->closeOpeningBalanceModal();
        } else {
            $this->alert('failed', $result['message']);
        }
    }

    public function render()
    {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);
        $entries = collect(Account::getEntries($this->accountId, Carbon::parse($fromDate), Carbon::parse($toDate),$this->searchText));

        return view('livewire.Accounting.account-show', ['entries' => $entries])->layout('layouts.accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
