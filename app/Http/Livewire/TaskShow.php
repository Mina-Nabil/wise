<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\Task;
use App\Models\Users\User;
use App\Models\Users\TaskComment;


class TaskShow extends Component
{
    public $taskId;
    public $taskTitle;
    public $assignedTo;
    public $desc;
    public $taskStatus;
    public $due;
    public $newComment;
    public $taskableType;
    public $changes = false;

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $task = Task::findOrFail($this->taskId);
        $this->taskTitle = $task->title;
        $this->assignedTo = $task->assigned_to_id;
        $this->desc = $task->desc;
        $this->taskStatus = $task->status;
        $this->due = $task->due;
        $this->taskableType = $task->taskable_type;
    }

    public function updatedTaskTitle()
    {
        $this->changes = true;
    }

    public function updatedAssignedTo()
    {
        $this->changes = true;
    }
    public function updatedDesc()
    {
        $this->changes = true;
    }
    public function updatedTaskStatus()
    {
        $this->changes = true;
    }
    public function updatedDue()
    {
        $this->changes = true;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required',
        ]);


        $task = Task::find($this->taskId);

        $com = $task->addComment($this->newComment);

        if ($com) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Comment Added!',
                'type' => 'success', // or 'failed' or 'info'
            ]);

            $this->newComment = null;
        } else {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Error Adding Comment!',
                'type' => 'failed', // or 'failed' or 'info'
            ]);
        }
    }


    public function save()
    {
        $task = new Task();
        $t = $task->editTask(
            $this->taskId,
            $this->taskTitle,
            $this->assignedTo,
            $this->due,
            $this->desc,
            $this->taskStatus
        );

        if ($t) {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'Task Updated Successfuly',
                'type' => 'success', // or 'failed' or 'info'
            ]);

            $this->changes = false;
        } else {
            $this->dispatchBrowserEvent('toastalert', [
                'message' => 'failed to update!',
                'type' => 'failed', // or 'failed' or 'info'
            ]);
        }
    }


    public function render()
    {
        $comments = TaskComment::where('task_id', $this->taskId)
            ->orderBy('created_at', 'desc')
            ->get();

        $statuses = Task::STATUSES;
        $users = User::all();

        return view('livewire.task-show', [
            'comments' => $comments,
            'taskId' => $this->taskId,
            'taskTitle' => $this->taskTitle,
            'assignedTo' => $this->assignedTo,
            'desc' => $this->desc,
            'taskStatus' => $this->taskStatus,
            'due' => $this->due,
            'statuses' => $statuses,
            'users' => $users,
            'taskableType' => $this->taskableType,
        ]);
    }
}
