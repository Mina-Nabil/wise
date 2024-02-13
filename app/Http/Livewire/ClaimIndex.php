<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Users\User;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Traits\ToggleSectionLivewire;


class ClaimIndex extends Component
{
    use WithPagination, AlertFrontEnd, WithFileUploads, ToggleSectionLivewire;

    public $dateRange;
    public $startDate;
    public $endDate;
    public $filteredStatus;
    public $searchText;
    public $myTasks;
    public $watcherTasks;


    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function redirectToShowPage($id)
    {
        return redirect(route('tasks.show', ['id' => $id]));
    }


    public function filterByStatus($status)
    {
        $this->filteredStatus = [$status];
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

        $this->searchText = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->dateRange = ($this->startDate && $this->endDate) ? $this->startDate . ' to ' . $this->endDate : "N/A";
        $this->watcherTasks = false;
    }

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            // The string contains 'to'
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
            // dd($this->startDate, $this->endDate);
        }
    }

    public function updatedSearchText()
    {
        $this->resetPage();
    }

    public function render()
    {
        $statuses = Task::STATUSES;

        $users = User::all();
        $user_types = User::TYPES;

        $tasks = Task::myTasksQuery($this->myTasks, $this->watcherTasks)
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                return $query->fromTo($startDate, $endDate);
            })
            ->when($this->searchText, function ($query) {
                return $query->searchByTitle($this->searchText);
            })
            ->when($this->filteredStatus, function ($query) {
                return $query->byStates($this->filteredStatus);
            })
            ->when($this->filteredStatus == null, function ($query) {
                return $query->byStates(['active']);
            })
            ->claims()
            ->paginate(10);


        return view('livewire.claim-index', [
            'tasks' => $tasks,
            'statuses' => $statuses,
            'filteredStatus' => $this->filteredStatus,
            'users' => $users,
            'user_types' => $user_types,
        ]);
    }
}
