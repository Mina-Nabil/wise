<?php

namespace App\Models\Base;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class SlaRecord extends Model
{
    use HasFactory;

    protected $table = 'sla_records';
    protected $fillable = [
        'created_by', 'assigned_to_id', 'assigned_to_team', 'action_title', 'due', 'reply_by', 'reply_action', 'reply_date', 'is_ignore'
    ];

    ///model functions

    //////only add this function to a button in the front end
    public function ignoreRecord()
    {
        try {
            $this->update([
                "is_ignore"  =>  true
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("SLA Reply failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function recordReply($action)
    {
        try {
            $this->update([
                "reply_action"  =>  $action,
                "reply_date"  =>  Carbon::now()->format("Y-m-d H:i:s"),
                "reply_by"  =>  Auth::id(),
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("SLA Reply failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }


    ///static function
    public static function newSlaRecord(Model $action_item, $action_title, Carbon $due, $assigned_to_id = null, $assigned_to_team = null)
    {
        $newSlaRecord = new self([
            "action_title"  =>  $action_title,
            "due"  =>  $due->format("Y-m-d H:i:s"),
            "assigned_to_id"  =>  $assigned_to_id,
            "assigned_to_team"  =>  $assigned_to_team
        ]);
        try {
            AppLog::info("SLA Record added", loggable: $action_item);
            $newSlaRecord->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Failed adding SLA Record", desc: $e->getMessage());
            return false;
        }
    }


    ////scopes
    public function scopePageData($query, Carbon $from, Carbon $to, $showIgnored = false, $user_id = null, $team_type = null)
    {
        return $query->with('created_by', 'assigned_to', 'reply_by')->whereBetween('created_at', [
            $from->format('Y-m-d H:i:s'),
            $to->format('Y-m-d H:i:s')
        ])->when($user_id, fn ($q) => $q->where('assigned_to_id', $user_id))
            ->when($team_type == false, fn ($q) => $q->where('assigned_to_team', $team_type))
            ->when($showIgnored == false, fn ($q) => $q->where('is_ignore', false));
    }

    ////relations
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }
    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(User::class, "assigned_to_id");
    }
    public function reply_by(): BelongsTo
    {
        return $this->belongsTo(User::class, "reply_by");
    }
}
