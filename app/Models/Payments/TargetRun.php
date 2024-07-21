<?php

namespace App\Models\Payments;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetRun extends Model
{
    use HasFactory;

    protected $table = 'target_runs';
    protected $fillable = ['added_to_balance', 'added_to_payments'];


    ///relations
    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }
}
