<?php

namespace App\Models\Corporates;

use App\Models\Customers\Followup;
use App\Models\Tasks\Task;
use App\Models\Offers\Offer;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Corporate extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'corporate';

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
        'type', 'name', 'arabic_name', 'email', 'commercial_record',
        'commercial_record_doc', 'tax_id', 'tax_id_doc', 'kyc',
        'kyc_doc', 'contract_doc', 'main_bank_evidence', 'creator_id',
        'owner_id', 'note', 'is_welcomed', 'welcome_note'
    ];

    ///model functions
    public function addFollowup($title, $call_time, $desc = null, $is_meeting = false, $line_of_business = null): Followup|false
    {
        try {
            $res = $this->followups()->create([
                "creator_id" =>  Auth::id(),
                "title"     =>  $title,
                "call_time" =>  $call_time,
                "is_meeting"        =>  $is_meeting,
                "line_of_business"  =>  $line_of_business,
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
        $main_bank_evidence = null,
        $note = null,
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
            "note"          =>  $note,
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
                    $this->addAddress($adrs["type"], $adrs["line_1"], $adrs["line_2"] ?? null, $adrs["country"] ?? null, $adrs["city"] ?? null, $adrs["area"] ?? null, $adrs["building"] ?? null, $adrs["flat"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addAddress($type, $line_1, $line_2 = null, $country = null, $city = null, $area = null, $building = null, $flat = null, $is_default = false): Address|false
    {
        try {
            /** @var Address */
            $tmp = $this->addresses()->create([
                "type"      =>  $type,
                "line_1"    =>  $line_1,
                "line_2"    =>  $line_2,
                "flat"      =>  $flat,
                "building"  =>  $building,
                "area"      =>  $area,
                "city"      =>  $city,
                "country"   =>  $country,
                "is_default"   =>  false
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
                    $this->addPhone($phone["type"], $phone["number"], $phone["is_default"] ?? false);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addPhone($type, $number, $is_default = false): Phone|false
    {
        try {
            /** @var Phone */
            $tmp = $this->phones()->create([
                "type"      =>  $type,
                "number"    =>  $number,
                "is_default"    =>  $is_default
            ]);
            if ($is_default) $tmp->setAsDefault();
            AppLog::info("Adding corporate phone", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding corporate phone failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setContacts(array $contacts)
    {
        try {
            DB::transaction(function () use ($contacts) {
                $this->contacts()->delete();
                foreach ($contacts as $con) {
                    $this->addContact($con["name"], $con["job_title"], $con["email"] ?? null, $con["phone"] ?? null, $con["is_default"] ?? null);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addContact($name, $job_title = null, $email = null, $phone = null, $is_default = false): Contact|false
    {
        try {
            /** @var Contact */
            $tmp = $this->contacts()->create([
                "name"      =>  $name,
                "job_title" =>  $job_title,
                "email"     =>  $email,
                "phone"     =>  $phone,
                "is_default"     =>  false,
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
            return $this->status()->updateOrCreate([], [
                "status"    =>  $status,
                "reason"    =>  $reason,
                "note"    =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setIsWelcomed(bool $status, string $note = null): bool
    {
        try {
            $this->is_welcomed = $status;
            $this->welcome_note = $note;
            $this->save();
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setCorporateNote($note): bool
    {
        try {
            AppLog::info("Updating corporate note", loggable: $this);
            return $this->update([
                "note"    =>  $note,
            ]);
        } catch (Exception $e) {
            AppLog::error("Updating corporate note", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    public function setDocInfo($full_name, $national_id, $address, $tel1, $tel2, $car=NULL, $model_year=NULL, $insured_value=NULL){
        try{
            $this->name = $full_name;
            $this->commercial_record = $national_id;
            if($address)
            $this->addAddress(Address::TYPE_HQ, $address, country: "Egypt");
            if ($tel1) $this->addPhone(Phone::TYPE_WORK, $tel1, true);
            if ($tel2) $this->addPhone(Phone::TYPE_WORK, $tel2, false);
            $this->save();
        } catch (Exception $e){
            report($e);
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
            AppLog::info("Adding corporate interest", loggable: $this);
            return $tmp;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding corporate interest failed", desc: $e->getMessage(), loggable: $this);
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
        $owner_id = null,
        $note = null
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
            "owner_id"      =>  $owner_id ?? Auth::id(),
            "creator_id"    => Auth::id(),
            "note"          =>  $note
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
        $note = null
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
            "owner_id"      =>  $owner_id ?? Auth::id(),
            "creator_id"    => Auth::id(),
            "note"          =>  $note
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

    ///attributes
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    ///scopes
    public function scopeUserData($query, $searchText = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('corporates.*')->with('status')
            ->join('users', "corporates.owner_id", '=', 'users.id');

        if (!($loggedInUser->is_admin
            || ($loggedInUser->is_operations && $searchText))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) use ($loggedInUser) {
            $q->leftjoin('corporate_phones', 'corporate_phones.corporate_id', '=', 'corporates.id')
                ->groupBy('corporates.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp, $loggedInUser) {
                    // if ($loggedInUser->is_operations) {
                    //     $qq->where('corporates.name', '=', "$tmp")
                    //         ->orwhere('corporates.arabic_name', '=', "$tmp")
                    //         ->orwhere('corporates.email', '=', "$tmp")
                    //         ->orwhere('corporate_phones.number', '=', "$tmp")
                    //         ->orwhere('corporates.arabic_name', '=', "$tmp");
                    // } else {
                        $qq->where('corporates.name', 'LIKE', "%$tmp%")
                            ->orwhere('corporates.arabic_name', 'LIKE', "%$tmp%")
                            ->orwhere('corporates.email', 'LIKE', "%$tmp%")
                            ->orwhere('corporate_phones.number', 'LIKE', "%$tmp%")
                            ->orwhere('corporates.arabic_name', 'LIKE', "%$tmp%");
                    // }
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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

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

    public function bank_accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function offers(): MorphMany
    {
        return $this->morphMany(Offer::class, 'client');
    }

    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }
}
