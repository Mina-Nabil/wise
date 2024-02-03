<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrossCalculation extends Model
{
    use HasFactory;

    const TYPE_PERCENTAGE = '%';
    const TYPE_VALUE = '=';
    const TYPES = [
        self::TYPE_PERCENTAGE,
        self::TYPE_VALUE
    ];

    protected $fillable = ['title', 'calculation_type', 'value'];
    public $timestamps = false;

    ///model functions
    public function editInfo($title, $calculation_type, $value)
    {
        try {
            AppLog::info("Editing gross calculation", loggable: $this);
            return $this->update([
                "title"             =>  $title,
                "calculation_type"  =>  $calculation_type,
                "value"             =>  $value,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing gross calculation failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function delete()
    {
        if (parent::delete()) {
            AppLog::info("Gross calculation deleted");
            return true;
        } else {
            AppLog::error("Gross calculation deletetion failed");
        }
    }

    ///relations
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
