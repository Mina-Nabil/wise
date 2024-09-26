<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use Carbon\Carbon;
use Livewire\Component;

class AccountShow extends Component
{
    public $page_title = 'Account';

    public $account;
    // public $entries;

    public $entryId;

    public $fromDate = '2024-01-01';
    public $toDate = '2024-12-01';
    protected $listeners = ['dateRangeSelected'];

    public function dateRangeSelected($startDate, $endDate)
    {
        $this->fromDate = $startDate;
        $this->toDate = $endDate;

        $this->resetPage();
    }

    public function mount($id)
    {
        $this->entryId = $id;
        // dd($this->entryId );
    }

    public function render()
    {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);
        // dd($this->entryId);
        $entries = Account::getEntries($this->entryId, Carbon::parse($fromDate), Carbon::parse($toDate));
        // dd($entries );

        return view('livewire.accounting.account-show',[ 'entries' => $entries ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
