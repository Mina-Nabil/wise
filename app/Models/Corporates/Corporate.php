<?php

namespace App\Models\Corporates;

use App\Models\Base\Country;
use App\Models\Customers\Followup;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Corporate extends Model
{
    use HasFactory;

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
        'type', 'name', 'arabic_name', 'email', 'gender',
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

    public function editInfo(
        $name,
        $arabic_name = null,
        $email = null,
        $commercial_record = null,
        $commercial_record_doc = null,
        $tax_id = null,
        $tax_id_doc = null,
        $kyc = null,
        $kyc_doc = null,
        $contract_doc = null,
        $main_bank_evidence = null
    ): bool {
        $this->update([
            "name"          =>  $name,
            "arabic_name"   =>  $arabic_name,
            "email"         =>  $email,
            "commercial_record"     =>  $commercial_record,
            "commercial_record_doc" =>  $commercial_record_doc,
            "tax_id"        =>  $tax_id,
            "tax_id_doc"    =>  $tax_id_doc,
            "kyc"           =>  $kyc,
            "kyc_doc"       =>  $kyc_doc,
            "contract_doc"  =>  $contract_doc,
            "main_bank_evidence"    =>  $main_bank_evidence
        ]);

        try {
            $res = $this->save();
            AppLog::info('Corporate edited', loggable: $this);
            return $res;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Corporate editing failed', desc: $e->getMessage(), loggable: $this);
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
            AppLog::info('Corporate owner changed', loggable: $this);
            return $res;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Corporate owner changing failed', desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setAddresses(array $addresses): bool
    {
        try {
            DB::transaction(function () use ($addresses) {
                $this->addresses()->delete();
                foreach ($addresses as $adrs) {
                    $this->addAddress($adrs["type"], $adrs["line_1"], $adrs["line_2"] ?? null, $adrs["country"] ?? null, $adrs["city"] ?? null, $adrs["building"] ?? null, $adrs["flat"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addAddress($type, $line_1, $line_2 = null, $country = null, $city = null, $building = null, $flat = null): Address|false
    {
        try {
            $tmp = $this->addresses()->create([
                "type"      =>  $type,
                "line_1"    =>  $line_1,
                "line_2"    =>  $line_2,
                "flat"      =>  $flat,
                "building"  =>  $building,
                "city"      =>  $city,
                "country"   =>  $country,
            ]);
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
                    $this->addAddress($phone["type"], $phone["number"], $adrs["is_default"] ?? null);
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
            $tmp = $this->addresses()->create([
                "type"      =>  $type,
                "number"    =>  $number,
                "is_default"    =>  $is_default
            ]);
            AppLog::info("Adding corporate phone", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding corporate phone failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }


    public function setContacts(array $relatives)
    {
        try {
            DB::transaction(function () use ($relatives) {
                $this->contacts()->delete();
                foreach ($relatives as $rel) {
                    $this->addContact($rel["name"], $rel["job_title"], $rel["email"] ?? null, $rel["phone"] ?? null, $rel["is_default"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addContact($name, $job_title = null, $email = null, $phone = null, $is_default = false): Relative|false
    {
        try {
            /** @var Contact */
            $tmp = $this->contacts()->create([
                "name"      =>  $name,
                "job_title" =>  $job_title,
                "email"     =>  $email,
                "phone"     =>  $phone,
            ]);
            if ($is_default) $tmp->setAsDefault();
            AppLog::info("Adding corporate contact", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding corporate contact failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///static functions
    public static function newLead(
        $name,
        $arabic_name = null,
        $email = null,
        $commercial_record = null,
        $commercial_record_doc = null,
        $tax_id = null,
        $tax_id_doc = null,
        $kyc = null,
        $kyc_doc = null,
        $contract_doc = null,
        $main_bank_evidence = null,
        $owner_id = null
    ): self|false {
        $newLead = new self([
            "type"          =>  self::TYPE_LEAD,
            "name"          =>  $name,
            "arabic_name"   =>  $arabic_name,
            "email"         =>  $email,
            "commercial_record"     =>  $commercial_record,
            "commercial_record_doc" =>  $commercial_record_doc,
            "tax_id"        =>  $tax_id,
            "tax_id_doc"    =>  $tax_id_doc,
            "kyc"           =>  $kyc,
            "kyc_doc"       =>  $kyc_doc,
            "contract_doc"  =>  $contract_doc,
            "main_bank_evidence"    =>  $main_bank_evidence,
            "owner_id"  =>  $owner_id
        ]);
        try {
            $newLead->save();
            AppLog::info('New corporate lead created', loggable: $newLead);
            return $newLead;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t create new corporate lead', desc: $e->getMessage());
            return false;
        }
    }

    public static function newCorporate(
        $owner_id,
        $name,
        $arabic_name = null,
        $email = null,
        $commercial_record = null,
        $commercial_record_doc = null,
        $tax_id = null,
        $tax_id_doc = null,
        $kyc = null,
        $kyc_doc = null,
        $contract_doc = null,
        $main_bank_evidence = null,
    ): self|false {
        $newCorporate = new self([
            "type"          =>  self::TYPE_LEAD,
            "name"          =>  $name,
            "arabic_name"   =>  $arabic_name,
            "email"         =>  $email,
            "commercial_record"     =>  $commercial_record,
            "commercial_record_doc" =>  $commercial_record_doc,
            "tax_id"        =>  $tax_id,
            "tax_id_doc"    =>  $tax_id_doc,
            "kyc"           =>  $kyc,
            "kyc_doc"       =>  $kyc_doc,
            "contract_doc"  =>  $contract_doc,
            "main_bank_evidence"    =>  $main_bank_evidence,
            "owner_id"  =>  $owner_id
        ]);

        try {
            $newCorporate->save();
            AppLog::info('New corporate created', loggable: $newCorporate);
            return $newCorporate;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t create new corporate', desc: $e->getMessage());
            return false;
        }
    }

    ///relations
    public function followups(): MorphMany
    {
        return $this->morphMany(Followup::class, 'called');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
