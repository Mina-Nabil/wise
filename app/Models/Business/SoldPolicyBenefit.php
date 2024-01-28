<?php

namespace App\Models\Business;

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


    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
}
