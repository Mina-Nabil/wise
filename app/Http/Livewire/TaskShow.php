<?php

namespace App\Http\Livewire;

use App\Exceptions\NoManagerException;
use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskFile;
use App\Models\Users\User;
use App\Models\Tasks\TaskComment;
use App\Models\Tasks\TaskField;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\URL;

class TaskShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

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
    public $changeTitleDesc = false;
    public $changeAsignee = false;
    public $changeDue = false;
    public $haveDueTime = true;
    public $assignedToComment;
    // public $fileUrl;
    public $changes = false;
    public $changeWatchers = false;
    public $uploadedFile;
    public $uploadedFiles = [];
    public $sendTempAssignSection = false;
    public $TempAssignUser;
    public $TempAssignDate;
    public $TempAssignNote;
    public $preview;
    public $SetTempAssignSection = false;
    public $deleteSection = false;

    public $fieldId;
    public $editedFieldValue;
    public $deleteFieldId;
    public $addFieldSec = false;
    public $newTitle;
    public $newValue;

    public $completeEndorsmentSec = false;
    public $actionsIds = [];

    public function closeCompleteEndorsmenet()
    {
        $this->completeEndorsmentSec = false;
    }

    public function openAddField()
    {
        $this->addFieldSec = true;
    }

    public function closeAddField()
    {
        $this->addFieldSec = false;
        $this->newTitle = null;
        $this->newValue = null;
    }

    public function addField()
    {
        $this->validate([
            'newTitle' => 'required|in:' . implode(',', TaskField::TITLES),
            'newValue' => 'required|string|max:255'
        ]);

        $res = Task::find($this->taskId)->addField($this->newTitle, $this->newValue);

        if ($res) {
            $this->closeAddField();
            $this->mount($this->taskId);
            $this->alert('success', 'Field added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteThisField($id)
    {
        $this->deleteFieldId = $id;
    }

    public function deleteField()
    {
        $res = TaskField::find($this->deleteFieldId)->delete();
        if ($res) {
            $this->deleteFieldId = null;
            $this->mount($this->taskId);
            $this->alert('success', 'Claim deleted!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function dismissDeleteField()
    {
        $this->deleteFieldId = null;
    }

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


        foreach ($task->actions as $action) {
            array_push($this->actionsIds, $action->id);
        }


        // dd($this->watchersList->pluck('user_id')->all());

        $createdAt = Carbon::parse($task->due);
        $this->dueDate = $createdAt->toDateString();
        $this->dueTime = $createdAt->format('H:i');

        $this->taskableType = $task->taskable_type;
        $this->task = $task;
    }

    public function editThisField($id)
    {
        $this->fieldId = $id;
        $field = TaskField::find($id);
        $this->editedFieldValue = $field->value;
    }

    public function editField()
    {
        $this->validate([
            'editedFieldValue' => 'required|string|max:255'
        ]);

        $field = TaskField::find($this->fieldId);
        $res = $field->editInfo($field->title, $this->editedFieldValue);

        if ($res) {
            $this->fieldId = null;
            $this->mount($this->taskId);
            $this->alert('success', 'Claim updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function toggleDelete()
    {
        $this->toggle($this->deleteSection);
    }

    public function previewFile($id)
    {
        $task = TaskFile::findOrFail($id);
        $url = $task->file_url;
        // dd('aaa');
        $modifiedString = preg_replace('/\//', '', $url, 1);
        $this->preview = 'https://wiseins.s3.eu-north-1.amazonaws.com/' . $modifiedString;

        // dd($this->preview);
    }

    public function toggleSendTempAssign()
    {
        $this->toggle($this->sendTempAssignSection);
    }

    public function toggleSetTempAssign()
    {
        $this->toggle($this->SetTempAssignSection);
    }

    public function submitSetTempAssign()
    {
        $this->validate(
            [
                'TempAssignUser' => 'integer|exists:users,id',
                'TempAssignDate' => 'required|date',
                'TempAssignNote' => 'nullable|string',
            ],
            [],
            [
                'TempAssignUser' => 'User',
                'TempAssignDate' => 'Date',
                'TempAssignNote' => 'Note',
            ],
        );
        $task = Task::find($this->taskId);
        $TempAssignDate = $this->dueDate ? Carbon::parse($this->TempAssignDate) : null;
        $t = $task->tempAssignTo($this->TempAssignUser, $TempAssignDate, $this->TempAssignNote);
        if ($t) {
            $this->alert('success', 'Request Sent Successfuly!');
            $this->toggleSendTempAssign();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function submitTempAssignRequest()
    {
        $this->validate(
            [
                'TempAssignDate' => 'required|date',
                'TempAssignNote' => 'nullable|string',
            ],
            [],
            [
                'TempAssignDate' => 'Date',
                'TempAssignNote' => 'Note',
            ],
        );

        $task = Task::find($this->taskId);
        $TempAssignDate = $this->dueDate ? Carbon::parse($this->TempAssignDate) : null;
        try {
            $t = $task->tempAssignTo(Auth()->user()->id, $TempAssignDate, $this->TempAssignNote);
        } catch (NoManagerException $e) {
            $this->alert('failed', 'User has no manager to approve this request!');
            return;
        }
        $t = $task->tempAssignTo(Auth()->user()->id, $TempAssignDate, $this->TempAssignNote);
        if ($t) {
            $this->alert('success', 'Request Sent Successfuly!');
            $this->toggleSendTempAssign();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }


    public function updatedUploadedFiles()
    {
        $this->validate(
            [
                'uploadedFiles.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000'
            ],
            [
                'uploadedFiles.*.max' => 'The file must not be greater than 20MB.',
            ]
        );

        foreach ($this->uploadedFiles as $file) {
            $filename = $file->getClientOriginalName();
            $url = $file->store('files', 's3');

            $task = Task::find($this->taskId);
            $t = $task->addFile($filename, $url);

            if (!$t) {
                $this->alert('failed', 'Server Error!');
                return;
            }
        }

        $this->alert('success', 'Files Uploaded!');
        $this->mount($this->taskId);
    }

    public function removeFile($id)
    {
        $task = Task::find($this->taskId);
        $f = $task->removeFile($id);
        if ($f) {
            $this->alert('success', 'File removed!');
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function OpenChangeWatchers()
    {
        $this->changeWatchers = true;
    }
    public function closeChangeWatchers()
    {
        $this->changeWatchers = false;
    }
    public function saveWatchers()
    {
        $this->validate([
            'setWatchersList' => 'nullable|array',
            'setWatchersList.*' => 'integer|exists:users,id',
        ], [], [
            'setWatchersList' => 'Watchers',
        ]);
        $task = Task::find($this->taskId);
        $t = $task->setWatchers($this->setWatchersList);
        if ($t) {
            $this->alert('success', 'Watchers Updated!');
            $this->closeChangeWatchers();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    // changeDue
    public function toggleDue()
    {
        $this->toggle($this->changeDue);
    }

    public function saveDue()
    {
        $dueDate = $this->dueDate ? Carbon::parse($this->dueDate) : null;
        if ($this->haveDueTime) {
            $dueTime = $this->dueTime ? Carbon::parse($this->dueTime) : null;
        } else {
            $dueTime = null;
        }

        $combinedDateTime = $dueTime ? $dueDate->setTime($dueTime->hour, $dueTime->minute, $dueTime->second) : $dueDate;
        $task = Task::findOrFail($this->taskId);
        $t = $task->editDue($combinedDateTime, null);
        if ($t) {
            $this->alert('success', 'Due Updated!');
            $this->toggleDue();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function toggleEditAsignee()
    {
        $this->toggle($this->changeAsignee);
    }

    public function saveAsignee()
    {
        $this->validate([
            'assignedTo' => 'required|integer|exists:users,id',
            'assignedToComment' => 'nullable|string'
        ], [], [
            'assignedTo' => 'User',
            'assignedToComment' => 'Comment'
        ]);
        $task = Task::findOrFail($this->taskId);
        $t = $task->assignTo($this->assignedTo, $this->assignedToComment);
        if ($t) {
            $this->alert('success', 'Asignee Updated!');
            $this->toggleEditAsignee();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function toggleEditTitleDesc()
    {
        $this->toggle($this->changeTitleDesc);
    }

    public function saveTitleAndDesc()
    {
        $this->validate(
            [
                'taskTitle' => 'required|string|max:255',
                'desc' => 'nullable|string',
            ],
            [],
            [
                'taskTitle' => 'Title',
                'desc' => 'Description',
            ],
        );
        $task = Task::findOrFail($this->taskId);
        $t = $task->editTitleAndDesc($this->taskTitle, $this->desc);
        if ($t) {
            $this->alert('success', 'Task Updated!');
            $this->toggleEditTitleDesc();
            $this->mount($this->taskId);
        } else {
            $this->alert('failed', 'Server Error!');
        }
    }

    public function toggleEditStatus()
    {
        if ($this->changeStatus === true) {
            $this->changeStatus = false;
        } else {
            $this->changeStatus = true;
        }
    }
    public function saveStatuses()
    {
        if ($this->task->type === Task::TYPE_ENDORSMENT && !empty($this->task->actions) && $this->editedStatus === Task::STATUS_COMPLETED && !$this->completeEndorsmentSec) {
            $this->completeEndorsmentSec = true;
            return;
        } else {
            $this->completeEndorsmentSec = false;
        }
        $this->validate([
            'statusComment' => 'nullable|string',
            'editedStatus' => 'required|in:' . implode(',', Task::STATUSES),
        ]);
        $task = Task::findOrFail($this->taskId);
        $t = $task->setStatus($this->editedStatus, $this->statusComment, $this->actionsIds);
        if ($t) {
            $this->alert('success', 'Status Updated!');
            $this->toggleEditStatus();
        } else {
            $this->alert('failed', 'Server Error!');
        }
        $this->mount($this->taskId);
    }

    public function downloadFile($id)
    {
        $task = TaskFile::findOrFail($id);
        // $extension = pathinfo($task->name, PATHINFO_EXTENSION);
        $fileContents = Storage::disk('s3')->get($task->file_url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $task->name . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    public function setWatchers()
    {
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
        $this->validate(
            [
                'taskTitle' => 'required|string|max:255',
                'assignedTo' => 'required|integer|exists:users,id',
                'desc' => 'nullable|string',
                'dueDate' => 'required|date',
                'dueTime' => 'nullable|date_format:H:i',
                'taskStatus' => 'required|in:' . implode(',', Task::STATUSES),
            ],
            [],
            [
                'taskTitle' => 'Title',
                'assignedTo' => 'Assignee',
                'desc' => 'Description',
                'dueDate' => 'Date',
                'dueTime' => 'Time',
                'taskStatus' => 'Status',
            ],
        );

        $dueDate = $this->dueDate ? Carbon::parse($this->dueDate) : null;
        $dueTime = $this->dueTime ? Carbon::parse($this->dueTime) : null;
        $combinedDateTime = $dueTime ? $dueDate->setTime($dueTime->hour, $dueTime->minute, $dueTime->second) : $dueDate;

        $res = $this->task->editTask($this->taskId, $this->taskTitle, $this->assignedTo, $combinedDateTime, $this->desc, $this->taskStatus, $this->fileUrl);

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
        } else {
            $this->alert('failed', 'Task deletion failed');
        }
    }

    public function render()
    {
        $comments = TaskComment::where('task_id', $this->taskId)
            ->orderBy('created_at', 'desc')
            ->get();

        $statuses = Task::STATUSES;
        if ($this->task->assigned_to_type)
            $users = User::where('type', $this->task->assigned_to_type)->get();
        else $users = User::all();

        $fieldTitles = TaskField::TITLES;

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
            'fieldTitles' => $fieldTitles
        ]);
    }
}
