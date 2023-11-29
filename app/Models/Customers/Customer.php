<?php

namespace App\Models\Customers;

use App\Models\Base\Country;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
    ];

    const TYPE_LEAD = 'lead';
    const TYPE_CLIENT = 'client';
    const TYPES = [
        self::TYPE_LEAD,
        self::TYPE_CLIENT,
    ];

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDERS = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
    ];

    const IDTYPE_NATIONAL_ID = 'national_id';
    const IDTYPE_PASSPORT = 'passport';
    const IDTYPE_MILITARY_ID = 'military_id';
    const IDTYPE_DRIVER_LICENSE = 'driver_license';
    const IDTYPES = [
        self::IDTYPE_NATIONAL_ID,
        self::IDTYPE_PASSPORT,
        self::IDTYPE_MILITARY_ID,
        self::IDTYPE_DRIVER_LICENSE
    ];

    const MARITALSTATUS_MARRIED = 'married';
    const MARITALSTATUS_DIVORCES = 'divorced';
    const MARITALSTATUS_SEPARATED = 'separated';
    const MARITALSTATUS_SINGLE = 'single';
    const MARITALSTATUS_UNKNOWN = 'unknown';
    const MARITALSTATUS_WIDOWED = 'widowed';
    const MARITALSTATUSES = [
        self::MARITALSTATUS_MARRIED,
        self::MARITALSTATUS_DIVORCES,
        self::MARITALSTATUS_SEPARATED,
        self::MARITALSTATUS_SINGLE,
        self::MARITALSTATUS_UNKNOWN,
        self::MARITALSTATUS_WIDOWED
    ];

    const SALARY_RANGE_0_TO_10K = '0_to_10';
    const SALARY_RANGE_10K_TO_25K = '10_to_25';
    const SALARY_RANGE_25K_TO_50K = '25_to_50';
    const SALARY_RANGE_50K_TO_100K = '50_to_100';
    const SALARY_RANGE_100K_AND_MORE = '100_and_more';
    const SALARY_RANGES = [
        self::SALARY_RANGE_0_TO_10K,
        self::SALARY_RANGE_10K_TO_25K,
        self::SALARY_RANGE_25K_TO_50K,
        self::SALARY_RANGE_50K_TO_100K,
        self::SALARY_RANGE_100K_AND_MORE
    ];

    const INCOME_SOURCE_SALARY = 'salary';
    const INCOME_SOURCE_BUSINESS = 'business';
    const INCOME_SOURCE_PROPERTIES = 'properties';
    const INCOME_SOURCE_INVESTMENTS = 'invenstments';
    const INCOME_SOURCE_OTHER = 'other';
    const INCOME_SOURCES = [
        self::INCOME_SOURCE_SALARY,
        self::INCOME_SOURCE_BUSINESS,
        self::INCOME_SOURCE_PROPERTIES,
        self::INCOME_SOURCE_INVESTMENTS,
        self::INCOME_SOURCE_OTHER,
    ];

    protected $fillable = [
        'type', 'name', 'arabic_name', 'email', 'gender', 'owner_id',
        'marital_status', 'nationality_id', 'id_type', 'id_number',
        'profession_id', 'salary_range', 'income_source', 'birth_date'
    ];

    ///model functions
    public function addFollowup($title, $call_time, $desc = null): Followup|false
    {
        try {
            $res = $this->followups()->create([
                "creator_id" =>  Auth::id(),
                "title"     =>  $title,
                "call_time" =>  $call_time,
                "desc"      =>  $desc
            ]);
            AppLog::info("Follow-up created", loggable: $res);
            return $res;
        } catch (Exception $e) {
            AppLog::error("Can't create followup", desc: $e->getMessage());
            report($e);
            return false;
        }
    }


    public function setOwner(
        $owner_id
    ): bool {
        $this->update([
            "owner_id"  =>  $owner_id
        ]);

        try {
            $res = $this->save();
            AppLog::info('Customer owner changed', loggable: $this);
            return $res;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Customer owner changing failed', desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function editCustomer(
        $name,
        $arabic_name = null,
        $birth_date = null,
        $email = null,
        $gender = null,
        $marital_status = null,
        $id_type = null,
        $id_number = null,
        $nationality_id = null,
        $profession_id = null,
        $salary_range = null,
        $income_source = null
    ): bool {
        $this->update([
            "name"  =>  $name,
            "arabic_name"   =>  $arabic_name,
            "birth_date"    =>  $birth_date,
            "email" =>  $email,
            "gender"    =>  $gender,
            "marital_status"    =>  $marital_status,
            "id_type"   =>  $id_type,
            "id_number" =>  $id_number,
            "nationality_id"    =>  $nationality_id,
            "profession_id" =>  $profession_id,
            "salary_range"  =>  $salary_range,
            "income_source" =>  $income_source,
        ]);

        try {
            $res = $this->save();
            AppLog::info('Customer info changed', loggable: $this);
            return $res;
        } catch (Exception $e) {
            AppLog::error('Customer info changes failed', desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function setCars(array $cars): bool
    {
        try {
            DB::transaction(function () use ($cars) {
                $this->cars()->delete();
                foreach ($cars as $car) {
                    $this->addCar($car["car_id"], $car["sum_insured"] ?? null, $car["insurance_payment"] ?? null, $car["payment_frequency"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addCar($car_id, $sum_insured = null, $insurance_payment = null, $payment_frequency = null): Car|false
    {
        try {
            $tmp = $this->cars()->create([
                "car_id"      =>  $car_id,
                "sum_insured"  =>  $sum_insured,
                "insurance_payment"    =>  $insurance_payment,
                "payment_frequency"     =>  $payment_frequency
            ]);
            AppLog::info("Adding customer car", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer car failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setAddresses(array $addresses): bool
    {
        try {
            DB::transaction(function () use ($addresses) {
                $this->addresses()->delete();
                foreach ($addresses as $adrs) {
                    $this->addAddress($adrs["type"], $adrs["line_1"], $adrs["line_2"] ?? null, $adrs["country"] ?? null, $adrs["city"] ?? null, $adrs["building"] ?? null, $adrs["flat"] ?? null, $adrs["is_default"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addAddress($type, $line_1, $line_2 = null, $country = null, $city = null, $building = null, $flat = null, $is_default = null): Address|false
    {
        try {
            /** @var Address */
            $tmp = $this->addresses()->create([
                "type"      =>  $type,
                "line_1"    =>  $line_1,
                "line_2"    =>  $line_2,
                "flat"      =>  $flat,
                "building"  =>  $building,
                "city"      =>  $city,
                "country"   =>  $country,
            ]);
            if ($is_default) $tmp->setAsDefault();
            AppLog::info("Adding customer address", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer address failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setPhones(array $phones): bool
    {
        try {
            DB::transaction(function () use ($phones) {
                $this->phones()->delete();
                foreach ($phones as $phone) {
                    $this->addAddress($phone["type"], $phone["number"], $phone["is_default"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addPhone($type, $number, $is_default = null): Phone|false
    {
        try {
            /** @var Phone */
            $tmp = $this->phones()->create([
                "type"      =>  $type,
                "number"    =>  $number
            ]);
            if ($is_default) $tmp->setAsDefault();
            AppLog::info("Adding customer phone", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer phone failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setRelatives(array $relatives)
    {
        try {
            DB::transaction(function () use ($relatives) {
                $this->relatives()->delete();
                foreach ($relatives as $rel) {
                    $this->addRelative($rel["name"], $rel["relation"], $rel["gender"] ?? null, $rel["phone"] ?? null, $rel["birth_date"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addRelative($name, $relation, $gender = null, $phone = null, $birth_date = null): Relative|false
    {
        try {
            $tmp = $this->relatives()->create([
                "name"      =>  $name,
                "relation"  =>  $relation,
                "gender"    =>  $gender,
                "phone"     =>  $phone,
                "birth_date"    =>  $birth_date,
            ]);
            AppLog::info("Adding customer relative", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer relative failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///static functions
    public static function newLead(
        $name,
        $phone,
        $arabic_name = null,
        $birth_date = null,
        $email = null,
        $gender = null,
        $marital_status = null,
        $id_type = null,
        $id_number = null,
        $nationality_id = null,
        $profession_id = null,
        $salary_range = null,
        $income_source = null,
        $owner_id,
    ): self|false {
        $newLead = new self([
            "type"  =>  self::TYPE_LEAD,
            "name"  =>  $name,
            "arabic_name"   =>  $arabic_name,
            "birth_date"    =>  $birth_date,
            "email" =>  $email,
            "gender"    =>  $gender,
            "marital_status"    =>  $marital_status,
            "id_type"   =>  $id_type,
            "id_number" =>  $id_number,
            "nationality_id"    =>  $nationality_id,
            "profession_id" =>  $profession_id,
            "salary_range"  =>  $salary_range,
            "income_source" =>  $income_source,
            "owner_id" =>  $owner_id,
        ]);

        try {
            $newLead->save();
            $newLead->addPhone(Phone::TYPE_HOME, $phone, true);
            AppLog::info('New customer lead created', loggable: $newLead);
            return $newLead;
        } catch (Exception $e) {
            AppLog::error("Can't add customer", $e->getMessage());
            report($e);
            AppLog::error('Unable to create customer lead', desc: $e->getMessage());
            return false;
        }
    }

    public static function newCustomer(
        $owner_id,
        $name,
        $gender,
        $email,
        $arabic_name = null,
        $birth_date = null,
        $marital_status = null,
        $id_type = null,
        $id_number = null,
        $nationality_id = null,
        $profession_id = null,
        $salary_range = null,
        $income_source = null
    ): self|false {
        $newCustomer = new self([
            "type"  =>  self::TYPE_CLIENT,
            "name"  =>  $name,
            "arabic_name"   =>  $arabic_name,
            "birth_date"    =>  $birth_date,
            "email" =>  $email,
            "gender"    =>  $gender,
            "marital_status"    =>  $marital_status,
            "id_type"   =>  $id_type,
            "id_number" =>  $id_number,
            "nationality_id"    =>  $nationality_id,
            "profession_id" =>  $profession_id,
            "salary_range"  =>  $salary_range,
            "income_source" =>  $income_source,
            "owner_id" =>  $owner_id,
        ]);

        try {
            $newCustomer->save();
            AppLog::info('New customer created', loggable: $newCustomer);
            return $newCustomer;
        } catch (Exception $e) {

            report($e);
            AppLog::error('Unable to create customer', desc: $e->getMessage());
            return false;
        }
    }

    ///relations
    public function followups(): MorphMany
    {
        return $this->morphMany(Followup::class, 'called');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(Relative::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }
}
