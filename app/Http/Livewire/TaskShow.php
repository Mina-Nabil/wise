<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Users\User;
use App\Models\Tasks\TaskComment;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TaskShow extends Component
{
    use AlertFrontEnd;

    public Task $task;
    public $taskId;
    public $taskTitle;
    public $assignedTo;
    public $desc;
    public $taskStatus;
    public $dueDate;
    public $dueTime;
    public $newComment;
    public $taskableType;
    public $watchersList = [];
    public $setWatchersList;
    public $editedStatus;
    public $statusComment;
    public $changeStatus = false;
    // public $fileUrl;
    public $changes = false;
    public $changeWatchers = false;

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        /** @var Task */
        $task = Task::with('comments', 'comments.user')->findOrFail($this->taskId);
        $this->taskTitle = $task->title;
        $this->assignedTo = $task->assigned_to_id;
        $this->desc = $task->desc;
        $this->taskStatus = $task->status;
        $this->watchersList = $task->watcher_ids;
        $this->editedStatus = $task->status;

        // dd($this->watchersList->pluck('user_id')->all());



        $createdAt = Carbon::parse($task->due);
        $this->dueDate = $createdAt->toDateString();
        $this->dueTime = $createdAt->format('H:i');

        $this->taskableType = $task->taskable_type;
        $this->task = $task;
    }

    public function OpenChangeWatchers(){
        
        $this->changeWatchers = true;
    }
    public function closeChangeWatchers(){
        $this->changeWatchers = false;
    }
    public function saveWatchers(){
        $task = Task::find($this->taskId);
        $t = $task->setWatchers($this->setWatchersList);
        if($t){
            $this->alert('success', 'Watchers Updated!');
            $this->closeChangeWatchers();
            $this->mount($this->taskId);
        }else{
            $this->alert('failed', 'Server Error!');
        }
    }

    public function toggleEditStatus(){

        if($this->changeStatus === true){
            $this->changeStatus = false;
        }else{
            $this->changeStatus =true;
        }
    }
    public function saveStatuses(){
        // dd($this->editedStatus);
        $task = Task::findOrFail($this->taskId);
        $task->setStatus($this->editedStatus,$this->statusComment);
        $this->mount($this->taskId);
    }

    public function downloadFile()
    {
        $fileContents = Storage::disk('s3')->get($this->fileUrl);
        $fileName = $this->fileUrl;
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers
        );
    }

    public function setWatchers(){

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
    public function updatedDueDate()
    {
        $this->changes = true;
    }
    public function updatedDueTime()
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
        $this->validate([
            'taskTitle' => 'required|string|max:255',
            'assignedTo' => 'required|integer|exists:users,id',
            'desc' => 'nullable|string',
            'dueDate' => 'required|date',
            'dueTime' => 'nullable|date_format:H:i',
            'taskStatus' => 'required|in:' . implode(',', Task::STATUSES),
        ], [], [
            'taskTitle' => 'Title',
            'assignedTo' => 'Assignee',
            'desc' => 'Description',
            'dueDate' => 'Date',
            'dueTime' => 'Time',
            'taskStatus' => 'Status',
        ]);

        $dueDate = $this->dueDate ? Carbon::parse($this->dueDate) : null;
        $dueTime = $this->dueTime ? Carbon::parse($this->dueTime) : null;
        $combinedDateTime = $dueTime ? $dueDate->setTime($dueTime->hour, $dueTime->minute, $dueTime->second) : $dueDate;

        $res = $this->task->editTask(
            $this->taskId,
            $this->taskTitle,
            $this->assignedTo,
            $combinedDateTime,
            $this->desc,
            $this->taskStatus,
            $this->fileUrl,
        );

        if ($res) {
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
            'dueDate' => $this->dueDate,
            'dueTime' => $this->dueTime,
            'statuses' => $statuses,
            'users' => $users,
            // 'fileUrl' => $this->fileUrl,
            'taskableType' => $this->taskableType,
            'watchersList' => $this->watchersList,
        ]);
    }
}
