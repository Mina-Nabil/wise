<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\Accounting\AccountSetting;
use App\Models\Accounting\Account;
use App\Traits\AlertFrontEnd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountSettingsIndex extends Component
{
    use AlertFrontEnd, AuthorizesRequests;

    public $page_title = 'Account Settings';
    public $settings = [];
    public $isEditModalOpen = false;
    public $currentKey;
    public $currentKeyLabel;
    public $selectedAccountIds = [];
    public $selectedAccounts = [];
    public $searchAccount = '';
    public $accounts = [];

    protected $listeners = ['accountSelected'];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->settings = [];
        $allSettings = AccountSetting::getAllSettings();
        
        foreach (AccountSetting::getRequiredKeys() as $key => $label) {
            $accountIds = $allSettings[$key] ?? [];
            $accounts = Account::whereIn('id', $accountIds)->get();
            
            $this->settings[] = [
                'key' => $key,
                'label' => $label,
                'accounts' => $accounts->map(function($account) {
                    return [
                        'id' => $account->id,
                        'code' => $account->full_code,
                        'name' => $account->name,
                    ];
                })->toArray(),
            ];
        }
    }

    public function openEditModal($key, $label)
    {
        $this->currentKey = $key;
        $this->currentKeyLabel = $label;
        $this->selectedAccountIds = AccountSetting::getAccountIds($key);
        $this->searchAccount = '';
        $this->accounts = [];
        
        // Load currently selected accounts
        $this->loadSelectedAccounts();
        
        $this->isEditModalOpen = true;
    }

    public function loadSelectedAccounts()
    {
        if (empty($this->selectedAccountIds)) {
            $this->selectedAccounts = [];
            return;
        }
        
        $accounts = Account::whereIn('id', $this->selectedAccountIds)->get();
        $this->selectedAccounts = $accounts->map(function($account) {
            return [
                'id' => $account->id,
                'code' => $account->full_code,
                'name' => $account->name,
            ];
        })->toArray();
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->currentKey = null;
        $this->currentKeyLabel = null;
        $this->selectedAccountIds = [];
        $this->selectedAccounts = [];
        $this->searchAccount = '';
        $this->accounts = [];
    }

    public function updatedSearchAccount()
    {
        if (strlen($this->searchAccount) >= 2) {
            $this->accounts = Account::where('name', 'like', '%' . $this->searchAccount . '%')
                ->orWhere('saved_full_code', 'like', '%' . $this->searchAccount . '%')
                ->limit(20)
                ->get()
                ->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'acc_code' => $account->full_code,
                        'acc_name' => $account->name,
                    ];
                })
                ->toArray();
        } else {
            $this->accounts = [];
        }
    }

    public function selectAccount($accountId)
    {
        // Check if already selected
        if (in_array($accountId, $this->selectedAccountIds)) {
            $this->alert('warning', 'Account already selected');
            return;
        }
        
        // Add to selected accounts
        $this->selectedAccountIds[] = $accountId;
        $this->loadSelectedAccounts();
        
        // Clear search
        $this->searchAccount = '';
        $this->accounts = [];
    }

    public function removeAccount($accountId)
    {
        $this->selectedAccountIds = array_values(
            array_filter($this->selectedAccountIds, fn($id) => $id != $accountId)
        );
        $this->loadSelectedAccounts();
    }

    public function clearAllAccounts()
    {
        $this->selectedAccountIds = [];
        $this->selectedAccounts = [];
        $this->searchAccount = '';
        $this->accounts = [];
    }

    public function saveAccountSetting()
    {
        if (!$this->currentKey) {
            $this->alert('error', 'Invalid key');
            return;
        }

        if (empty($this->selectedAccountIds)) {
            $this->alert('error', 'Please select at least one account');
            return;
        }

        AccountSetting::setAccountIds($this->currentKey, $this->selectedAccountIds);
        
        $this->alert('success', 'Account settings updated successfully');
        $this->loadSettings();
        $this->closeEditModal();
    }

    public function render()
    {
        return view('livewire.Accounting.account-settings-index')
            ->layout('layouts.accounting', [
                'page_title' => $this->page_title,
            ]);
    }
}
