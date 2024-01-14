<?php

namespace App\Models\Customers;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    use HasFactory;

    protected $table = 'customer_status';
    protected $fillable = [
        'status', 'reason', 'note'
    ];

    const STATUS_NEW = 'new';
    const STATUS_QUALIFIED = 'qualified';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CLIENT = 'client';
    const STATUS_INACTIVE = 'in_active';

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_QUALIFIED,
        self::STATUS_REJECTED,
        self::STATUS_CLIENT,
        self::STATUS_INACTIVE,
    ];

    const REASONS = []; //lsa madaneesh el reasons


    //relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
