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
    public $selectedAccountId;
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
            $accountId = $allSettings[$key] ?? null;
            $account = $accountId ? Account::find($accountId) : null;
            
            $this->settings[] = [
                'key' => $key,
                'label' => $label,
                'account_id' => $accountId,
                'account_name' => $account ? $account->name : null,
                'account_code' => $account ? $account->saved_full_code : null,
            ];
        }
    }

    public function openEditModal($key, $label)
    {
        $this->currentKey = $key;
        $this->currentKeyLabel = $label;
        $this->selectedAccountId = AccountSetting::getAccountId($key);
        $this->searchAccount = '';
        $this->accounts = [];
        
        // Load initial accounts if there's already a selected account
        if ($this->selectedAccountId) {
            $account = Account::find($this->selectedAccountId);
            if ($account) {
                $this->accounts = [
                    [
                        'id' => $account->id,
                        'acc_code' => $account->acc_code,
                        'acc_name' => $account->acc_name,
                    ]
                ];
            }
        }
        
        $this->isEditModalOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->currentKey = null;
        $this->currentKeyLabel = null;
        $this->selectedAccountId = null;
        $this->searchAccount = '';
        $this->accounts = [];
    }

    public function updatedSearchAccount()
    {
        if (strlen($this->searchAccount) >= 2) {
            $this->accounts = Account::where('name', 'like', '%' . $this->searchAccount . '%')
                ->limit(20)
                ->get()
                ->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'acc_code' => $account->acc_code,
                        'acc_name' => $account->acc_name,
                    ];
                })
                ->toArray();
        } else {
            $this->accounts = [];
        }
    }

    public function selectAccount($accountId)
    {
        $this->selectedAccountId = $accountId;
        $account = Account::find($accountId);
        if ($account) {
            $this->searchAccount = $account->acc_code . ' - ' . $account->acc_name;
        }
        $this->accounts = [];
    }

    public function clearAccount()
    {
        $this->selectedAccountId = null;
        $this->searchAccount = '';
    }

    public function saveAccountSetting()
    {
        if (!$this->currentKey) {
            $this->alert('error', 'Invalid key');
            return;
        }

        AccountSetting::setAccountId($this->currentKey, $this->selectedAccountId);
        
        $this->alert('success', 'Account setting updated successfully');
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
