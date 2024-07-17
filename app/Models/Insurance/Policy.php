<?php

namespace App\Models\Insurance;

use App\Models\Customers\Car as CustomersCar;
use App\Models\Payments\PolicyCommConf;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Policy extends Model
{
    const MORPH_TYPE = 'policy';

    use HasFactory, SoftDeletes;

    const BUSINESS_PERSONAL_MOTOR = 'personal_motor';
    const BUSINESS_CORPORATE_MOTOR = 'corporate_motor';
    const BUSINESS_PERSONAL_MEDICAL = 'personal_medical';
    const BUSINESS_CORPORATE_MEDICAL = 'corporate_medical';
    const BUSINESS_PERSONAL_LIFE = 'personal_life';
    const BUSINESS_CORPORATE_LIFE = 'corporate_life';
    const BUSINESS_ACCIDENT = 'accident';
    const BUSINESS_HOME = 'home';
    const BUSINESS_BUSINESS = 'business';
    const BUSINESS_PROPERTY = 'property';
    const BUSINESS_CARGO = 'cargo';
    const BUSINESS_INLAND = 'inland';
    const BUSINESS_ENGINEERING = 'engineering';
    const BUSINESS_EXTENDED_WARRANTY = 'extended_warranty';
    const BUSINESS_LIABILITY = 'liability';

    const PERSONAL_TYPES = [
        self::BUSINESS_PERSONAL_MOTOR,
        self::BUSINESS_PERSONAL_MEDICAL,
        self::BUSINESS_PERSONAL_LIFE,
        self::BUSINESS_ACCIDENT,
        self::BUSINESS_HOME,
        self::BUSINESS_BUSINESS,
        self::BUSINESS_PROPERTY,
    ];

    const CORPORATE_TYPES = [
        self::BUSINESS_CORPORATE_MEDICAL,
        self::BUSINESS_CORPORATE_MOTOR,
        self::BUSINESS_CARGO,
        self::BUSINESS_INLAND,
        self::BUSINESS_ENGINEERING,
        self::BUSINESS_LIABILITY,
        self::BUSINESS_EXTENDED_WARRANTY,
        self::BUSINESS_CORPORATE_LIFE,
        self::BUSINESS_PROPERTY,
    ];

    const LINES_OF_BUSINESS = [
        self::BUSINESS_PERSONAL_MOTOR,
        self::BUSINESS_CORPORATE_MOTOR,
        self::BUSINESS_PERSONAL_MEDICAL,
        self::BUSINESS_CORPORATE_MEDICAL,
        self::BUSINESS_ACCIDENT,
        self::BUSINESS_HOME,
        self::BUSINESS_PROPERTY,
        self::BUSINESS_CARGO,
        self::BUSINESS_INLAND,
        self::BUSINESS_ENGINEERING,
        self::BUSINESS_LIABILITY,
        self::BUSINESS_EXTENDED_WARRANTY,
        self::BUSINESS_PERSONAL_LIFE,
        self::BUSINESS_CORPORATE_LIFE,
        self::BUSINESS_BUSINESS,
    ];

    protected $table = 'policies';
    protected $fillable = [
        'company_id',
        'name', //policy as named by the insurance company
        'business', //line of business - enum - motor,cargo..
        'note' //extra note for users - nullable
    ];

    ///static functions
    public static function getAvailablePolicies($type, CustomersCar $car = null, $age = null, $offerValue = null): Collection
    {
        assert(
            in_array($type, [
                self::BUSINESS_PERSONAL_MOTOR,
                self::BUSINESS_CORPORATE_MOTOR,
                self::BUSINESS_PERSONAL_MEDICAL,
                self::BUSINESS_CORPORATE_MEDICAL,
            ]),
            "Can't find options for type outside of motor and medical. Received: $type"
        );
        assert($car || $age, "All parameters are null");

        if ($car) {
            assert(!$age, "Must use only one parameter");
            assert(in_array($type, [self::BUSINESS_PERSONAL_MOTOR, self::BUSINESS_CORPORATE_MOTOR]), "Must use a motor type if a car is supplied");
        }
        if ($age) {
            assert(!$car, "Must use only one parameter");
            assert(in_array($type, [self::BUSINESS_PERSONAL_MEDICAL, self::BUSINESS_CORPORATE_MEDICAL]), "Must use a medical type if age is supplied");
        }

        $policies = self::byType($type)->withCompany()->withConditions()->get();

        $valid_policies = new Collection();
        $net_value = 0;
        $gross_value = 0;
        foreach ($policies as $pol) {
            if ($car) {
                $cond = $pol->getConditionByCarOrValue($car, $offerValue);
                if ($cond) {
                    $net_value = ($cond->rate / 100) * $offerValue;
                    $gross_value = $pol->calculateGrossValue($net_value);
                }
            } else if ($age)
                $cond = $pol->getConditionByAge($age);

            if ($cond) {
                $valid_policies->push([
                    "policy"        => $pol,
                    "cond"          => $cond,
                    "net_value"     => $net_value,
                    "gross_value"   => $gross_value,
                ]);
            }
        }
        return $valid_policies;
    }

    public static function newPolicy($company_id, $name, $business, $note = null)
    {
        //TODO remove comment
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('create', self::class)) return false;

        $newPolicy = new self([
            "company_id" =>  $company_id,
            "name"      =>  $name,
            "business"  =>  $business,
            "note"      =>  $note
        ]);
        try {
            $newPolicy->save();
            AppLog::info('New Policy added', "Policy $name ($newPolicy->id) added successfully");
            return $newPolicy;
        } catch (Exception $e) {
            AppLog::error("Can't add policy", $e->getMessage());
            report($e);
            return false;
        }
    }

    public static function getPolicyByNameAndLineOfBusiness($company_name, $business, $policy_name)
    {
        return self::select("policies.*")
            ->join('insurance_companies', 'insurance_companies.id', '=', 'policies.company_id')
            ->where('business', $business)
            ->where('insurance_companies.name', $company_name)
            ->where('policies.name', $policy_name)
            ->first();
    }

    ///model functions
    public function getConditionByCarOrValue(CustomersCar $customer_car, $value = null)
    {
        // if (!in_array($this->business, [self::BUSINESS_PERSONAL_MOTOR, self::BUSINESS_CORPORATE_MOTOR]))
        //     throw new Exception("Invalid business type. Can't get policy rate by car");

        $this->loadMissing('conditions');
        $customer_car->loadMissing('car');
        foreach ($this->conditions as $cond) {
            switch ($cond->scope) {
                case PolicyCondition::SCOPE_MODEL:
                    if ($customer_car->car->car_model_id == $cond->value)
                        return $cond;
                    break;
                case PolicyCondition::SCOPE_BRAND:
                    $customer_car->car->loadMissing('car_model');
                    if ($customer_car->car->car_model->brand_id == $cond->value)
                        return $cond;
                    break;

                case PolicyCondition::SCOPE_COUNTRY:
                    $customer_car->car->loadMissing('car_model', 'car_model.brand');
                    if ($customer_car->car->car_model->brand->country_id == $cond->value)
                        return $cond;
                    break;

                case PolicyCondition::SCOPE_YEAR:
                    if (!$customer_car) break;
                    switch ($cond->operator) {
                        case PolicyCondition::OP_EQUAL:
                            if ($customer_car->model_year == $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_GREATER:
                            if ($customer_car->model_year > $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_GREATER_OR_EQUAL:
                            if ($customer_car->model_year >= $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_LESS:
                            if ($customer_car->model_year < $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_LESS_OR_EQUAL:
                            if ($customer_car->model_year <= $cond->value)
                                return $cond;
                            break;
                    }

                case PolicyCondition::SCOPE_VALUE:
                    $checkValue = $value ?? $customer_car->price;
                    if (!$checkValue) break;
                    switch ($cond->operator) {
                        case PolicyCondition::OP_EQUAL:
                            if ($checkValue == $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_GREATER:
                            if ($checkValue > $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_GREATER_OR_EQUAL:
                            if ($checkValue >= $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_LESS:
                            if ($checkValue < $cond->value)
                                return $cond;
                            break;

                        case PolicyCondition::OP_LESS_OR_EQUAL:
                            if ($checkValue <= $cond->value)
                                return $cond;
                            break;
                    }
            }
        }
        return null;
    }

    public function getConditionByAge($age)
    {
        if (!in_array($this->business, [self::BUSINESS_PERSONAL_MEDICAL, self::BUSINESS_CORPORATE_MEDICAL]))
            throw new Exception("Invalid business type. Can't get rate by age");
        foreach ($this->conditions as $cond) {
            switch ($cond->scope) {
                case PolicyCondition::SCOPE_AGE:
                    if ($age == $cond->value)
                        return $cond;
            }
        }
        return 0;
    }

    public function calculateGrossValue($net_premium)
    {
        $gross_premium = $net_premium;
        foreach ($this->gross_calculations as $g) {
            switch ($g->calculation_type) {
                case GrossCalculation::TYPE_PERCENTAGE:
                    $gross_premium += (($g->value / 100) * $net_premium);
                    break;

                case GrossCalculation::TYPE_VALUE:
                    $gross_premium += $g->value;
                    break;
            }
        }
        return $gross_premium;
    }

    public function editInfo($name, $business, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        $this->update([
            "name"      =>  $name,
            "business"  =>  $business,
            "note"      =>  $note
        ]);
        try {
            $this->save();
            AppLog::info('Policy update', "Policy $name ($this->id) updated successfully", $this);
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't edit policy", $e->getMessage());
            report($e);
            return false;
        }
    }

    public function addCondition(
        $scope,
        $operator,
        $value,
        $rate,
        $note = null
    ): false|PolicyCondition {
        //TODO remove comment
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('update', $this)) return false;

        try {
            $order = $this->conditions()->count() + 1;
            $condition = $this->conditions()->create([
                "scope" =>  $scope,
                "operator" =>  $operator,
                "value" =>  $value,
                "order" =>  $order,
                "rate" =>  $rate,
                "note" =>  $note,
            ]);
            AppLog::info('Condition Added', "New condition added for $this->name", $this);
            return $condition;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Adding condition failed', $e->getMessage());
            return false;
        }
    }

    public function addBenefit($benefit, $value)
    {
        //TODO remove comment
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('update', $this)) return false;
        try {
            AppLog::info("Adding benefit", loggable: $this);

            return $this->benefits()->updateOrCreate([
                "benefit"   =>  $benefit,
            ], [
                "value"     =>  $value
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding benefit failed", loggable: $this, desc: $e->getMessage());
            return false;
        }
    }

    public function addGrossCalculation($title, $calculation_type, $value)
    {
        //TODO remove comment
        /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('update', $this)) return false;
        try {
            AppLog::info("Adding gross calculation", loggable: $this);

            return $this->gross_calculations()->updateOrCreate([
                "title"   =>  $title,
            ], [
                "calculation_type"  =>  $calculation_type,
                "value"             =>  $value,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding gross calculation failed", loggable: $this, desc: $e->getMessage());
            return false;
        }
    }

    public function addCommConf($title, $calculation_type, $value, $due_penalty = null, $penalty_percent = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
        try {
            AppLog::info("Adding cost configuration", loggable: $this);

            return $this->comm_confs()->updateOrCreate([
                "title"   =>  $title,
            ], [
                "calculation_type"  =>  $calculation_type,
                "value"             =>  $value,
                "due_penalty"       =>  $due_penalty,
                "penalty_percent"   =>  $penalty_percent,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding cost configuration failed", loggable: $this, desc: $e->getMessage());
            return false;
        }
    }


    //scopes
    public function scopeTableData($query)
    {
        $query->select('policies.*');
    }

    /**
     * must use table data first
     **/
    public function scopeSearchBy($query, $text)
    {
        $query->select('policies.*')
            ->join('insurance_companies', 'insurance_companies.id', '=', 'policies.company_id');
        $splittedText = explode(' ', $text);
        foreach ($splittedText as $tmp) {
            $query->where(
                function ($q) use ($tmp) {
                    $q->where('policies.name', 'LIKE', "%$tmp%")
                        ->orWhere('insurance_companies.name', 'LIKE', "%$tmp%");
                }
            );
        }
        return $query;
    }

    public function scopeWithConditions($query)
    {
        $query->with('conditions');
    }

    public function scopeWithCompany($query)
    {
        $query->with('company');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('business', $type);
    }

    ///relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(PolicyBenefit::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(PolicyCondition::class);
    }

    public function gross_calculations(): HasMany
    {
        return $this->hasMany(GrossCalculation::class);
    }

    public function comm_confs(): HasMany
    {
        return $this->hasMany(PolicyCommConf::class);
    }
}
