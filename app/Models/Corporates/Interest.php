<?php

namespace App\Models\Corporates;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Interest extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'corporate_interests';

    protected $table = 'corporate_interests';

    protected $fillable = [
        "business",
        "interested",
        "note"
    ];


    ///model functions
    public function editInterest($business, bool $interested, $note = null)
    {
        try {
            $this->update([
                "business"      =>  $business,
                "interested"    =>  $interested,
                "note"    =>  $note
            ]);
            AppLog::info("Editing corporate interest", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing corporate interest failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function delete()
    {
        $corporate = $this->corporate;
        try {
            if (parent::delete()) {
                AppLog::info("Corporate interest deleted", loggable: $corporate);
            } else {
                AppLog::info("Corporate interest deletetion failed", loggable: $corporate);
            }
            return true;
        } catch (Exception $e) {
            AppLog::info("Corporate interest deletetion failed", loggable: $corporate);
            report($e);
            return false;
        }
    }

    ///relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}
