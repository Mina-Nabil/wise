<?php

namespace App\Models\Corporates;

use App\Models\Customers\Followup;
use App\Models\Tasks\Task;
use App\Models\Offers\Offer;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Corporate extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'corporate';
    const FILES_DIRECTORY = 'customers/docs/';
    const TYPE_LEAD = 'lead';
    const TYPE_CLIENT = 'client';
    const TYPES = [
        self::TYPE_LEAD,
        self::TYPE_CLIENT,
    ];


    protected $fillable = [
        'type',
        'name',
        'arabic_name',
        'email',
        'commercial_record',
        'commercial_record_doc',
        'tax_id',
        'tax_id_doc',
        'kyc',
        'kyc_doc',
        'contract_doc',
        'main_bank_evidence',
        'creator_id',
        'owner_id',
        'note',
        'is_welcomed',
        'welcome_note'
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
        $updates['name'] = $name;
        if ($arabic_name) $updates['arabic_name'] = $arabic_name;
        if ($email) $updates['email'] = $email;
        if ($commercial_record) $updates['commercial_record'] = $commercial_record;
        if ($commercial_record_doc) $updates['commercial_record_doc'] = $commercial_record_doc;
        if ($tax_id) $updates['tax_id'] = $tax_id;
        if ($tax_id_doc) $updates['tax_id_doc'] = $tax_id_doc;
        if ($kyc) $updates['kyc'] = $kyc;
        if ($kyc_doc) $updates['kyc_doc'] = $kyc_doc;
        if ($contract_doc) $updates['contract_doc'] = $contract_doc;
        if ($note) $updates['note'] = $note;
        if ($main_bank_evidence) $updates['main_bank_evidence'] = $contract_doc;

        $this->update($updates);

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

    public function addPhone($type, $number, $is_default = false, $checkIfNumberExist = false): Phone|false
    {
        try {

            $tmpPhone = $this->phones()->where('number', $number)->first();
            if ($tmpPhone && $checkIfNumberExist) {
                return $tmpPhone;
            }

            /** @var Phone */
            $tmp = $this->phones()->create([
                "type"      =>  $type,
                "number"    =>  $number,
                "is_default"    =>  $is_default
            ]);
            if ($is_default || $this->phones()->get()->count() == 1) $tmp->setAsDefault();
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

    public function addContact($name, $job_title = null, $email = null, $phone = null, $is_default = false, $checkIfContactExist = false): Contact|false
    {
        try {
            $tmpContact = $this->contacts()->where('name', $name)->first();
            if ($checkIfContactExist && $tmpContact) {
                return $tmpContact;
            }

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
                "user_id"      =>  Auth::id() ?? 1,
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

    public function setDocInfo($full_name, $national_id, $address, $tel1, $tel2, $car = NULL, $model_year = NULL, $insured_value = NULL)
    {
        try {
            $this->name = $full_name;
            $this->commercial_record = $national_id;
            if ($address)
                $this->addAddress(Address::TYPE_HQ, $address, country: "Egypt");
            if ($tel1) $this->addPhone(Phone::TYPE_WORK, $tel1, true);
            if ($tel2) $this->addPhone(Phone::TYPE_WORK, $tel2, false);
            $this->save();
        } catch (Exception $e) {
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

    public static function exportLeads($user_id = null)
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('exportAndImport', self::class)) return false;

        $leads = self::leads()->with('phones', 'owner', 'contacts')->when($user_id, function ($q, $v) {
            $q->where('owner_id', $v);
        })->get();

        $template = IOFactory::load(resource_path('import/corporate_leads_data.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();
        $activeSheet->getCell('I1')->setValue("NOTE");
        $i = 2;
        foreach ($leads as $lead) {
            $main_contact = $lead->contacts()->orderBy('is_default', 'desc')->first();
            $activeSheet->getCell('A' . $i)->setValue($lead->id);
            $activeSheet->getCell('B' . $i)->setValue($lead->name);
            $activeSheet->getCell('C' . $i)->setValue($main_contact?->name);
            $activeSheet->getCell('D' . $i)->setValue($main_contact?->title);
            $activeSheet->getCell('E' . $i)->setValue($lead->arabic_name);
            $activeSheet->getCell('F' . $i)->setValue($lead->telephone1);
            $activeSheet->getCell('G' . $i)->setValue($main_contact?->phone);
            $activeSheet->getCell('H' . $i)->setValue($lead->owner?->username);
            $activeSheet->getCell('I' . $i)->setValue($lead->note);
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "corporate_leads_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public static function importLeads($file)
    {
        $spreadsheet = IOFactory::load($file);
        if (!$spreadsheet) {
            throw new Exception('Failed to read files content');
        }
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            $id     =  $activeSheet->getCell('A' . $i)->getValue();
            $company_name     =  $activeSheet->getCell('B' . $i)->getValue();
            $contact_name      =  $activeSheet->getCell('C' . $i)->getValue();
            $contact_title  =  $activeSheet->getCell('D' . $i)->getValue();
            $company_arabic_name  =  $activeSheet->getCell('E' . $i)->getValue();
            $company_phone   =  $activeSheet->getCell('F' . $i)->getValue();
            $contact_phone     =  $activeSheet->getCell('G' . $i)->getValue();
            $username     =  $activeSheet->getCell('H' . $i)->getValue();
            $note           =  $activeSheet->getCell('I' . $i)->getValue();

            if (!$company_name || !$contact_name || !$company_phone || !$contact_phone) continue;
            $user = User::userExists($username);


            if (!$user) $user = Auth::user();

            if ($id) {
                /** @var self */
                $lead = self::find($id);
                if (!$lead) continue;

                $lead->editInfo($company_name, $company_arabic_name);
                $lead->setOwner($user->id);

                if ($company_phone)
                    $lead->addPhone(Phone::TYPE_MOBILE, $company_phone, false, true);

                $lead->addContact($contact_name, $contact_title, null, $contact_phone, true, true);

                if ($note)
                    $lead->setCorporateNote($note);
            } else {

                $lead = self::newLead($company_name, $company_arabic_name, owner_id: $user->id);
                $lead->addContact($contact_name, $contact_title, null, $contact_phone, true);

                if ($company_phone)
                    $lead->addPhone(Phone::TYPE_MOBILE, $company_phone, false);

                if ($note)
                    $lead->setCorporateNote($note);
            }
        }

        return true;
    }

    public static function downloadTemplate()
    {
        $template = IOFactory::load(resource_path('import/corporate_leads_data.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $sales = User::active()->get();
        $i = 5;
        foreach ($sales as $s) {
            $activeSheet->getCell('N' . $i)->setValue($s->username);
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "corporate_leads_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
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
            $newLead->setStatus(Status::STATUS_NEW, 'New lead');
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
            "creator_id"    => Auth::id() ?? 1,
            "note"          =>  $note
        ]);

        try {
            $newCorporate->save();
            $newCorporate->setStatus(Status::STATUS_CLIENT, 'New Client');

            AppLog::info('New corporate created', loggable: $newCorporate);
            return $newCorporate;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t create new corporate', desc: $e->getMessage());
            return false;
        }
    }

    public function delete()
    {
        if ($this->offers()->exists() || $this->soldpolicies()->exists()) {
            throw new Exception("Cannot delete corporate with existing offers or sold policies.");
        }

        // Delete related models
        $this->phones()->delete();
        $this->addresses()->delete();
        $this->status()->delete();
        $this->followups()->delete();
        $this->contacts()->delete();
        $this->bank_accounts()->delete();
        $this->interests()->delete();

        return parent::delete();
    }

    ///attributes
    public function getFullNameAttribute()
    {
        return $this->arabic_name ? $this->arabic_name : $this->name;
    }

    public function getTelephone1Attribute()
    {
        $this->load('phones');
        return $this->phones->where('is_default', 1)->first()?->number;
    }

    public function getContact1Attribute()
    {
        $this->load('contacts');
        return $this->contacts->first();
    }

    public function getAddressCityAttribute()
    {
        $this->load('addresses');
        return $this->addresses->first()?->city;
    }

    public function getTelephone2Attribute()
    {
        $this->load('phones');
        return $this->phones->where('is_default', 0)->first()?->number;
    }

    public function getIsDataFullAttribute()
    {
        if (!$this->name || !$this->arabic_name || !$this->contact1?->name || !$this->contact1?->phone || !$this->address_city || !$this->telephone1) return false;

        if (!$this->commercial_record || !$this->commercial_record_doc || !$this->tax_id || !$this->tax_id_doc) return false;
        return true;
    }

    ///scopes
    public function scopeUserData($query, $searchText = null, $statusFilter = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('corporates.*')->with('status')
            ->join('users', "corporates.owner_id", '=', 'users.id');

        if (!($loggedInUser->is_admin
            || ($loggedInUser->is_operations && $searchText))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->whereIn('users.manager_id', $loggedInUser->children_ids_array)
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

        $query->when($statusFilter, function ($q, $filter) {
            $q->join('corporate_status', 'corporate_status.corporate_id', '=', 'corporates.id')
                ->where('corporate_status.status', $filter);
        });
        return $query->orderByDesc('corporates.created_at');
    }

    //scopes
    public function scopeLeads($query)
    {
        return $query->where('type', self::TYPE_LEAD);
    }

    /**
     * @param array $interests = [
     *      'line_of_business' => 1 or 0
     * ]
     */
    public function scopeInterestReport($query, Carbon $from = null, Carbon $to = null, array $interests = [], $owner_id = null, $is_welcomed = null)
    {
        $query->userData()
            ->when($is_welcomed !== null, function ($q) use ($is_welcomed) {
                $q->where('is_welcomed', $is_welcomed);
            })
            ->when($owner_id !== null, function ($q) use ($owner_id) {
                $q->where('customers.owner_id', $owner_id);
            })
            ->join('corporate_interests', 'corporates.id', '=', 'corporate_interests.corporate_id')
            ->select('corporates.*', 'corporate_interests.business', 'corporate_interests.interested', 'corporate_interests.note')
            ->when($from, function ($q, $v) {
                $q->where('corporate_interests.created_at', '>=', $v->format('Y-m-d'));
            })
            ->when($to, function ($q, $v) {
                $q->where('corporate_interests.created_at', '<=', $v->format('Y-m-d'));
            })
            ->when(count($interests), function ($q) use ($interests) {
                foreach ($interests as $i => $v) {
                    $q->where(function ($qq) use ($i, $v) {
                        $qq->where('corporate_interests.business', $v);
                    });
                }
            });
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

    public function soldpolicies(): MorphMany
    {
        return $this->morphMany(Offer::class, 'client');
    }

    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }
}
