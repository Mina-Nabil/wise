<?php

namespace App\Models\Business;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Car;
use App\Models\Customers\Customer;
use App\Models\Customers\Phone;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskField;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SoldPolicy extends Model
{
    use HasFactory;

    const FILES_DIRECTORY = 'sold_policies/';

    const MORPH_TYPE = 'sold_policy';

    protected $table = 'sold_policies';
    protected $fillable = [
        'creator_id', 'offer_id', 'policy_id', 'net_rate', 'net_premium',
        'gross_premium', 'installements_count', 'start', 'expiry', 'discount',
        'payment_frequency', 'is_valid', 'customer_car_id', 'insured_value',
        'car_chassis', 'car_plate_no', 'car_engine', 'policy_number',
        'in_favor_to', 'policy_doc'
    ];

    ///model functions
    public function generateRenewalOffer(Carbon $due)
    {
        return Offer::newOffer(
            client: $this->client,
            type: $this->policy->business,
            item_value: $this->insured_value,
            item_title: "Renewal Offer",
            note: "Policy#$this->policy_number Renewal Offer",
            due: $due,
            item: ($this->customer_car_id) ? Car::find($this->customer_car_id) : null,
            is_renewal: true,
            in_favor_to: $this->in_favor_to
        );
    }

    public function editInfo(Carbon $start, Carbon $expiry, $policy_number, $car_chassis = null, $car_plate_no = null, $car_engine = null, $in_favor_to = null): self|bool
    {
        $this->update([
            'policy_number' => $policy_number,
            'start' => $start->format('Y-m-d H:i:s'),
            'expiry' => $expiry->format('Y-m-d H:i:s'),
            'car_chassis' => $car_chassis,
            'car_plate_no' => $car_plate_no,
            'in_favor_to' => $in_favor_to,
            'car_engine' => $car_engine
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsValid()
    {
        $this->update([
            'active' => 1,
        ]);

        try {
            $this->save();
            $this->sendPolicyNotifications("Policy#$this->id activated", Auth::user()->username . " activated the policy");
            AppLog::info("Sold Policy activated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't validate Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function setPolicyDoc($policy_doc)
    {
        $this->update([
            'policy_doc' => $policy_doc,
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy doc updated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set Sold Policy doc", desc: $e->getMessage());
            return false;
        }
    }

    public function deletePolicyDoc()
    {
        $this->policy_doc = null;
        if ($this->save()) {
            Storage::delete($this->policy_doc);
        }

        try {
            $this->save();
            AppLog::info("Sold Policy doc deleted", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete Sold Policy doc", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsInvalid()
    {
        $this->update([
            'active' => 0,
        ]);

        try {
            $this->save();
            $this->sendPolicyNotifications("Policy#$this->id inactivated", Auth::user()->username . " inactivated the policy");
            AppLog::info("Sold Policy inactivated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't invalidate Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function updatePaymentInfo($insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, $discount)
    {
        $this->update([
            'insured_value' => $insured_value,
            'net_rate'      => $net_rate,
            'net_premium'   => $net_premium,
            'gross_premium' => $gross_premium,
            'installements_count'   => $installements_count,
            'payment_frequency'     => $payment_frequency,
            'discount'      => $discount
        ]);

        try {
            $this->save();
            $this->sendPolicyNotifications("Policy#$this->id payment info changed", Auth::user()->username . " updated payment info");
            AppLog::info("Sold Policy payment edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy payment", desc: $e->getMessage());
            return false;
        }
    }

    public function setCustomerCar($customer_car_id)
    {
        $this->update([
            'customer_car_id'      => $customer_car_id
        ]);

        try {
            $this->save();
            AppLog::info("Sold Policy customer car edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy customer car", desc: $e->getMessage());
            return false;
        }
    }

    public function setNote($note)
    {
        $this->update([
            'note'      => $note
        ]);

        try {
            $this->save();
            $this->sendPolicyNotifications("Policy#$this->id note changed", Auth::user()->username . " set the policy note");
            AppLog::info("Sold Policy note edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy note", desc: $e->getMessage());
            return false;
        }
    }

    public function addEndorsement($due = null, $desc = null, $actions = [])
    {
        $newEndors = $this->addTask(Task::TYPE_ENDORSMENT, "Policy# $this->policy_number endorsement", $desc, $due);
        if (!$newEndors) return false;
        $this->sendPolicyNotifications("Policy#$this->id endorsement added", Auth::user()->username . " added a endorsement");
        foreach ($actions as $a) {
            $newEndors->addAction($a['column_name'], $a['value']);
        }
        return $newEndors;
    }

    public function addClaim($due = null, $desc = null, $fields = [])
    {
        $newTask = $this->addTask(Task::TYPE_CLAIM, "Policy# $this->policy_number claim", $desc, $due);
        if (!$newTask) return false;
        $this->sendPolicyNotifications("Policy#$this->id claim added", Auth::user()->username . " added a claim");

        foreach (TaskField::SALES_CHECKLIST as $s) {
            $newTask->addField($s, "NO");
        }
        foreach ($fields as $f) {
            $newTask->addField($f['title'], $f['value']);
        }
        return $newTask;
    }

    private function addTask($type, $title, $desc, Carbon $due = null): Task|false
    {
        return Task::newTask($title, taskable: $this, desc: $desc, due: $due, assign_to_id_or_type: User::TYPE_OPERATIONS, type: $type);
    }

    /**
     * @param array $benefits array of 'benefit' and 'value'
     */
    public function setBenefits(array $benefits = [])
    {
        try {
            DB::transaction(function () use ($benefits) {
                $this->benefits()->delete();
                foreach ($benefits as $b) {
                    $this->addBenefit($b['benefit'], $b['value']);
                }
                $this->sendPolicyNotifications("Policy#$this->id benefits change", Auth::user()->username . " changed benefits");
                AppLog::info("Changing policy benefits", loggable: $this);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting benefits failed", desc: $e->getMessage(), loggable: $this);
            return false;
        };
    }

    public function addBenefit($benefit, $value): SoldPolicyBenefit|false
    {
        try {
            $benefit = $this->benefits()->firstOrCreate([
                "benefit"   =>  $benefit
            ], [
                "value" =>  $value
            ]);
            AppLog::info("Benefit added", loggable: $this);
            return $benefit;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting benefits failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    /**
     * @param array $benefits array of 'benefit' and 'value'
     */
    public function setExclusions(array $exclusions = [])
    {
        try {
            DB::transaction(function () use ($exclusions) {
                $this->exclusions()->delete();
                foreach ($exclusions as $e) {
                    $this->addExclusion($e['title'], $e['value']);
                }
                $this->sendPolicyNotifications("Policy#$this->id exclusions change", Auth::user()->username . " changed exclusions");
                AppLog::info("Changing policy exclusions", loggable: $this);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting exclusions failed", desc: $e->getMessage(), loggable: $this);
            return false;
        };
    }

    public function addExclusion($title, $value): SoldPolicyExclusion|false
    {
        try {
            $exclusion = $this->exclusions()->firstOrCreate([
                "title"   =>  $title
            ], [
                "value" =>  $value
            ]);
            return $exclusion;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting exclusions failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setWatchers(array $user_ids = [])
    {
        try {
            $this->sendPolicyNotifications("Policy#$this->id watchers change", Auth::user()->username . " changed watcher list");
            $this->watchers()->sync($user_ids);
            $this->addComment("Changed watchers list", false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't Set watchers", $e->getMessage(), $this);
            return false;
        }
    }

    private function sendPolicyNotifications($title, $message)
    {
        $notifier_id = Auth::id();

        if ($notifier_id != $this->assignee_id) {
            $this->loadMissing('assignee');
            $this->assignee?->pushNotification($title, $message, "sold-policies/" . $this->id);
        }
        if ($notifier_id != $this->creator_id) {
            $this->loadMissing('creator');
            $this->creator?->pushNotification($title, $message, "sold-policies/" . $this->id);
        }
        $this->loadMissing('watchers');
        foreach ($this->watchers as $watcher) {
            if ($notifier_id != $watcher->id) {
                $watcher->pushNotification($title, $message, "sold-policies/" . $this->id);
            }
        }
    }


    ///static functons
    public static function newSoldPolicy(Customer|Corporate $client, $policy_id, $policy_number, $insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, Carbon $start, Carbon $expiry, $discount = 0, $offer_id = null, $customer_car_id = null, $car_chassis = null, $car_plate_no = null, $car_engine = null, $is_valid = true, $note = null, $in_favor_to = null, $policy_doc = null): self|bool
    {
        $newSoldPolicy = new self([
            'creator_id' => Auth::id() ?? 10,
            'policy_number' => $policy_number,
            'offer_id'      => $offer_id,
            'policy_id'     => $policy_id,
            'insured_value' => $insured_value,
            'net_rate'      => $net_rate,
            'net_premium'   => $net_premium,
            'gross_premium' => $gross_premium,
            'installements_count' => $installements_count,
            'start'         => $start->format('Y-m-d H:i:s'),
            'expiry'        => $expiry->format('Y-m-d H:i:s'),
            'payment_frequency' => $payment_frequency,
            'is_valid'      => $is_valid,
            'customer_car_id' => $customer_car_id,
            'car_chassis'   => $car_chassis,
            'car_plate_no'  => $car_plate_no,
            'car_engine'    => $car_engine,
            'discount'      => $discount,
            'note'          => $note,
            'in_favor_to'   => $in_favor_to,
            'policy_doc'    => $policy_doc,
        ]);
        $newSoldPolicy->client()->associate($client);
        try {
            $newSoldPolicy->save();
            AppLog::info("New Sold Policy", loggable: $newSoldPolicy);
            return $newSoldPolicy;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public static function importData($file)
    {
        $spreadsheet = IOFactory::load($file);
        if (!$spreadsheet) {
            throw new Exception('Failed to read files content');
        }
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            try {
                //client data
                $client_type = $activeSheet->getCell('C' . $i)->getValue() == "Indv" ? "client" : "corporate";
                $full_name = $activeSheet->getCell('F' . $i)->getValue();
                $is_renewal = $activeSheet->getCell('D' . $i)->getValue() == "Renewal";
                $tel = ($activeSheet->getCell('J' . $i)->getValue() != "0" && is_numeric($activeSheet->getCell('J' . $i)->getValue())) ? $activeSheet->getCell('J' . $i)->getValue() : null;

                //policy data
                $company_name = $activeSheet->getCell('A' . $i)->getValue();
                $policy_name = $activeSheet->getCell('U' . $i)->getValue();
                $sheet_business = $activeSheet->getCell('B' . $i)->getValue();
                $line_of_business = null;
                switch ($sheet_business) {
                    case 'Motor':
                        if ($client_type == 'client') $line_of_business = Policy::BUSINESS_PERSONAL_MOTOR;
                        else $line_of_business = Policy::BUSINESS_CORPORATE_MOTOR;
                        break;
                    case 'Medical':
                        if ($client_type == 'client') $line_of_business = Policy::BUSINESS_PERSONAL_MEDICAL;
                        else $line_of_business = Policy::BUSINESS_CORPORATE_MEDICAL;
                        break;

                    case 'Laibility':
                        $line_of_business = Policy::BUSINESS_LIABILITY;
                        break;

                    default:
                        $line_of_business = null;
                        break;
                }
                if (!$line_of_business) {
                    Log::warning("Row#$i missed, failed to get line of business");
                    continue;
                }

                if ($policy_name == "0") {
                    $policy1 = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business, $company_name);
                    $policy2 = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business,  $policy_name);
                    if ($policy1) $policy = $policy1;
                    else if ($policy2) $policy = $policy2;
                } else {
                    $policy = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business, $policy_name == "0" ? $company_name :  $policy_name);
                }
                if (!$policy) {
                    Log::warning("Row#$i missed, failed to get policy");
                    continue;
                }
                Log::info($activeSheet->getCell('H' . $i)->getFormattedValue());

                //sold policy data
                $policy_number = $activeSheet->getCell('E' . $i)->getValue();
                $start_date = $activeSheet->getCell('G' . $i)->getValue() ? Carbon::createFromFormat("d/m/Y", ($activeSheet->getCell('G' . $i)->getFormattedValue())) : new Carbon();
                $expiry = $start_date->addYear();
                $net_premium = $activeSheet->getCell('M' . $i)->getValue() ?? 0;
                $gross_premium = $activeSheet->getCell('N' . $i)->getValue() ?? 0;
                $insured_value = $activeSheet->getCell('V' . $i)->getValue() ?? 0;
                $chassis = $activeSheet->getCell('W' . $i)->getValue();
                $discount = $activeSheet->getCell('AC' . $i)->getValue();
                $note = $activeSheet->getCell('BG' . $i)->getValue();

                $tmpClient = null;
                if ($client_type == 'client') {
                    $name_array = explode(" ", $full_name);
                    $middle_name = "";
                    for ($j = 1; $j < count($name_array) - 1; $j++) $middle_name .= "$name_array[$j] ";
                    $tmpClient = Customer::newCustomer(
                        owner_id: 10,
                        first_name: $name_array[0],
                        last_name: $name_array[count($name_array) - 1],
                        middle_name: trim($middle_name),
                        gender: Customer::GENDER_MALE,
                        email: "test@mail"
                    );
                    if ($tel) $tmpClient->addPhone(Phone::TYPE_MOBILE, $tel, true);
                } else {
                    $name_array = str_split($full_name);
                    $middle_name = "";
                    for ($j = 1; $j < count($name_array); $j++) $middle_name .= "$name_array[$j] ";
                    $tmpClient = Corporate::newCorporate(
                        owner_id: 10,
                        name: $full_name
                    );
                    if ($tel) $tmpClient->addPhone(Phone::TYPE_MOBILE, $tel, true);
                }
                if (is_numeric($net_premium) && is_numeric($insured_value))
                    SoldPolicy::newSoldPolicy(
                        client: $tmpClient,
                        policy_id: $policy->id,
                        policy_number: $policy_number,
                        insured_value: $insured_value ?? 0,
                        net_rate: $insured_value ? ($net_premium / $insured_value) : 0,
                        net_premium: $net_premium ?? 0,
                        gross_premium: $gross_premium ?? 0,
                        installements_count: 1,
                        payment_frequency: OfferOption::PAYMENT_FREQ_YEARLY,
                        start: $start_date,
                        expiry: $expiry,
                        car_chassis: $chassis,
                        discount: $discount,
                        note: $note
                    );
                else Log::warning("Invalid insured / net prem on Row#$i");
            } catch (Exception $e) {
                Log::warning("Row#$i crashed");
                Log::warning($e->getMessage());
                Log::warning($e->getFile() . " " . $e->getLine());
            }
        }
    }

    ///scopes
    public function scopeUserData($query, $searchText = null, $is_expiring = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('sold_policies.*')
            ->join('users', "sold_policies.creator_id", '=', 'users.id')
            ->leftjoin('policy_watchers', 'policy_watchers.offer_id', '=', 'sold_policies.id');

        if ($loggedInUser->type !== User::TYPE_ADMIN) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id)
                    ->orwhere('policy_watchers.user_id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('sold_policies.client_id', '=', 'corporates.id')
                    ->where('sold_policies.client_type', Corporate::MORPH_TYPE);
            })->leftjoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', Customer::MORPH_TYPE);
            })->groupBy('sold_policies.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    //search using customer info
                    $qq->where('customers.name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        //search using customer info
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.email', 'LIKE', "%$tmp%")
                        //search using policy info
                        ->orwhere('policy_number', 'LIKE', "%$tmp%")
                        //search using car info
                        ->orwhere('car_chassis', 'LIKE', "%$tmp%")
                        ->orwhere('car_engine', 'LIKE', "%$tmp%")
                        ->orwhere('car_plate_no', 'LIKE', "%$tmp%");
                });
            }
        });

        $query->when($is_expiring, function ($q) {
            $now = Carbon::now();
            $now->addMonth();
            $q->whereBetween("expiry", [
                $now->format('Y-m-01'),
                $now->format('Y-m-t'),
            ]);
        });
        return $query->latest();
    }

    public function scopeWithTableRelations($query)
    {
        return $query->with('client', 'policy', 'creator', 'customer_car');
    }

    public function scopeWithProfileRelations($query)
    {
        return $query->with(
            'client',
            'policy',
            'creator',
            'customer_car',
            'claims',
            'endorsements',
            'benefits',
            'exclusions'
        );
    }

    ///relations
    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function customer_car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'customer_car_id');
    }

    public function claims(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->where('type', Task::TYPE_CLAIM);
    }

    public function endorsements(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->where('type', Task::TYPE_ENDORSMENT);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(SoldPolicyBenefit::class);
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(SoldPolicyExclusion::class);
    }

    public function watcher_ids(): HasMany
    {
        return $this->hasMany(SoldPolicyWatcher::class);
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'policy_watchers');
    }
}
