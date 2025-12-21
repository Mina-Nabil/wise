<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\Accounting\Account;
use App\Models\Accounting\AccountSetting;
use App\Traits\AlertFrontEnd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class IncomeStatementReport extends Component
{
    use AlertFrontEnd, AuthorizesRequests;

    public $page_title = 'Income Statement Report';
    public $startDate;
    public $endDate;
    public $isGenerating = false;

    public function mount()
    {
        // Default to last year and current date
        $this->startDate = Carbon::now()->subYear()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // Check if all settings are configured
        if (!AccountSetting::isFullyConfigured()) {
            $missing = AccountSetting::getMissingKeys();
            $this->alert('error', 'Please configure all account settings first. Missing: ' . implode(', ', $missing));
            return;
        }

        $this->isGenerating = true;

        try {
            $startDate = Carbon::parse($this->startDate);
            $endDate = Carbon::parse($this->endDate);

            $filePath = Account::generateIncomeStatementReport($startDate, $endDate);

            if ($filePath && file_exists($filePath)) {
                $filename = basename($filePath);
                $this->alert('success', 'Report generated successfully!');
                
                // Trigger download
                $this->emit('downloadReport', asset('storage/reports/' . $filename));
            } else {
                $this->alert('error', 'Failed to generate report. Please check permissions.');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'Error generating report: ' . $e->getMessage());
        } finally {
            $this->isGenerating = false;
        }
    }

    public function render()
    {
        $isConfigured = AccountSetting::isFullyConfigured();
        $missingKeys = AccountSetting::getMissingKeys();
        
        return view('livewire.Accounting.income-statement-report', [
            'isConfigured' => $isConfigured,
            'missingKeys' => $missingKeys,
        ])
        ->layout('layouts.accounting', [
            'page_title' => $this->page_title,
        ]);
    }
}
