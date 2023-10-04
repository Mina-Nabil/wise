<?php

namespace App\Models\Insurance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyCondition extends Model
{
    use HasFactory;

    CONST OP_GREATER_OR_EQUAL = 'gte';
    CONST OP_GREATER = 'gt';
    CONST OP_LESS_OR_EQUAL = 'lte';
    CONST OP_LESS = 'lt';
    CONST OP_EQUAL = 'e';

    CONST OPERATORS = [
        self::OP_EQUAL,
        self::OP_GREATER,
        self::OP_GREATER_OR_EQUAL,
        self::OP_LESS,
        self::OP_LESS_OR_EQUAL
    ];

    CONST SCOPE_YEAR = 'year';
    CONST SCOPE_MODEL = 'car_model';
    CONST SCOPE_BRAND = 'brand';
    CONST SCOPE_COUNTRY = 'country';
    CONST SCOPE_AGE = 'age';


    CONST SCOPES = [
        self::SCOPE_AGE,
        self::SCOPE_MODEL,
        self::SCOPE_BRAND,
        self::SCOPE_COUNTRY,
        self::SCOPE_YEAR
    ];

    protected $table = 'policy_conditions';
    protected $fillable = [
        'scope', //scope of condition, car model, brand, model year - age in case of health
        'operator', //operator defining the conditions - e.g if model year (greater than) 2000
        'value', //condition limit
        'order', //condition order between other conditions - more specific cases should be calculated first 
        'rate', //condition result - end value
        'note' // extra note for users
    ];


    //relations
    public function policy() : BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
