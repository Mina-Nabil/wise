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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        'type',
        'first_name',
        'middle_name',
        'last_name',
        'arabic_first_name',
        'arabic_middle_name',
        'arabic_last_name',
        'email',
        'gender',
        'owner_id',
        'marital_status',
        'nationality_id',
        'id_type',
        'id_number',
        'profession_id',
        'salary_range',
        'income_source',
        'birth_date',
        'id_doc',
        'driver_license_doc',
        'note',
        'id_doc_2',
        'driver_license_doc_2',
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
                "desc"      =>  $desc,
                "is_meeting"        =>  $is_meeting,
                "line_of_business"  =>  $line_of_business,
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
        $id_doc_2 = null,
        $driver_license_doc_2 = null,
    ): bool {
        $updates['first_name'] = $first_name;
        $updates['last_name'] = $last_name;
        if ($middle_name) $updates['middle_name'] = $middle_name;
        if ($arabic_first_name) $updates['arabic_first_name'] = $arabic_first_name;
        if ($arabic_middle_name) $updates['arabic_middle_name'] = $arabic_middle_name;
        if ($arabic_last_name) $updates['arabic_last_name'] = $arabic_last_name;
        if ($birth_date) $updates['birth_date'] = $birth_date;
        if ($email) $updates['email'] = $email;
        if ($gender) $updates['gender'] = $gender;
        if ($marital_status) $updates['marital_status'] = $marital_status;
        if ($id_type) $updates['id_type'] = $id_type;
        if ($id_number) $updates['id_number'] = $id_number;
        if ($nationality_id) $updates['nationality_id'] = $nationality_id;
        if ($profession_id) $updates['profession_id'] = $profession_id;
        if ($salary_range) $updates['salary_range'] = $salary_range;
        if ($income_source) $updates['income_source'] = $income_source;
        if ($id_doc) $updates['id_doc'] = $id_doc;
        if ($driver_license_doc) $updates['driver_license_doc'] = $driver_license_doc;
        if ($id_doc_2) $updates['id_doc_2'] = $id_doc_2;
        if ($driver_license_doc_2) $updates['driver_license_doc_2'] = $driver_license_doc_2;

        $this->update($updates);

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

    public function addPhone($type, $number, $is_default = null, $checkIfNumberExist = false): Phone|false
    {
        try {

            $tmpPhone = $this->phones()->where('number', $number)->first();
            if ($tmpPhone && $checkIfNumberExist) {
                return $tmpPhone;
            }

            /** @var Phone */
            $tmp = $this->phones()->create([
                "type"      =>  $type,
                "number"    =>  $number
            ]);
            if ($is_default || $this->phones()->get()->count() == 1) $tmp->setAsDefault();
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
            $newLead->setStatus(Status::STATUS_NEW, 'new lead');
            AppLog::info('New customer lead created', loggable: $newLead);
            return $newLead;
        } catch (Exception $e) {
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
        $id_doc_2 = null,
        $driver_license_doc_2 = null,
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
            "id_doc_2"        =>  $id_doc_2,
            "driver_license_doc"    =>  $driver_license_doc,
            "driver_license_doc_2"    =>  $driver_license_doc_2,
            "note"          =>  $note,
            "creator_id"    => Auth::id()
        ]);

        try {
            $newCustomer->save();
            $newCustomer->setStatus(Status::STATUS_CLIENT, 'new customer');
            AppLog::info('New customer created', loggable: $newCustomer);
            return $newCustomer;
        } catch (Exception $e) {

            report($e);
            AppLog::error('Unable to create customer', desc: $e->getMessage());
            return false;
        }
    }

    public static function exportLeads($user_id = null)
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('exportAndImport', self::class)) return false;

        $leads = self::leads()->with('phones', 'owner')->when($user_id, function ($q, $v) {
            $q->where('owner_id', $v);
        })->get();

        $template = IOFactory::load(resource_path('import/leads_data.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();
        $activeSheet->getCell('I1')->setValue("NOTE");
        $i = 2;
        foreach ($leads as $lead) {
            $activeSheet->getCell('A' . $i)->setValue($lead->id);
            $activeSheet->getCell('B' . $i)->setValue($lead->first_name);
            $activeSheet->getCell('C' . $i)->setValue($lead->last_name);
            $activeSheet->getCell('D' . $i)->setValue($lead->arabic_first_name);
            $activeSheet->getCell('E' . $i)->setValue($lead->arabic_last_name);
            $activeSheet->getCell('F' . $i)->setValue($lead->telephone1);
            $activeSheet->getCell('G' . $i)->setValue($lead->telephone2);
            $activeSheet->getCell('H' . $i)->setValue($lead->owner?->username);
            $activeSheet->getCell('I' . $i)->setValue($lead->note);
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "leads_export.xlsx";
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
            $first_name     =  $activeSheet->getCell('B' . $i)->getValue();
            $last_name      =  $activeSheet->getCell('C' . $i)->getValue();
            $first_arabic_name  =  $activeSheet->getCell('D' . $i)->getValue();
            $last_arabic_name   =  $activeSheet->getCell('E' . $i)->getValue();
            $telephone1     =  $activeSheet->getCell('F' . $i)->getValue();
            $telephone2     =  $activeSheet->getCell('G' . $i)->getValue();
            $username       =  $activeSheet->getCell('H' . $i)->getValue();
            $note           =  $activeSheet->getCell('I' . $i)->getValue();

            if (!$first_name || !$last_name || !$telephone1) continue;
            $user = User::userExists($username);


            if (!$user) $user = Auth::user();

            if ($id) {
                /** @var self */
                $lead = self::find($id);
                if (!$lead) continue;

                $lead->editCustomer($first_name, $last_name, arabic_first_name: $first_arabic_name, arabic_last_name: $last_arabic_name);
                $lead->setOwner($user->id);
                if ($note)
                    $lead->setCustomerNote($note);

                if ($telephone1)
                    $lead->addPhone(Phone::TYPE_MOBILE, $telephone1, true, true);

                if ($telephone2)
                    $lead->addPhone(Phone::TYPE_MOBILE, $telephone2, false, true);
            } else {

                $lead = self::newLead($first_name, $last_name, $telephone1, note: $note, arabic_first_name: $first_arabic_name, arabic_last_name: $last_arabic_name, owner_id: $user->id);

                if ($telephone2)
                    $lead->addPhone(Phone::TYPE_MOBILE, $telephone2, false, true);
                if ($note)
                    $lead->setCustomerNote($note);
            }
        }

        return true;
    }

    public static function downloadTemplate()
    {
        $template = IOFactory::load(resource_path('import/leads_data.xlsx'));
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
        $file_path = self::FILES_DIRECTORY . "leads_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function delete()
    {
        if ($this->offers()->exists() || $this->soldpolicies()->exists()) {
            throw new Exception("Cannot delete customer with existing offers or sold policies.");
        }
    
        // Delete related models
        $this->phones()->delete();
        $this->addresses()->delete();
        $this->status()->delete();
        $this->followups()->delete();
        $this->cars()->delete();
        $this->relatives()->delete();
        $this->interests()->delete();
    
        return parent::delete();
    }



    ///attributes
    public function getNameAttribute()
    {
        return ($this->arabic_first_name && $this->arabic_last_name)
            ? $this->arabic_first_name . ' ' . $this->arabic_last_name
            : $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameAttribute()
    {
        return ($this->arabic_first_name && $this->arabic_last_name)
            ? $this->arabic_first_name . ' ' . ($this->arabic_middle_name ?  $this->arabic_middle_name . ' ' : '') . $this->arabic_last_name
            : $this->first_name . ' ' . ($this->middle_name ?  $this->middle_name . ' ' : '')  .  $this->last_name;
    }

    public function getAddressCityAttribute()
    {
        $this->load('addresses');
        return $this->addresses->first()?->city;
    }

    public function getTelephone1Attribute()
    {
        $this->load('phones');
        return $this->phones->where('is_default', 1)->first()?->number;
    }

    public function getTelephone2Attribute()
    {
        $this->load('phones');
        return $this->phones->where('is_default', 0)->first()?->number;
    }

    public function getIsDataFullAttribute()
    {
        if(!$this->first_name || !$this->last_name || !$this->middle_name || !$this->arabic_first_name || !$this->arabic_last_name || !$this->arabic_middle_name) return false; 

        if(!$this->telephone1 || !$this->address_city || !$this->id_number || !$this->id_doc) return false;
        return true;
    }

    ///scopes
    public function scopeUserData($query, $searchText = null, $mustMatchSearch = true, $statusFilter = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('customers.*')->with('status')
            ->join('users', 'customers.owner_id', '=', 'users.id');

        if (!($loggedInUser->is_admin
            || ($loggedInUser->is_operations && $searchText))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->whereIn('users.manager_id', $loggedInUser->children_ids_array)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) use ($loggedInUser, $mustMatchSearch) {
            $q->leftjoin('customer_phones', 'customer_phones.customer_id', '=', 'customers.id')
                ->groupBy('customers.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp, $loggedInUser, $mustMatchSearch) {
                    // if ($loggedInUser->is_operations && $mustMatchSearch) {
                    //     $qq->where('customers.email', '=', "$tmp")
                    //         ->orwhere('customers.first_name', '=', "$tmp")
                    //         ->orwhere('customers.last_name', '=', "$tmp")
                    //         ->orwhere('customer_phones.number', '=', "$tmp");
                    // } else {
                    $qq->where('customers.first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.id', '=', "$tmp")
                        ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.middle_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_last_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_middle_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        ->orwhere('customer_phones.number', 'LIKE', "%$tmp%");
                    // }
                });
            }
        });

        $query->when($statusFilter, function ($q, $filter) {
            $q->join('customer_status', 'customer_status.customer_id', '=', 'customers.id')
                ->where('customer_status.status', $filter);
        });
        return $query->latest();
    }

    /**
     * @param array $interests = [
     *      'line_of_business' => 1 or 0
     * ]
     */
    public function scopeReport($query, $searchText = null, Carbon $from = null, Carbon $to = null, array $interests = [])
    {
        $query->userData($searchText)
            ->when($from, function ($q, $v) {
                $q->where('customers.created_at', '>=', $v->format('Y-m-d'));
            })
            ->when($from, function ($q, $v) {
                $q->where('customers.created_at', '<=', $v->format('Y-m-d'));
            });
    }

    public function scopeLeads($query)
    {
        return $query->where('type', self::TYPE_LEAD);
    }

    public function scopeClients($query)
    {
        return $query->where('type', self::TYPE_CLIENT);
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

    public function soldpolicies(): MorphMany
    {
        return $this->morphMany(Offer::class, 'client');
    }

    public function bank_accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }
}
