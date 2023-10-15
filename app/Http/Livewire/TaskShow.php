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

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $task = Task::findOrFail($this->taskId);
        $this->taskTitle = $task->title;
        $this->assignedTo = $task->assigned_to_id;
        $this->desc = $task->desc;
        $this->taskStatus = $task->status;
        $this->due = $task->due;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required',
        ]);

        $task = Task::find($this->taskId);

        $task->addComment($this->newComment);

        $this->dispatchBrowserEvent('toastalert', [
            'message' => 'Comment Added!',
            'type' => 'info', // or 'failed' or 'info'
        ]);
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
        ]);
    }
}
