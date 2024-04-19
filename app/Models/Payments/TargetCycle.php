<?php

namespace App\Models\Payments;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetCycle extends Model
{
    use HasFactory;

    protected $table = 'target_cycles';
    protected $fillable = ['day_of_month', 'each_month'];

    ///model functions
    public function editInfo($day_of_month, $each_month)
    {
        try {
            $this->update([
                "day_of_month"  =>  $day_of_month,
                "each_month"    =>  $each_month,
            ]);
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function delete()
    {
        try {
            return parent::delete();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///relations
    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }
}
