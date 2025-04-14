<?php

namespace App\Models\Tasks;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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
