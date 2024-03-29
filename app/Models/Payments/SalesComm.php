<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesComm extends Model
{
    use HasFactory;
    const PYMT_STATE_NEW    = 'new';
    const PYMT_STATE_PAID   = 'paid';
    const PYMT_STATE_CANCELLED    = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];
}
