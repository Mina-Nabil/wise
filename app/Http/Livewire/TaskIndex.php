<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\Task;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class TaskIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $dateRange;
    public $startDate;
    public $endDate;
    public $filteredStatus;
    public $myTasks;

    public $taskTitle;
    public $assignedTo;
    public $desc;
    public $taskStatus;
    public $due;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function createTask()
    {
        // Call the newTask method from your model
        $due = $this->due ? Carbon::parse($this->due) : null;

        $t = Task::newTask(
            $this->taskTitle,
            null,
            $this->assignedTo,
            $due,
            $this->desc
        );

        if ($t) {
            $this->alert('success', 'Task Added!');
            $this->resetFormFields();
        } else {
            $this->alert('failed', 'Error Adding Task!');
        }
    }

    private function resetFormFields()
    {
        $this->taskTitle = null;
        $this->assignedTo = null;
        $this->desc = null;
        $this->taskStatus = null;
        $this->due = null;
    }
    public function filterByStatus($status)
    {
        $this->filteredStatus = [$status];
    }

    public function resetStatusFilter()
    {
        $this->filteredStatus = null;
    }

    public function mount($filters)
    {
        foreach ($filters as $filter) {
            switch ($filter) {
                case 'my':
                    $this->myTasks = Auth::id();
                    break;
            }
        }

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
        $users = User::all();

        $tasks = Task::fromTo($startDate, $endDate)
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
            'users' => $users,
        ]);
    }
}
