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
        $allSettings = AccountSetting::getAllSettingsWithCalcType();
        
        foreach (AccountSetting::getRequiredKeys() as $key => $label) {
            $accountsData = $allSettings[$key] ?? [];
            
            $accounts = [];
            foreach ($accountsData as $data) {
                $account = Account::find($data['account_id']);
                if ($account) {
                    $accounts[] = [
                        'id' => $account->id,
                        'code' => $account->full_code,
                        'name' => $account->name,
                        'calc_type' => $data['calc_type'],
                    ];
                }
            }
            
            $this->settings[] = [
                'key' => $key,
                'label' => $label,
                'accounts' => $accounts,
            ];
        }
    }

    public function openEditModal($key, $label)
    {
        $this->currentKey = $key;
        $this->currentKeyLabel = $label;
        $this->searchAccount = '';
        $this->accounts = [];
        
        // Load currently selected accounts with calc_type
        $accountsData = AccountSetting::getAccountsWithCalcType($key);
        $this->selectedAccountIds = array_column($accountsData, 'account_id');
        
        $this->selectedAccounts = [];
        foreach ($accountsData as $data) {
            $account = Account::find($data['account_id']);
            if ($account) {
                $this->selectedAccounts[] = [
                    'id' => $account->id,
                    'code' => $account->full_code,
                    'name' => $account->name,
                    'calc_type' => $data['calc_type'],
                ];
            }
        }
        
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
            // Check if account already has calc_type set
            $existingAccount = collect($this->selectedAccounts)->firstWhere('id', $account->id);
            return [
                'id' => $account->id,
                'code' => $account->full_code,
                'name' => $account->name,
                'calc_type' => $existingAccount['calc_type'] ?? AccountSetting::CALC_TYPE_ADD,
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
        
        // Add to selected accounts with default calc_type
        $account = Account::find($accountId);
        if ($account) {
            $this->selectedAccountIds[] = $accountId;
            $this->selectedAccounts[] = [
                'id' => $account->id,
                'code' => $account->full_code,
                'name' => $account->name,
                'calc_type' => AccountSetting::CALC_TYPE_ADD, // Default to 'add'
            ];
        }
        
        // Clear search
        $this->searchAccount = '';
        $this->accounts = [];
    }

    public function removeAccount($accountId)
    {
        $this->selectedAccountIds = array_values(
            array_filter($this->selectedAccountIds, fn($id) => $id != $accountId)
        );
        $this->selectedAccounts = array_values(
            array_filter($this->selectedAccounts, fn($acc) => $acc['id'] != $accountId)
        );
    }

    public function updateCalcType($accountId, $calcType)
    {
        // Update calc_type for the account in selectedAccounts
        foreach ($this->selectedAccounts as &$account) {
            if ($account['id'] == $accountId) {
                $account['calc_type'] = $calcType;
                break;
            }
        }
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

        if (empty($this->selectedAccounts)) {
            $this->alert('error', 'Please select at least one account');
            return;
        }

        // Prepare data with calc_type
        $accountsData = array_map(function($account) {
            return [
                'account_id' => $account['id'],
                'calc_type' => $account['calc_type'],
            ];
        }, $this->selectedAccounts);

        AccountSetting::setAccountIds($this->currentKey, $accountsData);
        
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
