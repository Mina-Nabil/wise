<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\Task;
use Carbon\Carbon;
use Livewire\WithPagination;

class TaskIndex extends Component
{
    use WithPagination;

    public $dateRange;
    public $startDate;
    public $endDate;
    public $filteredStatus;
    public $myTasks;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function filterByStatus($status)
    {
        
        $this->filteredStatus  = [$status];
    }

    public function resetStatusFilter(){
        $this->filteredStatus = null;
    }

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()
            ->addMonths(3)
            ->format('Y-m-d');
        $this->dateRange = $this->startDate . ' to ' . $this->endDate;
    }

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            // The string contains 'to'
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
            // dd($this->startDate, $this->endDate);
        }
    }

    public function render()
    {
        $statuses = Task::STATUSES;
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);

        $tasks = Task::whereBetween('due', [$startDate, $endDate])
            ->when($this->filteredStatus, function ($query) {
                return $query->byStates($this->filteredStatus);
            })
            ->when($this->myTasks, function ($query) {
                return $query->assignedTo(auth()->user()->id);
            })
            ->paginate(10);

        return view('livewire.task-index', [
            'tasks' => $tasks,
            'statuses' => $statuses,
            'filteredStatus' => $this->filteredStatus,
        ]);
    }
}
