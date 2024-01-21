<?php

namespace App\Models\Base;

use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaRecord extends Model
{
    use HasFactory;

    protected $table = 'sla_records';
    protected $fillable = [
        'created_by', 'assigned_to_id','assigned_to_team','action_title','due','reply_by','reply_action','reply_date','is_ignore'
    ];

    public static function newSlaRecord(Model $action_item, $action_title, Carbon $due , $assigned_to_id = null, $assigned_to_team = null)
    {
        $newSlaRecord = new self([
            "action_title"  =>  $action_title,
            "due"  =>  $due->format("Y-m-d H:i:s"),
            "assigned_to_id"  =>  $assigned_to_id,
            "assigned_to_team"  =>  $assigned_to_team
        ]);
        try{
            AppLog::info("SLA Record added", loggable: $action_item);
            $newSlaRecord->save();
        } catch (Exception $e)
        {
            report($e);
            AppLog::error("Failed adding SLA Record", desc: $e->getMessage());
            return false;
        }
    }

}
