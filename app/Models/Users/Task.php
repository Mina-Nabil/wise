<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [];

    const STATUS_NEW = 'new'; //open but not assigned to anybode
    const STATUS_ASSIGNED = 'assigned'; //open and assigned
    const STATUS_IN_PROGRESS = 'in_progress'; //open and assigned and the assignee set it as in-progress
    const STATUS_PENDING = 'pending'; //waiting for external factor
    const STATUS_COMPLETED = 'completed'; //complete successfully
    const STATUS_CLOSED = 'closed'; //closed wkhalas

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_ASSIGNED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_CLOSED,
    ];

    /////model functions
    public function assignTo($user_id)
    {
        $this->status = self::STATUS_ASSIGNED;
        $this->assigned_to_id = $user_id;
        try {
            $loggedInUser = Auth::user();
            AppLog::info("Task Assigned to user $user_id");
            $this->comments()->create([
                "comment"   =>  "Task assigned to $loggedInUser->username by $this->username"
            ]);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't assign task", $e->getMessage());
            return false;
        }
    }

    public function addComment($comment): TaskComment|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        try {
            $comment = $this->comments()->create([
                "user_id"   =>  $loggedInUser ? $loggedInUser->id : null,
                "comment"   =>  $comment
            ]);
            $this->last_action_by()->associate($loggedInUser);
            AppLog::info("Comment added", "User $loggedInUser->username added new comment to task $this->id");
            return $comment;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add comment", $e->getMessage());
            return false;
        }
    }

    public function setState($state)
    {
        $this->status = $state;
        try {
            AppLog::info("Task state changed", "Task#$this->id state changed to $state");
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't change task state", $e->getMessage());
            return false;
        }
    }

    /////static functions
    public static function newTask($title, Model $taskable = null, $assign_to_id = null, Carbon $due = null, $desc = null)
    {
        try {
            $loggedInUser = Auth::user();
            $newTask = new self([
                "title"     =>  $title,
                "desc"      =>  $desc,
                "due"      =>  $due ? $due->format('Y-m-d H:i') : null,
                "status"    =>  self::STATUS_NEW,
                "desc"      =>  $desc,
            ]);
            if ($loggedInUser) {
                $newTask->open_by()->associate($loggedInUser);
                $newTask->comments()->create([
                    "comment"   =>  "Task created by $loggedInUser->username"
                ]);
            } else {
                $newTask->comments()->create([
                    "comment"   =>  "Task generated by system"
                ]);
            }
            if ($assign_to_id) {
                $newTask->assigned_to()->associate($assign_to_id);
            }
            if ($taskable) {
                $newTask->taskable()->associate($taskable);
            }
            $newTask->comments()->create([
                "comment"   =>  "Task created by $loggedInUser->username"
            ]);
            AppLog::info("New task created");
            return $newTask;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create task", $e->getMessage());
            return false;
        }
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $loggedInUser = Auth::user();
            $model->last_action_by()->associate($loggedInUser);
        });

        self::updated(function ($model) {
            $loggedInUser = Auth::user();
            $model->last_action_by()->associate($loggedInUser);
        });
    }

    //scopes
    public function scopeByStates($query, array $states)
    {
        return $query->whereIn("status", $states);
    }

    public function scopeOpenBy($query, $user_id)
    {
        return $query->where('open_by_id', $user_id);
    }

    public function scopeAssignedTo($query, $user_id)
    {
        return $query->where('assigned_to_id', $user_id);
    }

    public function scopeLastActionBy($query, $user_id)
    {
        return $query->where('last_action_by_id', $user_id);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate("due", Carbon::now()->format('Y-m-d'));
    }

    public function scopeDueTomorrow($query)
    {
        return $query->whereDate("due", Carbon::tomorrow()->format('Y-m-d'));
    }

    public function scopeFromTo($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween("due", [$from->format('Y-m-d', $to->format('Y-m-d'))]);
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

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
}