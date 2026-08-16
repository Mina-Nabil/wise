<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AccountShow extends Component
{
    use WithPagination, ToggleSectionLivewire, AlertFrontEnd, AuthorizesRequests;
    public $page_title = 'Account';
    public $searchText;

    public $account;
    public $accountId;
    public $fromDate = '2024-01-01';
    public $toDate = '2024-12-01';
    protected $listeners = ['dateRangeSelected'];

    public $is_open_edit = true;
    public $includeChildren = false;
    public $sameSheet = false;

    // Opening balance modal
    public $isOpeningBalanceModalOpen = false;
    public $openingBalance;
    public $openingForeignBalance;

    // Clear parent & children balances confirmation modal
    public $isClearBalancesModalOpen = false;

    // Delete account (with its entries) confirmation modal
    public $isDeleteAccountModalOpen = false;
    public $deleteConfirmation;
    public $deleteImpact = [];

    // Merge account into another one
    public $isMergeModalOpen = false;
    public $mergeSearchText;
    public $mergeSearchResults = [];
    public $mergeTargetId;
    public $mergeTarget;

    // Move account (with children) under a new parent
    public $isMoveModalOpen = false;
    public $moveSearchText;
    public $moveSearchResults = [];
    public $moveParentId;
    public $moveParent;
    public $moveToRoot = false;

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
        $res = Account::findOrFail($this->accountId)->downloadAccountDetails(
            Carbon::parse($this->fromDate),
            Carbon::parse($this->toDate),
            $this->searchText,
            $this->includeChildren,
            $this->sameSheet
        );
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

    public function openClearBalancesModal()
    {
        $this->isClearBalancesModalOpen = true;
    }

    public function closeClearBalancesModal()
    {
        $this->isClearBalancesModalOpen = false;
    }

    public function clearBalancesWithChildren()
    {
        $account = Account::findOrFail($this->accountId);

        /** @var \App\Models\Users\User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('setOpeningBalance', $account)) {
            $this->alert('failed', 'You are not allowed to clear balances');
            return;
        }

        $result = $account->clearBalanceWithChildren();

        if ($result['success']) {
            $this->alert('success', $result['message']);
            $this->account = $account->fresh();
            $this->closeClearBalancesModal();
        } else {
            $this->alert('failed', $result['message']);
        }
    }

    public function openDeleteAccountModal()
    {
        $this->deleteImpact = Account::findOrFail($this->accountId)->deletionImpact();
        $this->isDeleteAccountModalOpen = true;
        $this->deleteConfirmation = null;
    }

    public function closeDeleteAccountModal()
    {
        $this->reset(['isDeleteAccountModalOpen', 'deleteConfirmation', 'deleteImpact']);
    }

    public function deleteAccountWithEntries()
    {
        $account = Account::findOrFail($this->accountId);
        $this->authorize('deleteWithEntries', $account);

        // Typing the account name is the last guard before the entries go
        if (trim((string) $this->deleteConfirmation) !== trim($account->name)) {
            $this->alert('failed', 'اسم الحساب غير مطابق');
            return;
        }

        $result = $account->deleteWithEntries();

        if ($result['success']) {
            $this->alert('success', $result['message']);
            return redirect(url('/accounts'));
        }

        $this->alert('failed', $result['message']);
    }

    public function openMergeModal()
    {
        $this->isMergeModalOpen = true;
    }

    public function closeMergeModal()
    {
        $this->reset(['isMergeModalOpen', 'mergeSearchText', 'mergeSearchResults', 'mergeTargetId', 'mergeTarget']);
    }

    public function updatedMergeSearchText()
    {
        $this->mergeSearchResults = $this->mergeSearchText
            ? Account::searchBy($this->mergeSearchText)
                ->where('id', '!=', $this->accountId)
                ->limit(10)
                ->get()
            : [];
    }

    public function selectMergeTarget($id)
    {
        $this->mergeTarget = Account::findOrFail($id);
        $this->mergeTargetId = $id;
        $this->mergeSearchText = null;
        $this->mergeSearchResults = [];
    }

    public function clearMergeTarget()
    {
        $this->reset(['mergeTargetId', 'mergeTarget']);
    }

    public function mergeAccount()
    {
        $this->validate(['mergeTargetId' => 'required|exists:accounts,id']);

        $account = Account::findOrFail($this->accountId);
        $this->authorize('merge', $account);

        $result = $account->mergeInto(Account::findOrFail($this->mergeTargetId));

        if ($result['success']) {
            $this->alert('success', $result['message']);
            return redirect(url('/accounts/' . $this->mergeTargetId));
        }

        $this->alert('failed', $result['message']);
    }

    public function openMoveModal()
    {
        $this->isMoveModalOpen = true;
    }

    public function closeMoveModal()
    {
        $this->reset([
            'isMoveModalOpen',
            'moveSearchText',
            'moveSearchResults',
            'moveParentId',
            'moveParent',
            'moveToRoot',
        ]);
    }

    public function updatedMoveSearchText()
    {
        if (!$this->moveSearchText) {
            $this->moveSearchResults = [];
            return;
        }

        $account = Account::findOrFail($this->accountId);
        $excludedIds = $account->getSelfAndDescendants()->pluck('id')->all();

        $this->moveSearchResults = Account::searchBy($this->moveSearchText)
            ->where('main_account_id', $account->main_account_id)
            ->whereNotIn('id', $excludedIds)
            ->whereNotExists(
                fn($q) => $q->select(DB::raw(1))
                    ->from('entry_accounts')
                    ->whereColumn('entry_accounts.account_id', 'accounts.id')
            )
            ->limit(10)
            ->get();
    }

    public function selectMoveParent($id)
    {
        $this->moveParent = Account::findOrFail($id);
        $this->moveParentId = $id;
        $this->moveToRoot = false;
        $this->moveSearchText = null;
        $this->moveSearchResults = [];
    }

    public function clearMoveParent()
    {
        $this->reset(['moveParentId', 'moveParent', 'moveToRoot']);
    }

    public function chooseMoveToRoot()
    {
        $this->moveToRoot = true;
        $this->reset(['moveParentId', 'moveParent', 'moveSearchText', 'moveSearchResults']);
    }

    public function moveAccount()
    {
        if (!$this->moveToRoot) {
            $this->validate(['moveParentId' => 'required|exists:accounts,id']);
        }

        $account = Account::findOrFail($this->accountId);
        $this->authorize('move', $account);

        $newParent = $this->moveToRoot ? null : Account::findOrFail($this->moveParentId);
        $result = $account->moveTo($newParent);

        if ($result['success']) {
            $this->alert('success', $result['message']);
            $this->account = $account->fresh();
            $this->closeMoveModal();
        } else {
            $this->alert('failed', $result['message']);
        }
    }

    public function render()
    {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);
        $entries = collect(Account::getEntries($this->accountId, $fromDate, $toDate, $this->searchText));

        $periodStartBalance = $this->account->getStartBalanceForPeriod($fromDate, $toDate);

        $periodEndBalance = $entries->isNotEmpty()
            ? (float) $entries->last()->account_balance
            : $periodStartBalance;

        $openingBalance = $this->account->getOpeningBalance()['balance'];
        $currentBalance = (float) $this->account->balance;

        if ($entries->isNotEmpty()) {
            $firstInRange = $entries->first();
            $beforeFirstInRange = Account::balanceBeforeEntrySnapshot($firstInRange, $this->account);
            $isFirstLiveEntry = $firstInRange->id === $this->account->getFirstLiveEntryId();

            if ($isFirstLiveEntry) {
                $openingBalance = $beforeFirstInRange;
            }
        }

        return view('livewire.Accounting.account-show', [
            'entries' => $entries,
            'periodStartBalance' => $periodStartBalance,
            'periodEndBalance' => $periodEndBalance,
            'openingBalance' => $openingBalance,
            'currentBalance' => $currentBalance,
        ])->layout('layouts.accounting', ['page_title' => $this->page_title, 'accounts' => 'active']);
    }
}
