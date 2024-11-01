<?php

namespace App\Http\Livewire;

use App\Models\Tasks\Task;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TaskReport extends Component
{
    use WithPagination,ToggleSectionLivewire;
    
    public $page_title = '• Tasks Report';

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

    public $isExpired;

    protected $queryString = ['start_from','start_to','due_from','due_to','isExpired'];

    public function toggleIsExpired()
    {
        
        $this->toggle($this->isExpired);
    }

    public function clearExpired(){
        
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
        $tasks = Task::Report($this->start_from,$this->start_to,$this->due_from,$this->due_to,$this->isExpired)->paginate(10);
        return view('livewire.task-report',[
            'tasks' => $tasks
        ]);
    }
}
