<?php

namespace App\Models\Insurance;

use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Base\Country;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PolicyCondition extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'policy_condition';

    const OP_GREATER_OR_EQUAL = 'gte';
    const OP_GREATER = 'gt';
    const OP_LESS_OR_EQUAL = 'lte';
    const OP_LESS = 'lt';
    const OP_EQUAL = 'e';

    const OPERATORS = [
        self::OP_EQUAL, self::OP_GREATER,
        self::OP_GREATER_OR_EQUAL, self::OP_LESS,
        self::OP_LESS_OR_EQUAL
    ];

    const SCOPE_AGE = 'age'; //health - medical scope
    //the following scopes are for cars
    const SCOPE_YEAR = 'year';
    const SCOPE_MODEL = 'car_model';
    const SCOPE_BRAND = 'brand';
    const SCOPE_COUNTRY = 'country';
    //default scope for all
    const SCOPE_VALUE = 'value';

    const SCOPES = [
        self::SCOPE_AGE,
        self::SCOPE_MODEL, self::SCOPE_BRAND,
        self::SCOPE_COUNTRY, self::SCOPE_YEAR,
        self::SCOPE_VALUE
    ];

    protected $table = 'policy_conditions';
    protected $fillable = [
        'scope', //scope of condition, car model, brand, model year - age in case of health
        'operator', //operator defining the conditions - e.g if model year (greater than) 2000
        'value', //condition limit
        'order', //condition order between other conditions - more specific cases should be calculated first
        'rate', //condition result - end value
        'note', // extra note for users
    ];

    //model functions
    public function editInfo($scope, $op, $value, $rate, $note = null)
    {
        try {
            $this->update([
                'scope' => $scope,
                'operator' => $op,
                'value' => $value,
                'rate' => $rate,
                'note' => $note,
            ]);
            AppLog::info('Policy condition update done', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update policy condition", loggable: $this);
            return false;
        }
    }

    public function deleteCondition()
    {
        try {
            $this->delete();
            AppLog::info('Policy condition deleted', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete policy condition", loggable: $this);
            return false;
        }
    }

    public function moveUp()
    {
        $this->loadMissing('policy', 'policy.conditions');
        $sorted_conditions = $this->policy->conditions->sortByDesc('order');
        $swap = false;
        foreach ($sorted_conditions as $cond) {
            if ($swap) {
                $tmpOrder = $cond->order;
                $cond->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($cond) {
                        $cond->save();
                        $this->save();
                    });
                    AppLog::info('Orders adjusted', null, $this->policy);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust order", $e->getMessage(), $this->policy);
                    return false;
                }
            }
            if ($cond->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    public function moveDown()
    {
        $this->loadMissing('policy', 'policy.conditions');
        $sorted_conditions = $this->policy->conditions->sortBy('order');
        $swap = false;
        foreach ($sorted_conditions as $cond) {
            if ($swap) {
                $tmpOrder = $cond->order;
                $cond->order = $this->order;
                $this->order = $tmpOrder;
                try {
                    DB::transaction(function () use ($cond) {
                        $cond->save();
                        $this->save();
                    });
                    AppLog::info('Orders adjusted', null, $this->policy);
                    return true;
                } catch (Exception $e) {
                    report($e);
                    AppLog::error("Can't adjust order", $e->getMessage(), $this->policy);
                    return false;
                }
            }
            if ($cond->id == $this->id) {
                $swap = true;
            }
        }
        return true;
    }

    //static functions
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    //mutators
    public function getValueNameAttribute()
    {
        switch ($this->scope) {
            case self::SCOPE_BRAND:
                $brand = Brand::find($this->value);
                return $brand?->name ?? 'N/A';

            case self::SCOPE_COUNTRY:
                $country = Country::find($this->value);
                return $country?->name ?? 'N/A';

            case self::SCOPE_MODEL:
                $model = CarModel::find($this->value);
                return $model?->name ?? 'N/A';

            default:
                return $this->value;
        }
    }

    //relations
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
