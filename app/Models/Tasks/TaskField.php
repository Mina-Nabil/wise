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

    const TITLE_SALES_CHECK_LICENSE = 'رخصه السياره';
    const TITLE_SALES_CHECK_DRIVER = 'سواقه';
    const TITLE_SALES_CHECK_POLICE_DOC = 'محضر شرطه';
    const TITLE_SALES_CHECK_BENIF = 'مستفيد';
    const TITLE_SALES_CHECK_PERC = 'شرط النسبه';
    const TITLE_SALES_CHECK_MAND = 'تحمل اجباري';
    const TITLE_SALES_CHECK_AIRBAG = 'تحمل ايرباج و مشتلملات';
    const TITLE_SALES_CHECK_PANORAMA = 'تحمل بانوراما';
    const TITLE_SALES_CHECK_CONS = 'تحمل استهلاكات';
    const TITLE_SALES_CHECK_LIMITS = 'تحمل قيود الاستعمال';
    const TITLE_SALES_CHECK_EXCL = 'الاستثناء';
    const TITLE_SALES_CHECK_FRANCH = 'تحمل توكيل';

    const SALES_CHECKLIST = [
        self::TITLE_SALES_CHECK_LICENSE,
        self::TITLE_SALES_CHECK_DRIVER,
        self::TITLE_SALES_CHECK_POLICE_DOC,
        self::TITLE_SALES_CHECK_BENIF,
        self::TITLE_SALES_CHECK_PERC,
        self::TITLE_SALES_CHECK_MAND,
        self::TITLE_SALES_CHECK_AIRBAG,
        self::TITLE_SALES_CHECK_PANORAMA,
        self::TITLE_SALES_CHECK_CONS,
        self::TITLE_SALES_CHECK_LIMITS,
        self::TITLE_SALES_CHECK_EXCL,
        self::TITLE_SALES_CHECK_FRANCH
    ];

    const TITLE_ACCIDENT_PLACE = 'مكان الحادت';
    const TITLE_ACCIDENT_DESC = 'وصف الحادث';
    const TITLE_ACCIDENT_DATE = 'تاريخ الحادث';
    const TITLE_DAMAGES = 'تلفيات';
    const TITLE_DRIVER = 'قائد السياره';
    const TITLE_WORKSHOP = 'مكان الاصلاح';
    const TITLE_WORKSHOP_ADRS = 'عنوان مكان الاصلاح';
    const TITLE_WORKSHOP_PHONE = 'تليفون مكان الاصلاح';

    const TITLES = [
        self::TITLE_ACCIDENT_PLACE,
        self::TITLE_ACCIDENT_DESC,
        self::TITLE_ACCIDENT_DATE,
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
