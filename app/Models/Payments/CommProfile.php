<?php

namespace App\Models\Payments;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommProfile extends Model
{
    use HasFactory;

    const TYPE_SALES_IN = 'sales_in';
    const TYPE_SALES_OUT = 'sales_out';
    const TYPE_OVERRIDE = 'override';

    const TYPES = [
        self::TYPE_SALES_IN,
        self::TYPE_SALES_OUT,
        self::TYPE_OVERRIDE,
    ];

    protected $fillable = [
        'title', 'type', 'per_policy', 'desc'
    ];

    ///static functions
    


    ///relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
