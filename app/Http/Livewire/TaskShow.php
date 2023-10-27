<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\Task;
use App\Models\Users\User;
use App\Models\Users\TaskComment;
use App\Traits\AlertFrontEnd;

class TaskShow extends Component
{
    use AlertFrontEnd;

    public $task;
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
        $task = Task::with('comments', 'comments.user')->findOrFail($this->taskId);
        $this->taskTitle = $task->title;
        $this->assignedTo = $task->assigned_to_id;
        $this->desc = $task->desc;
        $this->taskStatus = $task->status;
        $this->due = $task->due;
        $this->taskableType = $task->taskable_type;
        $this->task = $task;
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
            $this->alert('success', 'Comment Added!');
            $this->newComment = null;
        } else {
            $this->alert('failed', 'Error Adding Comment!');
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
            $this->alert('success', 'Task Updated Successfuly!');
            $this->changes = false;
        } else {
            $this->alert('failed', 'failed to update!');
        }
    }

    public function delete()
    {
        /** @var Task */
        $task = Task::findOrFail($this->taskId);
        $res = $task->delete();
        if ($res) {
            $this->alert('success', 'Task deleted');
            return redirect()->to('/tasks');
        } else $this->alert('failed', 'Task deletion failed');
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
