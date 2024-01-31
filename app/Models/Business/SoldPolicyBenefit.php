<?php

namespace App\Models\Business;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldPolicyBenefit extends Model
{
    use HasFactory;

    protected $table = 'sold_benefits';
    protected $fillable = [
        'benefit', 'value'
    ];

    ///model functions
    public function editInfo($benefit, $value)
    {
        try {
            $this->update([
                "benefit"   =>  $benefit,
                "value"     =>  $value,
            ]);
            AppLog::info("Sold Policy Benefit edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Sold Policy Benefit edit", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function delete()
    {
        try {
            AppLog::info("Sold Policy Benefit deleted", loggable: $this);
            return parent::delete();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Sold Policy Benefit delete", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
}
