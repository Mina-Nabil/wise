<?php

namespace App\Models\Business;

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


    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
}
