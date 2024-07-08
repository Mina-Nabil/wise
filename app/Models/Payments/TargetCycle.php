<?php

namespace App\Models\Payments;

use Carbon\Carbon;
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

    ///scopes
    public function getIsDueTodayAttribute()
    {
        // 3 => 4 , 7 , 10 , 1 
        // 6 => 7 , 1 
        // 4 => 5 , 9 , 1 

        $now = Carbon::now();
        return !(($now->subMonth()->month()) % $this->each_month) && $now->day == $this->day_of_month;
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
