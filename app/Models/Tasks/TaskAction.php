<?php

namespace App\Models\Tasks;

use App\Models\Business\SoldPolicy;
use App\Models\Tasks\Task;
use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskAction extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'task_action';

    protected $table = 'task_actions';
    protected $fillable = [
        "title",
        "status",
        "column_name",
        "value"
    ];
    const STATUS_NEW = 'new';
    const STATUS_DONE = 'done';
    const STATUS_REJECTED = 'rejected';

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_DONE,
        self::STATUS_REJECTED
    ];

    const TABLE_SOLD_POLICY = 'sold_policies';

    const TABLES = [
        self::TABLE_SOLD_POLICY
    ];

    const COLUMNS = [
        self::TABLE_SOLD_POLICY => [
            'car_chassis',
            'car_engine',
            'car_plate_no',
            'insured_value',
            'expiry',
            'in_favor_to',
            'net_premium',
            'gross_premium',
            'cancellation_time',
        ]
    ];

    const COLUMNS_IN_CHANGE_SOLD = ['gross_premium', 'insured_value', 'expiry', 'start', 'net_premium'];

    ///static functions
    public static function changedSoldPoliciesIDs(Carbon $from, Carbon $to){
        return DB::table('task_actions')->whereIn('column_name', self::COLUMNS_IN_CHANGE_SOLD)
        ->join('tasks', 'tasks.id', '=', 'task_actions.task_id')
        ->where('task_actions.status', 'done')
        ->where('tasks.taskable_type', SoldPolicy::MORPH_TYPE)
        ->where('tasks.taskable_id', '!=', null)
        ->where('task_actions.updated_at', '>=', $from)
        ->where('task_actions.updated_at', '<=', $to)
        ->selectRaw('DISTINCT tasks.taskable_id')
        ->get()->pluck('taskable_id')->toArray();
    }

    public function confirmAction()
    {
        if ($this->status !== self::STATUS_NEW) return false;
        if ($this->makeAction()) {
            $this->status = self::STATUS_DONE;
            return $this->save();
        }
        return false;
    }

    private function makeAction()
    {


        if ($this->status !== self::STATUS_NEW) return false;

        try {
            $this->load('task', 'task.taskable');
            if (is_a($this->task->taskable, SoldPolicy::class)) {
                /** @var User */
                $loggedInUser = Auth::user();
                if (!$loggedInUser->can('updateClaim', $this->task->taskable)) return false;
            }
            $this->task->taskable->{$this->column_name} = $this->value;
            $this->task->taskable->save();
            if ($this->column_name == 'cancellation_time') {
                $this->task->taskable->cancelSoldPolicy();
            }
            if($this->column_name == 'gross_premium'){
                $this->task->taskable->createMissingClientPayments();
            }
            $new_val = $this->value ?? 'NULL';
            AppLog::info("Tast Action done", desc: "Changed column {$this->column_name} - {$this->value} to $new_val", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Tast Action failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function rejectAction()
    {
        if ($this->status !== self::STATUS_NEW) return false;

        $this->status = self::STATUS_REJECTED;
        return $this->save();
    }

    public function editValue($newValue)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        
        // Only allow editing if action status is 'new'
        if ($this->status !== self::STATUS_NEW) {
            AppLog::warning('Attempted to edit non-new action', "Action#$this->id is not in 'new' status", $this);
            return false;
        }

        // Load task relationship
        $this->load('task');
        
        // Check if task is closed or completed
        if (in_array($this->task->status, [Task::STATUS_COMPLETED, Task::STATUS_CLOSED])) {
            AppLog::warning('Attempted to edit action in closed/completed task', "Task#$this->task->id is {$this->task->status}", $this);
            return false;
        }

        // Store old value for comment
        $oldValue = $this->value ?? 'NULL';

        try {
            // Only update the value
            $this->value = $newValue;
            $res = $this->save();

            if ($res) {
                // Add comment to task about the value change
                $actionTitle = ucwords(str_replace('_', ' ', $this->title));
                $this->task->addComment("Action value edited: {$actionTitle} changed from '{$oldValue}' to '" . ($newValue ?? 'NULL') . "'", false);
                AppLog::info('Action value edited', "Action#$this->id value changed from '{$oldValue}' to '" . ($newValue ?? 'NULL') . "'", $this);
                return true;
            }

            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit action value", $e->getMessage(), $this);
            return false;
        }
    }

    ////attributes
    public function getOldValueAttribute()
    {
        return $this->task->taskable?->{$this->column_name};
    }

    ////relations
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
