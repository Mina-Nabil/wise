<?php

namespace App\Models\Business;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldPolicyExclusion extends Model
{
    use HasFactory;

    protected $table = 'sold_exclusions';
    protected $fillable = [
        'title', 'value'
    ];
    public $timestamps = false;

    ///model functions
    public function editInfo($title, $value)
    {
        try {
            $this->update([
                "title"   =>  $title,
                "value"     =>  $value,
            ]);
            AppLog::info("Sold Policy exclusion edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Sold Policy Exclusion edit", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function delete()
    {
        try {
            AppLog::info("Sold Policy Exclusion deleted", loggable: $this);
            return parent::delete();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Sold Policy Exclusion delete", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
}
