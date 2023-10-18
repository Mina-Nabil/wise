<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\AppLog;
use Livewire\WithPagination;
use Carbon\Carbon;

class AppLogIndex extends Component
{
    use WithPagination;

    public $fromDate = '2023-01-01';
    public $toDate = '2023-06-01';
    protected $listeners = ['dateRangeSelected'];

    public function dateRangeSelected($startDate, $endDate)
    {

        $this->fromDate = $startDate;
        $this->toDate = $endDate;

        $this->resetPage();
    }

    public function render()
    {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        $logs = AppLog::orderBy('created_at', 'desc')->fromTo($fromDate, $toDate)->paginate(20);

        return view('livewire.app-log-index', [
            'logs' => $logs,
        ]);
    }
}
