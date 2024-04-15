<?php

namespace App\Models\Business;

use App\Models\Cars\Car as CarsCar;
use App\Models\Corporates\Address as CorporatesAddress;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Phone as CorporatesPhone;
use App\Models\Customers\Address;
use App\Models\Customers\Car;
use App\Models\Customers\Customer;
use App\Models\Customers\Phone;
use App\Models\Insurance\GrossCalculation;
use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\PolicyComm;
use App\Models\Payments\PolicyCost;
use App\Models\Payments\SalesComm;
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
        'in_favor_to', 'policy_doc', 'note', 'is_renewed', 'is_paid', 'client_payment_date'
    ];

    ///model functions
    public function generateRenewalOffer(Carbon $due, string $in_favor_to = null)
    {
        if (Offer::newOffer(
            client: $this->client,
            type: $this->policy->business,
            item_value: $this->insured_value,
            renewal_policy: $this->policy_number,
            item_title: "Renewal Offer",
            note: "Policy#$this->policy_number Renewal Offer",
            due: $due,
            item: ($this->customer_car_id) ? Car::find($this->customer_car_id) : null,
            is_renewal: true,
            in_favor_to: $in_favor_to ?? $this->in_favor_to
        ))   $this->update([
            'is_renewed' => true,
        ]);
    }

    public function generatePolicyCommissions()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
        $this->loadMissing('policy');
        $this->loadMissing('policy.comm_confs');
        try {
            DB::transaction(function () {
                $this->comms_details()->delete();
                $clientPaymentDate = new Carbon($this->client_payment_date);
                $policyStart = new Carbon($this->start);
                $dueDays = $clientPaymentDate->diffInDays($policyStart);
                $total_comm = 0;
                foreach ($this->policy->comm_confs as $conf) {
                    $tmp_base_value = $conf->calculation_type == GrossCalculation::TYPE_VALUE ?
                        $conf->value : (($conf->value / 100) * $this->gross_premium);
                    if ($conf->due_penalty && $dueDays > $conf->due_penalty) {
                        $tmp_base_value = $tmp_base_value - (($conf->penalty_percent / 100) * $tmp_base_value);
                    }
                    $this->comms_details()->updateOrCreate([
                        "title"     =>  $conf->title
                    ], [
                        "amount"    =>  $tmp_base_value
                    ]);
                    $total_comm += $tmp_base_value;
                }
                $this->total_policy_comm = $total_comm;
                $this->save();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addPolicyCommission($title, $amount)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this)) return false;

        try {
            DB::transaction(function () use ($title, $amount) {
                $this->comms_details()->create([
                    "title"     =>  $title,
                    "amount"    =>  $amount
                ]);
                AppLog::info("Commission changed", loggable: $this, desc: "Sold Policy commission added manually");
            });
            $this->calculateTotalPolicyComm();
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addSalesCommission($title, $from, $comm_percentage, $user_id = null, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updatePayments', $this)) return false;

        try {
            /** @var SalesComm */
            $tmp = $this->sales_comms()->create([
                "title"             => $title,
                "from"              => $from,
                "comm_percentage"   => $comm_percentage,
                "user_id"           => $user_id,
                "note"              => $note
            ]);
            $tmp->refreshAmount();
            AppLog::info("Sales commission added", loggable: $this);
            return true;

            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit sales commission", desc: $e->getMessage());
            return false;
        }
    }

    public function addClientPayment($type, $amount, Carbon $due, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateClientPayments', $this)) return false;

        try {
            if ($this->client_payments()->create([
                "type"      => $type,
                "amount"    => $amount,
                "due"       => $due->format('Y-m-d H:i:s'),
                "note"              => $note
            ])) {
                AppLog::info("Client payment added", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add client payment", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function calculateTotalPolicyComm()
    {
        $tmp = 0;
        foreach ($this->comms_details as $comm) {
            $tmp += $comm->amount;
        }
        $this->total_policy_comm = $tmp;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function calculateTotalCompanyPayments()
    {
        $tmp = 0;
        foreach ($this->company_comm_payments()->paid()->get() as $comm) {
            $tmp += $comm->amount;
        }
        $this->total_comp_paid = $tmp;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function calculateTotalClientPayments()
    {
        $tmp = 0;
        foreach ($this->client_payments()->paid()->get() as $comm) {
            $tmp += $comm->amount;
        }
        $this->total_client_paid = $tmp;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function calculateTotalSalesCommPaid()
    {
        $tmp = 0;
        foreach ($this->sales_comms()->paid()->get() as $comm) {
            $tmp += $comm->amount;
        }
        $this->total_sales_comm = $tmp;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setClientPaymentDate(Carbon $date)
    {
        try {
            $this->client_payment_date = $date->format('Y-m-d H:i');
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function editInfo(Carbon $start, Carbon $expiry, $policy_number, $car_chassis = null, $car_plate_no = null, $car_engine = null, $in_favor_to = null): self|bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        $this->update([
            'is_valid' => 1,
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
            Storage::disk('s3')->delete($this->policy_doc);
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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        try {
            $this->update([
                'is_valid' => 0,
            ]);
            if (Auth::user())
                $this->sendPolicyNotifications("Policy#$this->id inactivated", Auth::user()->username . " inactivated the policy");
            AppLog::info("Sold Policy inactivated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't invalidate Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public function setPaid($is_paid, Carbon $client_payment_date = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this)) return false;

        try {
            $this->update([
                'is_paid' => $is_paid,
                "client_payment_date"   =>  $client_payment_date->format('Y-m-d H:i')
            ]);

            $is_paid ?
                $this->sendPolicyNotifications("Policy#$this->id paid", Auth::user()->username . " set the policy as paid") :
                $this->sendPolicyNotifications("Policy#$this->id unpaid", Auth::user()->username . " set the policy as unpaid");
            AppLog::info("Sold Policy is_paid set to " . $is_paid, loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update is_paid", desc: $e->getMessage());
            return false;
        }
    }

    public function setNotPaid()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this)) return false;

        try {
            $this->update([
                'is_paid' => 0,
                "client_payment_date"   =>  null
            ]);

            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update is_paid", desc: $e->getMessage());
            return false;
        }
    }


    public function updatePaymentInfo($insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, $discount)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
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

    public function setDocInfo(
        $policy_id,
        $insured_value,
        $net_premium,
        $gross_premium,
        $start_date,
        $expiry,
        $chassis,
        $motor_no,
        $note,
        $car_id
    ) {
        try {
            $this->creator_id = 1;
            $this->policy_id = $policy_id;
            $this->insured_value = $insured_value ?? 0;
            $this->net_rate = $insured_value ? ($net_premium / $insured_value) : 0;
            $this->net_premium = $net_premium ?? 0;
            $this->gross_premium = $gross_premium ?? 0;
            $this->start = $start_date;
            $this->expiry = $expiry;
            $this->car_chassis = $chassis;
            $this->car_engine = $motor_no;
            $this->customer_car_id = $car_id;
            $this->note = $note;
            $this->save();
        } catch (Exception $e) {
            report($e);
        }
    }

    public function setWatchers(array $user_ids = [])
    {
        try {
            $this->sendPolicyNotifications("Policy#$this->id watchers change", Auth::user()->username . " changed watcher list");
            $this->watchers()->sync($user_ids);
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
            'creator_id' => Auth::id() ?? 1,
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

    public static function importNewAllianzFile($file)
    {
        $spreadsheet = IOFactory::load($file);
        if (!$spreadsheet) {
            throw new Exception('Failed to read files content');
        }
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            if ($activeSheet->getCell('L' . $i)->getValue() == "ENDORSEMENT") continue;

            $client_type = $activeSheet->getCell('V' . $i)->getValue() == "INDIVIDUAL" ? "client" : "corporate";

            //policy data
            $policy_number = $activeSheet->getCell('F' . $i)->getValue();

            $policy_name = $activeSheet->getCell('C' . $i)->getValue();
            $gross_premium = abs($activeSheet->getCell('AK' . $i)->getValue() ?? 0);
            $net_premium = abs($activeSheet->getCell('AK' . $i)->getValue() ?? 0);
            $insured_value = abs($activeSheet->getCell('AL' . $i)->getValue() ?? 0);
            $start_date = $activeSheet->getCell('N' . $i)->getValue() ? Carbon::createFromFormat("d-M-y", ($activeSheet->getCell('N' . $i)->getFormattedValue())) : new Carbon();
            $expiry = $activeSheet->getCell('O' . $i)->getValue() ? Carbon::createFromFormat("d-M-y", ($activeSheet->getCell('O' . $i)->getFormattedValue())) : new Carbon();
            $note = $activeSheet->getCell('I' . $i)->getValue();
            $company_name = "Allianz";
            if ($client_type == 'client') $line_of_business = Policy::BUSINESS_PERSONAL_MOTOR;
            else {
                $line_of_business = Policy::BUSINESS_CORPORATE_MOTOR;
            }
            $policy = null;
            if ($policy_name == 'Motor Corporate' || $policy_name == 'Motor Commercial') {
                $policy = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business, "Motor One");
            } else {
                $policy = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business, $policy_name);
            }

            if ($policy == null) {
                $policy = Policy::getPolicyByNameAndLineOfBusiness($company_name, $line_of_business, "Motor One");
            }

            $is_active = $activeSheet->getCell('K' . $i)->getValue() == "INFORCE";

            //client data

            $full_name = $activeSheet->getCell('Q' . $i)->getValue();
            $national_id = $activeSheet->getCell('T' . $i)->getValue();
            $address = (is_string($activeSheet->getCell('U' . $i)->getValue())) ?
                $activeSheet->getCell('U' . $i)->getValue() : null;
            $tel1 = ($activeSheet->getCell('S' . $i)->getValue() != "0" &&
                is_numeric($activeSheet->getCell('S' . $i)->getValue())) ?
                $activeSheet->getCell('S' . $i)->getValue() : null;
            $tel2 = (
                $activeSheet->getCell('R' . $i)->getValue() != "0" &&
                $tel1 !== $activeSheet->getCell('R' . $i)->getValue() &&
                is_numeric($activeSheet->getCell('R' . $i)->getValue())) ?
                $activeSheet->getCell('R' . $i)->getValue() : null;

            ///car info
            $model_year = $activeSheet->getCell('AD' . $i)->getValue();
            $motor_no = $activeSheet->getCell('AE' . $i)->getValue();
            $chassis_no = $activeSheet->getCell('AF' . $i)->getValue();
            $brandName = $activeSheet->getCell('AI' . $i)->getValue();
            $modelName = $activeSheet->getCell('AJ' . $i)->getValue();
            $car = CarsCar::getByBrandAndModel($brandName, $modelName);

            $foundSoldPolicy = self::byPolicyNumber($policy_number)->first();
            if (!$full_name) {
                Log::warning("Row#$i has no name");
                continue;
            }

            if ($foundSoldPolicy) {
                $client = $foundSoldPolicy->client;
                $car_id = $client->setDocInfo($full_name, $national_id, $address, $tel1, $tel2, $car, $model_year, $insured_value);
                $foundSoldPolicy->setDocInfo(
                    $policy->id,
                    $insured_value,
                    $net_premium,
                    $gross_premium,
                    $start_date->format('Y-m-d H:i:s'),
                    $expiry->format('Y-m-d H:i:s'),
                    $chassis_no,
                    $motor_no,
                    $note,
                    $car_id
                );
                Log::info("Policy#$policy_number updated on row $i");
            } else {
                try {
                    $tmpClient = null;
                    $tmpCar = null;
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
                            id_type: Customer::IDTYPE_NATIONAL_ID,
                            id_number: $national_id,
                            email: "test@mail"
                        );
                        if ($tel1) $tmpClient->addPhone(Phone::TYPE_MOBILE, $tel1, true);
                        if ($tel2) $tmpClient->addPhone(Phone::TYPE_HOME, $tel2, false);
                        if ($address) $tmpClient->addAddress(type: Address::TYPE_HOME, line_1: $address, country: "Egypt");
                        if ($car) $tmpCar = $tmpClient->addCar($car->id, model_year: $model_year, sum_insured: $insured_value);
                    } else {
                        $tmpClient = Corporate::newCorporate(
                            owner_id: 10,
                            name: $full_name
                        );
                        if ($tel1) $tmpClient->addPhone(CorporatesPhone::TYPE_WORK, $tel1, true);
                        if ($tel2) $tmpClient->addPhone(CorporatesPhone::TYPE_WORK, $tel2, false);
                        if ($address) $tmpClient->addAddress(type: CorporatesAddress::TYPE_HQ, line_1: $address, country: "Egypt");
                    }


                    if (!$tmpClient) {
                        Log::warning("Row#$i has no client");
                        continue;
                    }

                    if (is_numeric($net_premium) && is_numeric($insured_value)) {

                        $tmpPolicy = SoldPolicy::newSoldPolicy(
                            client: $tmpClient,
                            policy_id: $policy->id,
                            policy_number: $policy_number,
                            insured_value: $insured_value ?? 0,
                            net_rate: $insured_value ? ($net_premium / $insured_value) : 0,
                            net_premium: $net_premium ?? 0,
                            gross_premium: $gross_premium ?? 0,
                            customer_car_id: $tmpCar?->id,
                            installements_count: 1,
                            payment_frequency: OfferOption::PAYMENT_FREQ_YEARLY,
                            start: $start_date,
                            expiry: $expiry,
                            car_chassis: $chassis_no,
                            car_engine: $motor_no,
                            note: $note,
                        );
                        if (!$is_active) $tmpPolicy->setAsInvalid();
                        Log::info("Policy#$policy_number added");
                    } else Log::warning("Invalid insured / net prem on Row#$i");
                } catch (Exception $e) {
                    Log::warning("Row#$i crashed");
                    Log::warning($e->getMessage());
                    Log::warning($e->getFile() . " " . $e->getLine());
                }
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
            ->leftjoin('policy_watchers', 'policy_watchers.sold_policy_id', '=', 'sold_policies.id');

        if (!($loggedInUser->is_admin
            || ($loggedInUser->is_operations && ($searchText || $is_expiring)))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id)
                    ->orwhere('policy_watchers.user_id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) use ($loggedInUser, $is_expiring) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('sold_policies.client_id', '=', 'corporates.id')
                    ->where('sold_policies.client_type', Corporate::MORPH_TYPE)
                    ->join('corporate_phones', 'corporate_phones.corporate_id', '=', 'corporates.id');
            })->leftjoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', Customer::MORPH_TYPE)
                    ->join('customer_phones', 'customer_phones.customer_id', '=', 'customers.id');
            })->groupBy('sold_policies.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp, $loggedInUser, $is_expiring) {
                    if ($loggedInUser->is_operations && !$is_expiring) {
                        $qq->where('customers.email', '=', "$tmp")
                            // ->orwhere('customer_phones.number', '=', "%$tmp%")
                            //search using customer info
                            ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                            ->orwhere('corporates.email', '=', "$tmp")
                            //search using policy info
                            ->orwhere('policy_number', '=', "$tmp")
                            ->orwhere('customer_phones.number', '=', "$tmp")
                            ->orwhere('corporate_phones.number', '=', "$tmp")
                            //search using car info
                            ->orwhere('car_chassis', '=', "$tmp")
                            ->orwhere('car_engine', '=', "$tmp")
                            ->orwhere('car_plate_no', '=', "$tmp");
                    } else {
                        $qq->where('customers.first_name', 'LIKE', "%$tmp%")
                            //search using customer info
                            ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.middle_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_first_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_last_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.arabic_middle_name', 'LIKE', "%$tmp%")
                            ->orwhere('customers.email', 'LIKE', "%$tmp%")
                            ->orwhere('customer_phones.number', 'LIKE', "%$tmp%")
                            // ->orwhere('customer_phones.number', 'LIKE', "%$tmp%")
                            //search using customer info
                            ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                            ->orwhere('corporates.email', 'LIKE', "%$tmp%")
                            ->orwhere('corporate_phones.number', 'LIKE', "%$tmp%")
                            //search using policy info
                            ->orwhere('policy_number', 'LIKE', "%$tmp%")
                            //search using car info
                            ->orwhere('car_chassis', 'LIKE', "%$tmp%")
                            ->orwhere('car_engine', 'LIKE', "%$tmp%")
                            ->orwhere('car_plate_no', 'LIKE', "%$tmp%");
                    }
                });
            }
        });

        $query->when($is_expiring, function ($q) {
            $now = Carbon::now();
            $now->addMonth();
            $q->where('is_renewed', 0)->whereBetween("expiry", [
                $now->format('Y-m-01'),
                $now->format('Y-m-t'),
            ]);
        });
        return $query->latest();
    }

    public function scopeByPaid($query, $is_paid)
    {
        Log::info($is_paid);
        return $query->where('sold_policies.is_paid', $is_paid);
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

    public function scopeByPolicyNumber($query, $number)
    {
        return $query->where('policy_number', $number);
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

    public function comms_details(): HasMany
    {
        return $this->hasMany(PolicyComm::class);
    }

    public function company_comm_payments(): HasMany
    {
        return $this->hasMany(CompanyCommPayment::class);
    }

    public function client_payments(): HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function sales_comms(): HasMany
    {
        return $this->hasMany(SalesComm::class);
    }
}
