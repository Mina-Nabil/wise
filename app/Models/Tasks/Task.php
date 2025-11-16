<?php

namespace App\Models\Tasks;

use App\Exceptions\NoManagerException;
use App\Models\Marketing\Review;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Task extends Model
{
    use SoftDeletes;

    const MORPH_TYPE = 'task';
    const FILES_DIRECTORY = 'tasks/';
    use HasFactory;

    protected $fillable = ['taskable_type', 'taskable_id', 'title', 'desc', 'open_by_id', 'assigned_to_id', 'last_action_by_id', 'due', 'file_url', 'type', 'status'];

    const STATUS_NEW = 'new'; //open but not assigned to anybode
    const STATUS_ASSIGNED = 'assigned'; //open and assigned
    const STATUS_IN_PROGRESS = 'in_progress'; //open and assigned and the assignee set it as in-progress
    const STATUS_PENDING = 'pending'; //waiting for external factor
    const STATUS_COMPLETED = 'completed'; //complete successfully
    const STATUS_CLOSED = 'closed'; //closed wkhalas

    const STATUSES = [self::STATUS_NEW, self::STATUS_ASSIGNED, self::STATUS_IN_PROGRESS, self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_CLOSED];

    const TYPE_TASK = 'task';
    const TYPE_CLAIM = 'claim';
    const TYPE_ENDORSMENT = 'endorsement';

    const TYPES = [self::TYPE_TASK, self::TYPE_CLAIM, self::TYPE_ENDORSMENT];

    /////model functions
    public function editTitleAndDesc($title, $desc = null)
    {
        try {
            /** @var User */
            $loggedInUser = Auth::user();
            if ($loggedInUser->can('updateMainInfo', $this)) {
                $this->title = $title;
            }
            $this->desc = $desc;
            $this->save();

            $this->addComment('Edited title/description', false);
            $this->sendTaskNotifications('Task edit', "Task edited by $loggedInUser->username");
            AppLog::info('Task title/desc edited', null, $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit task", $e->getMessage(), $this);
            return false;
        }
    }

    /**
     * Edit the task due, should only be done by the task owner
     * @param Carbon $due
     * @param string $comment - adding comment is optional
     * Adding comment is optional
     */
    public function editDue(Carbon $due, $comment = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updateDue', $this)) {
            return false;
        }
        try {
            $this->due = $due ? $due->format('Y-m-d H:i') : null;
            $this->save();
            if ($comment) {
                $this->addComment($comment, false);
            } else {
                $this->addComment('Updated Due', false);
            }
            $this->sendTaskNotifications('Task due update', "Due updated by $loggedInUser->username");
            AppLog::info("Task#$this->id due updated", null, $this);
            $this->last_action_by()->associate(Auth::id());
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit task", $e->getMessage(), $this);
            return false;
        }
    }

    public function assignTo($user_id_or_type, $comment = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser && !$loggedInUser->can('updateAssignTo', $this)) {
            return false;
        }
        $assignedToTitle = null;
        if (is_numeric($user_id_or_type)) {
            $this->assigned_to_id = $user_id_or_type;
            $this->assigned_to_type = null;
            $assignedToTitle = User::findOrFail($user_id_or_type)->username;
        } elseif (in_array($user_id_or_type, User::TYPES)) {
            $this->assigned_to_id = null;
            $this->assigned_to_type = $user_id_or_type;
            $assignedToTitle = $user_id_or_type;
        } else {
            AppLog::warning('Wrong input', "Trying to set Task#$this->id to $user_id_or_type", $this);
            return false;
        }

        try {
            if ($this->status == self::STATUS_NEW && $this->assigned_to_id) {
                $this->status = self::STATUS_ASSIGNED;
            }
            $this->save();
            $this->last_action_by()->associate(Auth::id());
            if ($comment) {
                $this->addComment($comment, false);
            } else {
                $this->addComment("Task assigned to $assignedToTitle", false);
            }
            AppLog::info("Task Assigned to $assignedToTitle", null, $this);
            $this->sendTaskNotifications('Assigned task', "Check Task#$this->id's assignee changed");

            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't assign task", $e->getMessage(), $this);
            return false;
        }
    }

    public function addField($title, $value): TaskField|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        try {
            $field = $this->fields()->updateOrCreate(
                [
                    'title' => $title,
                ],
                [
                    'value' => $value,
                ],
            );
            if ($field) {
                AppLog::info('Field added', loggable: $this);
                $this->last_action_by()->associate(Auth::id());
                if ($loggedInUser) {
                    $this->sendTaskNotifications('Field added', "Task#{$this->id} has a new field by $loggedInUser->username");
                }
            }
            return $field;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add field", $e->getMessage(), $this);
            return false;
        }
    }

    public function addComment($comment, $logEvent = true): TaskComment|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        try {
            $comment = $this->comments()->create([
                'user_id' => $loggedInUser ? $loggedInUser->id : null,
                'comment' => $comment,
            ]);
            if ($logEvent && $loggedInUser) {
                AppLog::info('Comment added', "User $loggedInUser->username added new comment to task $this->id", $this);
                $this->last_action_by()->associate(Auth::id());
                $this->sendTaskNotifications('Comment added', "Task#$this->id has a new comment by $loggedInUser->username");
            }

            return $comment;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add comment", $e->getMessage(), $this);
            return false;
        }
    }

    public function addFile($name, $file_url)
    {
        try {
            /** @var User */
            $loggedInUser = Auth::user();
            $this->files()->create([
                'user_id' => $loggedInUser->id,
                'name' => $name,
                'file_url' => $file_url,
            ]);
            $this->addComment('File uploaded', false);
            $this->last_action_by()->associate(Auth::id());
            $this->sendTaskNotifications('File uploaded', "New file added to Task#$this->id");
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add file", $e->getMessage(), $this);
            return false;
        }
    }

    public function removeFile($file_id)
    {
        try {
            $this->files()->where('id', $file_id)->delete();
            $this->last_action_by()->associate(Auth::id());
            $this->addComment('File deleted', false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add file", $e->getMessage(), $this);
            return false;
        }
    }

    public function setWatchers(array $user_ids)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updateMainInfo', $this)) {
            return false;
        }

        try {
            $this->watchers()->sync($user_ids);
            $this->addComment('Changed watchers list', false);
            $this->last_action_by()->associate($loggedInUser);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't Set watchers", $e->getMessage(), $this);
            return false;
        }
    }

    public function tempAssignTo($user_id, Carbon $end_date, $note = null): TaskTempAssignee|false
    {
        try {
            /** @var User */
            $loggedInUser = Auth::user();
            /** @var User */
            $user = User::findOrFail($user_id);
            $user->load('manager');
            if (!$user->manager) {
                throw new NoManagerException();
            }
            $newTmpAssignee = $this->temp_assignee()->updateOrCreate(
                [],
                [
                    'user_id' => $user_id,
                    'end_date' => $end_date->format('Y-m-d H:i'),
                    'note' => $note,
                ],
            );
            $user->manager->pushNotification('New Task Assignment Request', "$user->username requested a temporary assignment", 'temprequests/' . $newTmpAssignee->id);
            $this->last_action_by()->associate($loggedInUser);
            return $newTmpAssignee;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't temp assign", $e->getMessage(), $this);
            return false;
        }
    }

    public function setStatus($status, $comment, $accepted_actions_ids = [])
    {
        if (!in_array($status, self::STATUSES)) {
            return false;
        }
        /** @var User */
        $loggedInUser = Auth::user();

        $this->addComment("Changing status from $this->status to $status", false);
        $this->status = $status;
        DB::transaction(function () use ($status, $loggedInUser, $accepted_actions_ids, $comment) {

            $this->save();
            if ($status == self::STATUS_COMPLETED) {
                foreach ($this->actions as $a) {
                    if (in_array($a->id, $accepted_actions_ids)) {
                        if (!$a->confirmAction()) throw new Exception('Unauthorized');
                    } else {
                        $a->rejectAction();
                    }
                }
            }
            if ($comment) {
                $this->addComment($comment, false);
            }
            $this->last_action_by()->associate($loggedInUser);
            if (($status == self::STATUS_COMPLETED || $status == self::STATUS_CLOSED) && $this->type == self::TYPE_CLAIM) {
                try {
                    Review::createReview($this, "Claim Review", "Claim# $this->id completed");
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't create claim review", $e->getMessage(), $this);
                }
            }
            $this->sendTaskNotifications('Status changed', "Task#$this->id is set to $status");
            return true;
        });

        try {
            AppLog::info('Status changed', "Task#$this->id state changed to $status", $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't change task status", $e->getMessage(), $this);
            return false;
        }
    }

    public function sendTaskNotifications($title, $message)
    {
        $notifier_id = Auth::id();
        if ($notifier_id != $this->open_by_id) {
            $this->load('open_by');
            $this->open_by?->pushNotification($title, $message, 'tasks/' . $this->id);
        }
        if ($notifier_id != $this->assigned_to_id) {
            $this->load('assigned_to');
            $this->assigned_to?->pushNotification($title, $message, 'tasks/' . $this->id);
        }
        $this->load('watchers');
        foreach ($this->watchers as $watcher) {
            if ($notifier_id != $watcher->id) {
                $watcher->pushNotification($title, $message, 'tasks/' . $this->id);
            }
        }
    }

    public function addAction($column_name, $value)
    {
        /** @var User */
        $loggedInUser = Auth::user();

        // Only allow adding actions to endorsement tasks
        if (!$this->is_endorsment) {
            AppLog::warning('Attempted to add action to non-endorsement task', "Task#$this->id is not an endorsement", $this);
            return false;
        }

        if ($this->taskable == null) {
            AppLog::warning('Attempted to add action to task without taskable', "Task#$this->id has no taskable", $this);
            return false;
        }

        // Validate column_name if taskable is SoldPolicy
        if (is_a($this->taskable, \App\Models\Business\SoldPolicy::class)) {
            $validColumns = TaskAction::COLUMNS[TaskAction::TABLE_SOLD_POLICY] ?? [];
            if (!in_array($column_name, $validColumns)) {
                AppLog::warning('Invalid column name for action', "Column '$column_name' is not valid for sold policy", $this);
                return false;
            }
        }

        try {
            $action = $this->actions()->firstOrCreate(
                [
                    'column_name' => $column_name,
                ],
                [
                    'title' => "Change {$column_name}",
                    'value' => $value,
                    'status' => TaskAction::STATUS_NEW,
                ],
            );

            if ($action) {
                $this->last_action_by()->associate($loggedInUser);
                $this->addComment("Action added: Change {$column_name} to " . ($value ?? 'NULL'), false);
                AppLog::info('Action added to endorsement', "Added action to change {$column_name} in Task#$this->id", $this);
                return $action;
            }

            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add action to task", $e->getMessage(), $this);
            return false;
        }
    }

    /////static functions
    /**
     * @param array $files must contain array of ['name' => filename, 'file_url' => url, 'user_id'  => user_id] records
     */
    public static function newTask($title, Model $taskable = null, $assign_to_id_or_type = null, Carbon $due = null, $desc = null, $files = [], $watchers = [], $type = Task::TYPE_TASK)
    {
        try {
            $loggedInUser = Auth::user();
            $newTask = new self([
                'title' => $title,
                'desc' => $desc,
                'due' => $due ? $due->format('Y-m-d H:i') : null,
                'status' => self::STATUS_NEW,
                'last_action_by_id' => $loggedInUser?->id,
                'open_by_id' => $loggedInUser?->id,
                'desc' => $desc,
                'type' => $type,
            ]);
            if ($taskable) {
                $newTask->taskable()->associate($taskable);
            }
            $newTask->save();

            if ($loggedInUser) {
                $newTask->open_by()->associate($loggedInUser);
                $newTask->comments()->create([
                    'comment' => "Task created by $loggedInUser->username",
                ]);
            } else {
                $newTask->comments()->create([
                    'comment' => 'Task generated by system',
                ]);
            }
            if ($assign_to_id_or_type) {
                $newTask->assignTo($assign_to_id_or_type);
            }

            if ($files && count($files) > 0) {
                $newTask->files()->createMany($files);
            }

            if ($watchers && count($watchers) > 0) {
                $newTask->setWatchers($watchers);
            }

            AppLog::info('New task created', null, $newTask);
            return $newTask;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create task", $e->getMessage());
            return false;
        }
    }

    /**
     * export tasks functions
     */
    public static function exportReport(
        Carbon $created_from = null,
        Carbon $created_to = null,
        Carbon $due_from = null,
        Carbon $due_to = null,
        string $assignee_id = null,
        string $openedBy_id = null,
        bool $is_expired = null
    ) {
        $tasks = self::report($created_from, $created_to, $due_from, $due_to, $assignee_id, $openedBy_id, $is_expired)->get();
        $template = IOFactory::load(resource_path('import/tasks_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        foreach ($tasks as $task) {
            $activeSheet->getCell('A' . $i)->setValue($task->created_at->format('D d/m'));
            $activeSheet->getCell('B' . $i)->setValue(Carbon::parse($task->due)->format('D d/M H:i'));
            $activeSheet->getCell('C' . $i)->setValue($task->assigned_to_id ? $task->assigned_to?->first_name . ' ' . $task->assigned_to?->last_name : ($task->assigned_to_type ? $task->assigned_to_type : '-'));
            $activeSheet->getCell('D' . $i)->setValue($task->title);
            $activeSheet->getCell('E' . $i)->setValue($task->status);
            $activeSheet->getCell('F' . $i)->setValue($task->open_by?->first_name . ' ' . $task->open_by?->last_name);
            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "tasks_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    /////automatically set the last action by date
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $loggedInUser = Auth::user();
            $model->last_action_by()->associate($loggedInUser);
        });

        self::updating(function ($model) {
            $loggedInUser = Auth::user();
            $model->last_action_by()->associate($loggedInUser);
        });
    }

    ////attributes
    public function getIsClaimAttribute()
    {
        return $this->type == self::TYPE_CLAIM;
    }

    public function getIsEndorsmentAttribute()
    {
        return $this->type == self::TYPE_ENDORSMENT;
    }

    public function getIsTaskAttribute()
    {
        return $this->type == self::TYPE_TASK;
    }

    ////scopes
    public static function scopeMyTasksQuery($query, $assignedToMeOnly = true, $includeWatchers = true, $upcoming_only = false): Builder
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('tasks.*')->leftjoin('users', 'tasks.assigned_to_id', '=', 'users.id')->leftjoin('task_temp_assignee', 'task_temp_assignee.task_id', '=', 'tasks.id')->leftjoin('task_watchers', 'task_watchers.task_id', '=', 'tasks.id')->groupBy('tasks.id')->orderBy('tasks.due');

        if (!($loggedInUser->is_admin && !$assignedToMeOnly)) {
            $query
                ->where(function ($q) use ($loggedInUser, $includeWatchers) {
                    $q->whereIn('users.manager_id', $loggedInUser->children_ids_array)
                        ->orwhere('tasks.assigned_to_type', $loggedInUser->type)
                        ->when($loggedInUser->is_operations, fn($q) => $q->orwhere('users.type', User::TYPE_CLAIMS))
                        ->orwhere('tasks.assigned_to_id', $loggedInUser->id)
                        ->orwhere('tasks.open_by_id', $loggedInUser->id)
                        ->orwhere(function ($qu) use ($loggedInUser) {
                            $qu->where('task_temp_assignee.user_id', $loggedInUser->id)
                                ->where('task_temp_assignee.status', TaskTempAssignee::STATUS_ACCEPTED)
                                ->whereDate('task_temp_assignee.end_date', '>=', Carbon::now()->format('Y-m-d'));
                        });
                    $q->when($includeWatchers, fn($qq) => $qq->orwhere('task_watchers.user_id', $loggedInUser->id));
                })
                ->when($assignedToMeOnly, function ($qq) use ($loggedInUser, $includeWatchers) {
                    $qq->where('assigned_to_id', $loggedInUser->id);
                    $qq->when($includeWatchers, fn($qqq) => $qqq->orwhere('task_watchers.user_id', $loggedInUser->id));
                });
        }

        $query->when($upcoming_only, function ($q) {
            $now = new Carbon();
            $q->whereBetween('due', [$now->format('Y-m-01'), $now->addMonth()->format('Y-m-t')]);
        });

        return $query;
    }

    public function scopeByStates($query, array $states)
    {
        if (in_array('all', $states)) {
            return $query;
        }
        if (in_array('active', $states)) {
            array_push($states, self::STATUS_ASSIGNED);
            array_push($states, self::STATUS_IN_PROGRESS);
            array_push($states, self::STATUS_NEW);
            array_push($states, self::STATUS_PENDING);
        }
        return $query->whereIn('tasks.status', $states);
    }

    public function scopeClaims($query)
    {
        return $query->where('tasks.type', self::TYPE_CLAIM);
    }

    public function scopeEndorsments($query)
    {
        return $query->where('tasks.type', self::TYPE_ENDORSMENT);
    }

    public function scopeNormalTasks($query)
    {
        return $query->where('tasks.type', self::TYPE_TASK);
    }

    public function scopeByTypes($query, array $types)
    {
        return $query->whereIn('tasks.type', $types);
    }

    public function scopeSearchByTitle($query, $text)
    {
        return $query->where('tasks.title', 'LIKE', '%' . $text . '%');
    }

    public function scopeOpenBy($query, $user_id)
    {
        return $query->where('tasks.open_by_id', $user_id);
    }

    public function scopeAssignedTo($query, $user_id)
    {
        return $query->where('tasks.assigned_to_id', $user_id);
    }

    public function scopeLastActionBy($query, $user_id)
    {
        return $query->where('tasks.last_action_by_id', $user_id);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('tasks.due', Carbon::now()->format('Y-m-d'));
    }

    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('tasks.due', Carbon::tomorrow()->format('Y-m-d'));
    }

    public function scopeFromTo($query, Carbon $from, Carbon $to)
    {
        return $query->where(function ($query) use ($from, $to) {
            $query->whereBetween('tasks.due', [$from->format('Y-m-d'), $to->format('Y-m-d')])->orWhereNull('tasks.due');
        });
    }

    public function scopeReport(
        Builder $query,
        ?Carbon $created_from = null,
        ?Carbon $created_to = null,
        ?Carbon $due_from = null,
        ?Carbon $due_to = null,
        ?string $assignee_id = null,
        ?string $openedBy_id = null,
        ?bool $is_expired = null,
        ?string $search = null
    ) {
        // Filter by creation date range
        $query->myTasksQuery(false)
            ->when($created_from, function ($q, $v) {
                $q->where('created_at', '>=', $v->startOfDay());
            })->when($created_to, function ($q, $v) {
                $q->where('created_at', '<=', $v->endOfDay());
            })->when($search, function ($q, $v) {
                $q->searchByTitle($v);
            });

        // Filter by due date range
        $query->when($due_from, function ($q, $v) {
            $q->where('due', '>=', $v->startOfDay());
        })->when($due_to, function ($q, $v) {
            $q->where('due', '<=', $v->endOfDay());
        })->when($assignee_id, function ($q, $v) {
            $q->assignedTo($v);
        })->when($openedBy_id, function ($q, $v) {
            $q->openBy($v);
        });

        // Filter by expiration status
        if ($is_expired !== null) {
            $query->where('due', $is_expired ? '<' : '>=', now());
        }

        return $query;
    }

    /////relations
    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    public function open_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'open_by_id');
    }

    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function last_action_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_action_by_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(TaskField::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    public function actions(): HasMany
    {
        return $this->hasMany(TaskAction::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class);
    }

    public function watcher_ids(): HasMany
    {
        return $this->hasMany(TaskWatcher::class);
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_watchers');
    }

    public function temp_assignee(): HasOne
    {
        return $this->hasOne(TaskTempAssignee::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
