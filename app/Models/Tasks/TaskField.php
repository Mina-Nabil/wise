<?php

namespace App\Models\Tasks;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskField extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'task_field';

    protected $table = 'task_fields';
    protected $fillable = [
        "title", "value"
    ];

    const TITLE_ACCIDENT_PLACE = 'مكان الحادت';
    const TITLE_ACCIDENT_DESC = 'وصف الحادث';
    const TITLE_DAMAGES = 'تلفيات';
    const TITLE_DRIVER = 'قائد السياره';
    const TITLE_WORKSHOP = 'مكان الاصلاح';
    const TITLE_WORKSHOP_ADRS = 'عنوان مكان الاصلاح';
    const TITLE_WORKSHOP_PHONE = 'تليفون مكان الاصلاح';

    const TITLES = [
        self::TITLE_ACCIDENT_PLACE,
        self::TITLE_ACCIDENT_DESC,
        self::TITLE_DAMAGES,
        self::TITLE_DRIVER,
        self::TITLE_WORKSHOP,
        self::TITLE_WORKSHOP_ADRS,
        self::TITLE_WORKSHOP_PHONE
    ];

    public function editInfo($title, $value)
    {
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if ($loggedInUser->id != $this->user_id) return false;

        try {
            if ($this->update([
                "title"  =>  $title,
                "value"  =>  $value
            ])) {
                AppLog::info("Task field edited", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLog::error("Can't edit Comment", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    //relations
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
