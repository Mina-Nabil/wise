<?php

namespace App\Models\Customers;

use App\Models\Base\Country;
use App\Models\Tasks\Task;
use App\Models\Offers\Offer;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory;
    const FILES_DIRECTORY = 'customers/docs/';

    protected $casts = [
        'birth_date' => 'date',
    ];

    const MORPH_TYPE = 'customer';

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
        'type', 'first_name', 'middle_name', 'last_name',
        'arabic_first_name', 'arabic_middle_name', 'arabic_last_name',
        'email', 'gender', 'owner_id', 'marital_status',
        'nationality_id', 'id_type', 'id_number',
        'profession_id', 'salary_range', 'income_source', 'birth_date',
        'id_doc', 'driver_license_doc', 'note'
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
        $first_name,
        $last_name,
        $middle_name = null,
        $arabic_first_name = null,
        $arabic_middle_name = null,
        $arabic_last_name = null,
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
        $id_doc = null,
        $driver_license_doc = null,
    ): bool {
        $this->update([
            "first_name"  =>  $first_name,
            "last_name"  =>  $last_name,
            "middle_name"  =>  $middle_name,
            "arabic_first_name"  =>  $arabic_first_name,
            "arabic_middle_name"   =>  $arabic_middle_name,
            "arabic_last_name"   =>  $arabic_last_name,
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
            "id_doc" =>  $id_doc,
            "driver_license_doc" =>  $driver_license_doc,
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
                    $this->addCar(
                        $car["car_id"],
                        $car["model_year"],
                        $car["sum_insured"] ?? null,
                        $car["insurance_payment"] ?? null,
                        $car["payment_frequency"] ?? null,
                        $car["insurance_company_id"] ?? null,
                        $car["renewal_date"] ?? null,
                        $car["wise_insured"] ?? false
                    );
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addCar($car_id, $model_year, $sum_insured = null, $insurance_payment = null, $payment_frequency = null, $insurance_company_id = null, Carbon $renewal_date = null, $wise_insured = false): Car|false
    {
        try {
            $tmp = $this->cars()->updateOrCreate(
                [
                    "car_id"      =>  $car_id,
                ],
                [
                    "sum_insured"  =>  $sum_insured,
                    "insurance_payment"    =>  $insurance_payment,
                    "payment_frequency"     =>  $payment_frequency,
                    "insurance_company_id"     =>  $insurance_company_id,
                    "model_year"     =>  $model_year,
                    "renewal_date"     =>  $renewal_date ? $renewal_date->format('Y-m-d H:i:s') : null,
                    'wise_insured' =>   $wise_insured
                ]
            );
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
                    $this->addAddress($adrs["type"], $adrs["line_1"], $adrs["line_2"] ?? null, $adrs["country"] ?? null, $adrs["city"] ?? null, $adrs["area"] ?? null, $adrs["building"] ?? null, $adrs["flat"] ?? null, $adrs["is_default"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addAddress($type, $line_1, $line_2 = null, $country = null, $city = null, $area = null, $building = null, $flat = null, $is_default = null): Address|false
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
                "area"      =>  $area,
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

    public function setInterests(array $interests): bool
    {
        try {
            DB::transaction(function () use ($interests) {
                $this->interests()->delete();
                foreach ($interests as $intr) {
                    $this->addInterest($intr["business"], $intr["interested"], $adrs["note"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addInterest($business, bool $interested, $note = null): Interest|false
    {
        try {
            /** @var Interest */
            $tmp = $this->interests()->updateOrCreate(
                [
                    "business"      =>  $business,
                ],
                [
                    "interested"    =>  $interested,
                    "note"    =>  $note
                ]
            );
            AppLog::info("Adding customer interest", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer interest failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setPhones(array $phones): bool
    {
        try {
            DB::transaction(function () use ($phones) {
                $this->phones()->delete();
                foreach ($phones as $phone) {
                    $this->addPhone($phone["type"], $phone["number"], $phone["is_default"] ?? null);
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
                    $this->addRelative($rel["name"], $rel["relation"] ?? null, $rel["gender"] ?? null, $rel["phone"] ?? null, $rel["birth_date"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addRelative($name, $relation = null, $gender = null, $phone = null, $birth_date = null): Relative|false
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

    /** @var array $customer_relatives .. each entry should consist of entries like this ['id' => 'relation'] */
    public function setCustomerRelatives(array $customer_relatives)
    {
        try {
            DB::transaction(function () use ($customer_relatives) {
                $this->customer_relatives()->sync($customer_relatives);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addCustomerRelative($id, $relation = null)
    {
        try {
            $tmp = $this->customer_relatives()->attach($id, ["relation" =>  $relation]);
            AppLog::info("Adding customer to customer relative", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding customer to customer relative failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setBankAccounts(array $accounts)
    {
        try {
            DB::transaction(function () use ($accounts) {
                $this->bank_accounts()->delete();
                foreach ($accounts as $acc) {
                    $this->addBankAccount($acc["type"], $acc['bank_name'], $acc['account_number'], $acc['owner_name'], $acc['evidence_doc'] ?? null, $acc['iban'] ?? null, $acc['bank_branch'] ?? null, $acc['is_default'] ?? false);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addBankAccount($type, $bank_name, $account_number, $owner_name, $evidence_doc = null, $iban = null, $bank_branch = null, $is_default = false): BankAccount|false
    {
        try {
            /** @var BankAccount */
            $tmp = $this->bank_accounts()->create([
                "type"              =>  $type,
                "bank_name"         =>  $bank_name,
                "account_number"    =>  $account_number,
                "owner_name"        =>  $owner_name,
                "evidence_doc"      =>  $evidence_doc,
                "iban"              =>  $iban,
                "bank_branch"       =>  $bank_branch,
            ]);
            if ($is_default) $tmp->setAsDefault();
            AppLog::info("Adding bank account", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding bank account failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setStatus($status, $reason, $note = null): status|false
    {
        try {
            AppLog::info("Updating customer status", loggable: $this);
            return $this->status()->updateOrCreate([], [
                "user_id"      =>  Auth::id(),
                "status"    =>  $status,
                "reason"    =>  $reason,
                "note"    =>  $note,
            ]);
        } catch (Exception $e) {
            AppLog::error("Updating customer note", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    public function setCustomerNote($note): bool
    {
        try {
            AppLog::info("Updating customer note", loggable: $this);
            return $this->update([
                "note"    =>  $note,
            ]);
        } catch (Exception $e) {
            AppLog::error("Updating customer note", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    public function setDocInfo($full_name, $national_id, $address, $tel1, $tel2, $car, $model_year)
    {
        try {
            $name_array = explode(" ", $full_name);
            $middle_name = "";
            for ($j = 1; $j < count($name_array) - 1; $j++) $middle_name .= "$name_array[$j] ";

            $this->first_name = $name_array[0];
            $this->last_name = $name_array[count($name_array) - 1];
            $this->middle_name = trim($middle_name);
            $this->id_type = self::IDTYPE_NATIONAL_ID;
            $this->id_number = $national_id;
            if ($address)
                $this->addAddress(Address::TYPE_HOME, $address, country: "Egypt");
            if ($tel1) $this->addPhone(Phone::TYPE_MOBILE, $tel1, true);
            if ($tel2) $this->addPhone(Phone::TYPE_HOME, $tel2, false);
            $tmpCar = NULL;
            if ($car) {
                $this->setCars([[
                    "car_id"        => $car->id,
                    "model_year"    => $model_year
                ]]);
                $tmpCar = $this->cars()->where("car_id", $car->id)->where("model_year", $model_year)->first();
            }

            $this->save();
            return ($tmpCar) ? $tmpCar->id : null;
        } catch (Exception $e) {
            report($e);
        }
    }

    ///static functions
    public static function newLead(
        $first_name,
        $last_name,
        $phone,
        $middle_name = null,
        $arabic_first_name = null,
        $arabic_middle_name = null,
        $arabic_last_name = null,
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
        $owner_id = null,
        $id_doc = null,
        $driver_license_doc = null,
        $note = null,
    ): self|false {
        $newLead = new self([
            "type"          =>  self::TYPE_LEAD,
            "first_name"    =>  $first_name,
            "last_name"     =>  $last_name,
            "middle_name"   =>  $middle_name,
            "arabic_first_name"     =>  $arabic_first_name,
            "arabic_middle_name"    =>  $arabic_middle_name,
            "arabic_last_name"      =>  $arabic_last_name,
            "birth_date"    =>  $birth_date,
            "email"         =>  $email,
            "gender"        =>  $gender,
            "marital_status"    =>  $marital_status,
            "id_type"           =>  $id_type,
            "id_number"     =>  $id_number,
            "nationality_id"    =>  $nationality_id,
            "profession_id" =>  $profession_id,
            "salary_range"  =>  $salary_range,
            "income_source" =>  $income_source,
            "owner_id"      =>  $owner_id ?? Auth::id(),
            "id_doc"        =>  $id_doc,
            "driver_license_doc" =>  $driver_license_doc,
            "note"          =>  $note,
            "creator_id"    => Auth::id() ?? 10
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
        $first_name,
        $last_name,
        $gender,
        $email,
        $middle_name = null,
        $arabic_first_name = null,
        $arabic_middle_name = null,
        $arabic_last_name = null,
        $birth_date = null,
        $marital_status = null,
        $id_type = null,
        $id_number = null,
        $nationality_id = null,
        $profession_id = null,
        $salary_range = null,
        $income_source = null,
        $id_doc = null,
        $driver_license_doc = null,
        $note = null
    ): self|false {
        $newCustomer = new self([
            "type"          =>  self::TYPE_CLIENT,
            "first_name"    =>  $first_name,
            "last_name"     =>  $last_name,
            "middle_name"   =>  $middle_name,
            "arabic_first_name"     =>  $arabic_first_name,
            "arabic_middle_name"    =>  $arabic_middle_name,
            "arabic_last_name"      =>  $arabic_last_name,
            "birth_date"    =>  $birth_date,
            "email"         =>  $email,
            "gender"        =>  $gender,
            "marital_status"        =>  $marital_status,
            "id_type"       =>  $id_type,
            "id_number"     =>  $id_number,
            "nationality_id"        =>  $nationality_id,
            "profession_id" =>  $profession_id,
            "salary_range"  =>  $salary_range,
            "income_source" =>  $income_source,
            "owner_id"      =>  $owner_id ?? Auth::id(),
            "id_doc"        =>  $id_doc,
            "note"          =>  $note,
            "driver_license_doc"    =>  $driver_license_doc,
            "creator_id"    => Auth::id()
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

    ///attributes
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    ///scopes
    public function scopeUserData($query, $searchText = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('customers.*')->with('status')
            ->join('users', 'customers.owner_id', '=', 'users.id');

        if (!($loggedInUser->is_admin
            || ($loggedInUser->is_operations && $searchText))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) use ($loggedInUser) {
            $q->leftjoin('customer_phones', 'customer_phones.customer_id', '=', 'customers.id')
                ->groupBy('customers.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp, $loggedInUser) {
                    if ($loggedInUser->is_operations) {
                        $qq->where('customers.email', '=', "$tmp")
                            ->orwhere('customers.first_name', '=', "$tmp")
                            ->orwhere('customers.last_name', '=', "$tmp")
                            ->orwhere('customer_phones.number', '=', "$tmp");
                    } else {
                        $qq->where('customers.first_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.middle_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_first_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_last_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_middle_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.email', 'LIKE', "%$tmp%")
                            ->orwhere('customer_phones.number', 'LIKE', "%$tmp%");
                    }
                });
            }
        });
        return $query->latest();
    }

    ///relations
    public function status(): HasOne
    {
        return $this->hasOne(Status::class);
    }

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

    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(Relative::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    public function customer_relatives(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'cust_cust_relatives', 'customer_id', 'relative_id')->withPivot('relation');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function offers(): MorphMany
    {
        return $this->morphMany(Offer::class, 'client');
    }

    public function bank_accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }
}
