<?php

namespace App\Models\Insurance;

use App\Models\Cars\Car;
use App\Models\Customers\Car as CustomersCar;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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

    const OPTIONS_TYPES = [
        self::BUSINESS_PERSONAL_MOTOR,
        self::BUSINESS_CORPORATE_MOTOR,
        self::BUSINESS_PERSONAL_MEDICAL,
        self::BUSINESS_CORPORATE_MEDICAL,
    ];

    const PERSONAL_TYPES = [
        self::BUSINESS_PERSONAL_MOTOR,
        self::BUSINESS_PERSONAL_MEDICAL,
        self::BUSINESS_ACCIDENT,
        self::BUSINESS_HOME,
        self::BUSINESS_BUSINESS,
        self::BUSINESS_PERSONAL_MEDICAL,
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
    public static function getAvailablePolicies($type, CustomersCar $car = null, $age = null): Collection
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
        foreach ($policies as $pol) {
            if ($car)
                $rate = $pol->getRateByCar($car);
            else if ($age)
                $rate = $pol->getRateByAge($age);

            if ($rate) {
                $valid_policies->push(["policy" => $pol, "rate"  => $rate]);
            }
        }
        return $valid_policies;
    }

    public static function newPolicy($company_id, $name, $business, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (
            !($loggedInUser == null && App::isLocal()) && //local seeder code - can remove later
            !$loggedInUser->can('create', self::class)
        ) return false;

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

    ///model functions
    public function getRateByCar(CustomersCar $customer_car)
    {
        if (!in_array($this->business, [self::BUSINESS_PERSONAL_MOTOR, self::BUSINESS_CORPORATE_MOTOR]))
            throw new Exception("Invalid business type. Can't get policy rate by car");

        $this->loadMissing('conditions');
        $customer_car->loadMissing('car');
        foreach ($this->conditions as $cond) {
            switch ($cond->scope) {
                case PolicyCondition::SCOPE_MODEL:
                    if ($customer_car->car->car_model_id == $cond->value)
                        return $cond->rate;

                case PolicyCondition::SCOPE_BRAND:
                    $customer_car->car->loadMissing('car_model');
                    if ($customer_car->car->car_model->brand_id == $cond->value)
                        return $cond->rate;

                case PolicyCondition::SCOPE_COUNTRY:
                    $customer_car->car->loadMissing('car_model', 'car_model.brand');
                    if ($customer_car->car->car_model->brand->country_id == $cond->value)
                        return $cond->rate;

                case PolicyCondition::SCOPE_YEAR:
                    switch ($cond->operator) {
                        case PolicyCondition::OP_EQUAL:
                            if ($customer_car->model_year == $cond->value)
                                return $cond->rate;

                        case PolicyCondition::OP_GREATER:
                            if ($customer_car->model_year > $cond->value)
                                return $cond->rate;

                        case PolicyCondition::OP_GREATER_OR_EQUAL:
                            if ($customer_car->model_year >= $cond->value)
                                return $cond->rate;

                        case PolicyCondition::OP_LESS:
                            if ($customer_car->model_year < $cond->value)
                                return $cond->rate;

                        case PolicyCondition::OP_LESS_OR_EQUAL:
                            if ($customer_car->model_year <= $cond->value)
                                return $cond->rate;
                    }
            }
        }
        return 0;
    }

    public function getRateByAge($age)
    {
        if (!in_array($this->business, [self::BUSINESS_PERSONAL_MEDICAL, self::BUSINESS_CORPORATE_MEDICAL]))
            throw new Exception("Invalid business type. Can't get rate by age");
        foreach ($this->conditions as $cond) {
            switch ($cond->scope) {
                case PolicyCondition::SCOPE_AGE:
                    if ($age == $cond->value)
                        return $cond->rate;
            }
        }
        return 0;
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
        $note
    ): false|PolicyCondition {
        /** @var User */
        $loggedInUser = Auth::user();
        if (
            !($loggedInUser == null && App::isLocal()) && //local seeder code - can remove later
            !$loggedInUser->can('update', $this)
        ) return false;

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

    //scopes
    public function scopeTableData($query)
    {
        $query->join('insurance_companies', 'insurance_companies.id', '=', 'policies.company_id')
            ->select('policies.*');
    }

    /**
     * must use table data first
     **/
    public function scopeSearchBy($query, $text)
    {
        return $query->where(function ($q) use ($text) {
            $q->where('policies.name', 'LIKE', "%$text%")
                ->orWhere('insurance_companies.name', 'LIKE', "%$text%");
        });
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

    public function conditions(): HasMany
    {
        return $this->hasMany(PolicyCondition::class);
    }
}
