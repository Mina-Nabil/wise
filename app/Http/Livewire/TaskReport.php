<?php

namespace App\Http\Livewire;

use App\Models\Tasks\Task;
use App\Models\Users\User;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TaskReport extends Component
{
    use WithPagination, ToggleSectionLivewire;

    public $startSection = false;
    public $dueSection = false;

    public $Estart_from;
    public $start_from;
    public $Estart_to;
    public $start_to;
    public $Edue_from;
    public $due_from;
    public $Edue_to;
    public $due_to;

    public $assigneeSection = false;
    public $assigneeName;
    public $assignee_id;
    public $Eassignee_id;

    public $creatorSection = false;
    public $creatorName;
    public $creator_id;
    public $Ecreator_id;

    public $isExpired;

    protected $queryString = ['start_from', 'start_to', 'due_from', 'due_to', 'isExpired', 'assignee_id','creator_id'];

    public function toggleAssignee()
    {
        $this->toggle($this->assigneeSection);
        if ($this->assigneeSection) {
            $this->Eassignee_id = $this->assignee_id;
        }
    }

    public function setAssignee()
    {
        $this->assignee_id = $this->Eassignee_id;
        $this->toggle($this->assigneeSection);
    }
    public function clearAssignee()
    {
        $this->assignee_id = null;
    }

    public function toggleCreator()
    {
        $this->toggle($this->creatorSection);
        if ($this->creatorSection) {
            $this->Ecreator_id = $this->creator_id;
        }
    }

    public function setCreator()
    {
        $this->creator_id = $this->Ecreator_id;
        $this->toggle($this->creatorSection);
    }

    public function clearCreator()
    {
        $this->creator_id = null;
    }

    public function toggleIsExpired()
    {
        $this->toggle($this->isExpired);
    }

    public function clearExpired()
    {
        $this->isExpired = null;
    }

    public function toggleDueDate()
    {
        $this->toggle($this->dueSection);
        if ($this->dueSection) {
            $this->Edue_from = Carbon::parse($this->due_from)->toDateString();
            $this->Edue_to = Carbon::parse($this->due_to)->toDateString();
        }
    }

    public function setDueDates()
    {
        $this->due_from = Carbon::parse($this->Edue_from);
        $this->due_to = Carbon::parse($this->Edue_to);
        $this->toggle($this->dueSection);
    }

    public function clearDueDates()
    {
        $this->due_from = null;
        $this->due_to = null;
    }

    public function exportReport()
    {
        Task::exportReport($this->start_from, $this->start_to, $this->due_from, $this->due_to, $this->assignee_id,$this->creator_id, $this->isExpired);
    }

    public function toggleStartDate()
    {
        $this->toggle($this->startSection);
        if ($this->startSection) {
            $this->Estart_from = Carbon::parse($this->start_from)->toDateString();
            $this->Estart_to = Carbon::parse($this->start_to)->toDateString();
        }
    }

    public function setStartDates()
    {
        $this->start_from = Carbon::parse($this->Estart_from);
        $this->start_to = Carbon::parse($this->Estart_to);
        $this->toggle($this->startSection);
    }

    public function clearStartDates()
    {
        $this->start_from = null;
        $this->start_to = null;
    }

    public function updatedStartFrom()
    {
        $this->resetPage();
    }

    public function updatedStartTo()
    {
        $this->resetPage();
    }

    public function updatedDueFrom()
    {
        $this->resetPage();
    }

    public function updatedDueTo()
    {
        $this->resetPage();
    }

    public function updatedIsExpired()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->creator_id) {
            $c = User::find($this->creator_id);
            $this->creatorName = ucwords($c->first_name) . ' ' . ucwords($c->last_name);
        }

        if ($this->assignee_id) {
            $c = User::find($this->assignee_id);
            $this->assigneeName = $c ? ucwords($c->first_name) . ' ' . ucwords($c->last_name) : $this->assignee_id;
        }

        $users = User::all();
        $tasks = Task::Report($this->start_from, $this->start_to, $this->due_from, $this->due_to, $this->assignee_id, $this->creator_id, $this->isExpired)->paginate(10);
        return view('livewire.task-report', [
            'tasks' => $tasks,
            'users' => $users,
            'types' => User::TYPES,
        ]);
    }
}
