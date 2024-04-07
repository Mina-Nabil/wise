<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    const PERIOD_MONTH = 'month';
    const PERIOD_QUARTER = 'quarter';
    const PERIOD_YEAR = 'year';
    const PERIOD_YEAR_TO_DATE = 'year-to-date';

    const PERIODS = [
        self::PERIOD_MONTH,
        self::PERIOD_QUARTER,
        self::PERIOD_YEAR,
        self::PERIOD_YEAR_TO_DATE
    ];
}
