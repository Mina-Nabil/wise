<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Users\User;
use App\Models\Reports\RenewalAnalysis;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RenewalAnalysisReport extends Component
{
    public ?int $selectedYear = null;
    public ?int $selectedMonth = null;
    public ?int $selectedUserId = null; // optional

    public bool $showResults = false;
    public array $stats = [];
    public array $yearlyStats = [];

    /** @var array<int,int> */
    public array $years = [];
    /** @var array<int,array{value:int,label:string}> */
    public array $months = [];
    /** @var array<int,array{id:int,name:string}> */
    public array $users = [];

    public function mount(): void
    {
        $this->years = $this->generateYears();
        $this->months = $this->generateMonths();
        $this->users = $this->loadUsers();
    }

    public function updatedSelectedYear(): void
    {
        $this->showResults = false;
    }

    public function updatedSelectedMonth(): void
    {
        if (!$this->selectedMonth) {
            $this->selectedMonth = null;
        }
        $this->showResults = false;
    }

    public function updatedSelectedUserId(): void
    {
        // optional filter; keep results shown
    }

    public function load(): void
    {
        $this->validate([
            'selectedYear' => ['required', 'integer'],
            'selectedMonth' => ['nullable', 'integer', 'between:1,12'],
            'selectedUserId' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($this->selectedMonth) {
            $this->stats = RenewalAnalysis::calculate($this->selectedYear, $this->selectedMonth, $this->selectedUserId);
            $this->yearlyStats = [];
        } else {
            $this->stats = [];
            $this->yearlyStats = RenewalAnalysis::calculateYearly($this->selectedYear, $this->selectedUserId);
        }
        $this->showResults = true;
    }

    public function render(): View
    {
        return view('livewire.renewal-analysis-report');
    }

    /**
     * @return array<int,int>
     */
    private function generateYears(): array
    {
        $currentYear = (int) now()->year;
        $startYear = $currentYear - 10;
        $years = [];
        for ($y = $currentYear; $y >= $startYear; $y--) {
            $years[] = $y;
        }
        return $years;
    }

    /**
     * @return array<int,array{value:int,label:string}>
     */
    private function generateMonths(): array
    {
        $list = [];
        for ($m = 1; $m <= 12; $m++) {
            $list[] = [
                'value' => $m,
                'label' => now()->setMonth($m)->startOfMonth()->format('F'),
            ];
        }
        return $list;
    }

    /**
     * @return array<int,array{id:int,name:string}>
     */
    private function loadUsers(): array
    {
        return User::query()
            ->orderBy('username')
            ->get(['id', 'username'])
            ->all();
    }
}


