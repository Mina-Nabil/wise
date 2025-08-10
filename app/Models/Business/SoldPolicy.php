<?php

namespace App\Models\Business;

use App\Helpers\Helpers;
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
use App\Models\Payments\CommProfile;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\Invoice;
use App\Models\Payments\PolicyComm;
use App\Models\Payments\SalesComm;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskAction;
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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SoldPolicy extends Model
{
    use HasFactory;

    const FILES_DIRECTORY = 'sold_policies/';

    const MORPH_TYPE = 'sold_policy';

    const DELIVERY_TYPE_NOT_DELIVERED = 'not_delivered';
    const DELIVERY_TYPE_PENDING_DELIVERY = 'pending_delivery';
    const DELIVERY_TYPE_DELIVERED_HARD_CLIENT = 'delivered_hard_to_client';
    const DELIVERY_TYPE_DELIVERED_HARD_SALESOUT = 'delivered_hard_to_sales_out';
    const DELIVERY_TYPE_DELIVERED_SOFTCOPY = 'delivered_softcopy';

    const DELIVERY_TYPES = [
        self::DELIVERY_TYPE_NOT_DELIVERED,
        self::DELIVERY_TYPE_PENDING_DELIVERY,
        self::DELIVERY_TYPE_DELIVERED_HARD_CLIENT,
        self::DELIVERY_TYPE_DELIVERED_HARD_SALESOUT,
        self::DELIVERY_TYPE_DELIVERED_SOFTCOPY,
    ];

    protected $table = 'sold_policies';
    protected $fillable = [
        'creator_id',
        'offer_id',
        'policy_id',
        'net_rate',
        'net_premium',
        'gross_premium',
        'installements_count',
        'start',
        'expiry',
        'discount',
        'origin_discount',
        'payment_frequency',
        'is_valid',
        'customer_car_id',
        'insured_value',
        'car_chassis',
        'car_plate_no',
        'car_engine',
        'policy_number',
        'in_favor_to',
        'policy_doc',
        'note',
        'is_renewed',
        'is_paid',
        'client_payment_date',
        'total_policy_comm',
        'total_client_paid',
        'total_sales_comm',
        'total_comp_paid',
        'policy_comm_note',
        'assigned_to_id',
        'main_sales_id',
        'created_at',
        'after_tax_comm',
        'sales_out_comm',
        'renewal_policy_id',
        'is_penalized',
        'delivery_type',
        'is_reviewed',
        'is_valid_data',
        'review_comment',
        'reviewed_at',
        'cancellation_time',
        'is_manual_penalty',
        'penalty_amount'
    ];


    ///model functions
    public function generateRenewalOffer(Carbon $due, string $in_favor_to = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('generateRenewalOffer', $this)) return false;

        $newOffer = Offer::newOffer(
            client: $this->client,
            type: $this->policy->business,
            item_value: $this->insured_value,
            renewal_policy: $this->policy_number,
            item_title: "Renewal Offer",
            note: "Policy#$this->policy_number Renewal Offer",
            due: $due,
            item: ($this->customer_car_id) ? Car::find($this->customer_car_id) : null,
            is_renewal: true,
            in_favor_to: $in_favor_to ?? $this->in_favor_to,
            renewal_policy_id: $this->id
        );
        if ($newOffer) {
            $this->update([
                'is_renewed' => true,
            ]);
            foreach ($this->files()->get() as $f) {
                $newOffer->addFile($f->name, $f->url);
            }
            foreach ($this->sales_comms()->get() as $sc) {
                $newOffer->addCommProfile($sc->comm_profile_id);
            }
            $fields = [];
            foreach ($this->fields()->get() as $f) {
                $fields[$f->id] = [
                    'field' => $f->field,
                    'value' => $f->value,
                    'is_mandatory' => $f->is_mandatory,
                ];
            }

            $newOffer->setLineFields($fields);
            return $newOffer;
        }
    }

    public function setPenaltyInfo($is_manual_penalty, $is_penalized, $penalty_amount)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePenalty', $this)) return false;
        try {
            if ($is_manual_penalty) {
                return $this->update([
                    'is_manual_penalty' => $is_manual_penalty,
                    'is_penalized' => $is_penalized,
                    'penalty_amount' => $penalty_amount,
                ]);
            } else {
                $this->update([
                    'is_manual_penalty' => false,
                ]);
                $this->generatePolicyCommissions();
                return true;
            }
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function generatePolicyCommissions($skipUserCheck = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$skipUserCheck && !$loggedInUser->can('update', $this)) return false;

        $this->load('policy');
        $this->load('policy.comm_confs');
        try {
            DB::transaction(function () {
                $this->comms_details()->automatic()->delete();
                $clientPaymentDate = $this->first_client_payment_date ?? $this->client_payment_date;
                $issueDate = $this->created_at ? new Carbon($this->created_at) : null;
                $policyStart = new Carbon($this->start);
                $refDate = $issueDate ?  ($issueDate->max($policyStart)) : $policyStart;

                $dueDays = $refDate->diffInDays($clientPaymentDate, false);
                $total_comm = 0;
                foreach ($this->policy->comm_confs as $conf) {
                    if ($conf->sales_out_only && !$this->has_sales_out) continue;

                    $tmp_base_value = $conf->calculation_type == GrossCalculation::TYPE_VALUE ?
                        $conf->value : (($conf->value / 100) * $this->net_premium);

                    if (!$this->is_manual_penalty) {
                        if ($conf->due_penalty && $dueDays > $conf->due_penalty) {
                            $this->penalty_amount = (($conf->penalty_percent / 100) * $tmp_base_value);
                            $tmp_base_value = $tmp_base_value - $this->penalty_amount;
                            $this->is_penalized = true;
                            $this->save();
                        } else {
                            $this->is_penalized = false;
                            $this->save();
                        }
                    }

                    $this->comms_details()->updateOrCreate([
                        "title"     =>  $conf->title
                    ], [
                        "amount"    =>  $tmp_base_value
                    ]);
                    $total_comm += $tmp_base_value;
                }
                $clientPaidPercent = $this->client_payments()->whereIn('status', [ClientPayment::PYMT_STATE_PAID, ClientPayment::PYMT_STATE_PREM_COLLECTED])->sum('amount') / $this->gross_premium;
                $this->total_policy_comm = $total_comm * $clientPaidPercent;
                $this->after_tax_comm = $this->total_policy_comm * (1 - Invoice::TAX_RATE);
                $this->tax_amount = $this->total_policy_comm * Invoice::TAX_RATE;
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
        if (!$loggedInUser->can('updateWiseCommPayments', $this)) return false;

        try {
            DB::transaction(function () use ($title, $amount) {
                $this->comms_details()->create([
                    "title"     =>  $title,
                    "amount"    =>  $amount,
                    "is_manual" =>  true
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

    public function setDeliveryType(string $deliveryType)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        try {
            $this->delivery_type = $deliveryType;
            $this->save();
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function addSalesCommission($title, $from, $comm_percentage, $comm_profile_id = null, $note = null, $is_direct = false)
    {
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if ($loggedInUser && !$loggedInUser->can('updatePayments', $this)) return false;

        try {
            /** @var SalesComm */
            $tmp = $this->sales_comms()->create([
                "title"             => $title,
                "from"              => $from,
                "comm_percentage"   => $comm_percentage,
                "comm_profile_id"   => $comm_profile_id,
                "note"              => $note,
                "created_at"        => $this->created_at,
                "is_direct"         => $is_direct
            ]);
            $tmp->refreshPaymentInfo(false);
            AppLog::info("Sales commission added", loggable: $this);
            return true;

            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit sales commission", desc: $e->getMessage());
            return false;
        }
    }

    //takhod profile id men el nas el already leha sales comms 3al sold policy .. use CommProfile -> scopeLinkedToSoldPolicy 
    public function adjustSalesCommission($from, $amount, $comm_profile_id = null, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser && !$loggedInUser->can('updatePayments', $this)) return false;

        try {

            $from_amount = $this->getFromAmount($from);
            if ($from_amount == 0) return false;

            $comm_percentage = ($amount * 100) / $from_amount;

            /** @var SalesComm */
            $tmp = $this->sales_comms()->create([
                "title"             => "Manual Adjustment",
                "from"              => $from,
                "comm_percentage"   => $comm_percentage,
                "amount"            => $amount,
                "comm_profile_id"   => $comm_profile_id,
                "note"              => $note,
                "created_at"        => $this->created_at,
                "is_direct"         => true
            ]);
            AppLog::info("Sales commission added", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit sales commission", desc: $e->getMessage());
            return false;
        }
    }

    public function createMissingClientPayments()
    {
        $total_client_payments = $this->client_payments()->whereNot('status', ClientPayment::PYMT_STATE_CANCELLED)->sum('amount');
        $total_client_payments_amount = $this->gross_premium - $total_client_payments;
        if ($total_client_payments_amount > 0) {
            $this->addClientPayment(ClientPayment::PYMT_TYPE_CASH, $total_client_payments_amount, Carbon::now()->addDays(7), $this->main_sales_id, "Missing client payment");
        }
    }


    public function addClientPayment($type, $amount, Carbon $due, $assigned_to_id = null, $note = null, $sales_out_id = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser && !$loggedInUser->can('updateClientPayments', $this)) return false;

        assert($amount <= ($this->gross_premium - $this->total_client_paid), "Amount is more that what the client should pay. Please make sure the amount is less than the gross premium plus total paid");

        try {
            if ($this->client_payments()->create([
                "type"          => $type,
                "status"        => ClientPayment::PYMT_STATE_NEW,
                "amount"        => $amount,
                "assigned_to"   => $assigned_to_id ?? Auth::id(),
                "due"           => $due->format('Y-m-d H:i:s'),
                "note"          => $note,
                "sales_out_id"  => $sales_out_id,
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

    public function addCompanyPayment($type, $amount, $note = null, $invoice_id = null, $pymnt_perm = null, $skip_user_check = false)
    {
        if (!$skip_user_check) {
            /** @var User */
            $loggedInUser = Auth::user();
            if ($loggedInUser && !$loggedInUser->can('updatePayments', $this)) return false;
        }

        assert($amount <= ($this->after_tax_comm - $this->total_company_paid), "Amount is more that what the company should pay. Please make sure the amount is less than the total commission after tax plus the company payments total");

        $total_new_payments = $this->company_comm_payments()->where('status', CompanyCommPayment::PYMT_STATE_NEW)->get()->sum('amount');
        assert($amount <= ($this->after_tax_comm - $total_new_payments), "Amount is more that what the company should pay plus already added payment. Please check already added 'new' payments");

        try {
            if ($this->company_comm_payments()->create([
                "type"      => $type,
                "amount"    => $amount,
                "pymnt_perm"    => $pymnt_perm,
                "invoice_id"    => $invoice_id,
                "note"      => $note
            ])) {
                AppLog::info("Company payment added", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add company payment", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function calculateTotalPolicyComm()
    {
        $tmp = 0;
        $this->load('comms_details');
        foreach ($this->comms_details as $comm) {
            $tmp += $comm->amount;
        }
        $this->total_policy_comm = $tmp;
        $this->after_tax_comm = $this->total_policy_comm * (1 - Invoice::TAX_RATE);
        $this->tax_amount = $this->total_policy_comm * Invoice::TAX_RATE;

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

    public function calculateTotalSalesOutComm()
    {
        $total_sales_out = 0;
        /** @var SalesComm */
        foreach ($this->sales_comms()->notCancelled()->get() as $comm) {
            if ($comm->is_sales_out) {
                $comm->refreshPaymentInfo(false, update_soldpolicy: false);
                $total_sales_out += $comm->amount;
            }
        }
        $this->sales_out_comm = $total_sales_out;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function calculateTotalSalesComm()
    {
        $tmp = 0;
        $total_sales_out = 0;
        foreach ($this->sales_comms()->notCancelled()->get() as $comm) {
            $tmp += $comm->amount;
            if ($comm->is_sales_out) {
                $comm->refreshPaymentInfo(false, update_soldpolicy: false);
                $total_sales_out += $comm->amount;
            }
        }
        $this->total_sales_comm = $tmp;
        $this->sales_out_comm = $total_sales_out;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setClientPaymentDate(Carbon $date)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateClientPayments', $this)) return false;
        try {
            $this->is_paid = 1;
            $this->client_payment_date = $date->format('Y-m-d H:i');
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function unsetClientPaymentDate()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateClientPayments', $this)) return false;
        try {
            $this->is_paid = 0;
            $this->client_payment_date = null;
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /** Use only when client who paid in the past cancelled his payment. */
    public function setClientCancellationDate(Carbon $date)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser && !$loggedInUser->can('updateClientPayments', $this)) return false;
        try {
            // $this->is_paid = 1;
            $this->cancellation_time = $date->format('Y-m-d H:i');
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setPolicyCommission($amount, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this)) return false;
        try {

            $this->update([
                'total_policy_comm' => $amount,
                'after_tax_comm' => $amount * (1 - Invoice::TAX_RATE),
                'tax_amount' => $amount * Invoice::TAX_RATE,
                'policy_comm_note' => $note
            ]);
            AppLog::info("Sold Policy commission edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy Commission", desc: $e->getMessage());
            return false;
        }
    }

    public function editInfo(Carbon $start, Carbon $expiry, $policy_number, $car_chassis = null, $car_plate_no = null, $car_engine = null, $in_favor_to = null, Carbon $issuing_date = null): self|bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;
        $updates = [];

        if ($car_chassis) $updates['car_chassis'] = $car_chassis;
        if ($car_plate_no) $updates['car_plate_no'] = $car_plate_no;
        if ($in_favor_to) $updates['in_favor_to'] = $in_favor_to;
        if ($car_engine) $updates['car_engine'] = $car_engine;

        $updates['policy_number'] = $policy_number;
        $updates['start'] = $start->format('Y-m-d H:i:s');
        $updates['expiry'] = $expiry->format('Y-m-d H:i:s');

        if ($issuing_date) {
            $updates['created_at'] = $issuing_date->format('Y-m-d');
            $this->sales_comms()->update([
                'created_at'    =>  $issuing_date->format('Y-m-d')
            ]);
        }
        try {
            $this->update($updates);

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

    public function setMainSales($main_sales_id, $manual_change = true)
    {
        if ($manual_change) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('updateMainSales', $this)) return false;
        }

        $this->update([
            'main_sales_id' => $main_sales_id,
        ]);

        try {
            $this->save();
            // AppLog::info("Sold Policy main sales updated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set Sold Policy main sales", desc: $e->getMessage());
            return false;
        }
    }

    public function deletePolicyDoc()
    {
        if (Storage::disk('s3')->delete($this->policy_doc)) {
            $this->policy_doc = null;
            $this->save();
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
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('updatePayments', $this)) return false;

        try {
            $this->update([
                'is_paid' => $is_paid,
                "client_payment_date"   =>  $client_payment_date->format('Y-m-d H:i')
            ]);

            $is_paid ?
                $this->sendPolicyNotifications("Policy#$this->id paid", Auth::user()?->username . " set the policy as paid") :
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

    public function updatePaymentInfo($insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, $discount, $origin_discount)
    {
        // /** @var User */
        // $loggedInUser = Auth::user();
        // if (!$loggedInUser->can('update', $this)) return false;

        $this->update([
            'insured_value' => $insured_value,
            'net_rate'      => $net_rate,
            'net_premium'   => $net_premium,
            'gross_premium' => $gross_premium,
            'installements_count'   => $installements_count,
            'payment_frequency'     => $payment_frequency,
            'discount'      => $discount,
            'origin_discount'      => $origin_discount,
        ]);

        try {
            $this->save();
            $this->sendPolicyNotifications("Policy#$this->id payment info changed", Auth::user()?->username . " updated payment info");
            AppLog::info("Sold Policy payment edited", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit Sold Policy payment", desc: $e->getMessage());
            return false;
        }
    }

    public function updateSalesCommsPaymentInfo()
    {

        $client_paid_percentage = $this->gross_premium ? round(100 * $this->total_client_paid / $this->gross_premium, 2) : 0;
        $company_paid_percentage = $this->total_policy_comm ? round(100 * $this->total_comp_paid / $this->total_policy_comm, 2) : 0;

        try {
            /** @var SalesComm */
            foreach ($this->sales_comms()->get() as $commaya) {
                $commaya->refreshPaymentInfo(false);
                $commaya->setPaidInfo(client_paid_percent: $client_paid_percentage, company_paid_percent: $company_paid_percentage);
            }
        } catch (Exception $e) {
            report($e);
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
            $this->sendPolicyNotifications("Policy#$this->id note changed", Auth::user()?->username . " set the policy note");
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

        $newEndors = $this->addTask(Task::TYPE_ENDORSMENT, "Policy# $this->policy_number endorsement - " . $this->client?->name, $desc, $due);
        if (!$newEndors) return false;
        $this->sendPolicyNotifications("Policy#$this->id endorsement added", Auth::user()->username . " added a endorsement");
        foreach ($actions as $a) {
            $newEndors->addAction($a['column_name'], $a['value']);
        }
        return $newEndors;
    }

    public function addClaim($due = null, $desc = null, $fields = [])
    {

        $newTask = $this->addTask(Task::TYPE_CLAIM, "Policy# $this->policy_number claim - " . $this->client?->name, $desc, $due);
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

    public function addTaskToOperations($due = null, $desc = null)
    {

        $newTask = $this->addTask(Task::TYPE_TASK, "Policy# $this->policy_number task", $desc, $due);
        if (!$newTask) return false;
        $this->sendPolicyNotifications("Policy#$this->id task added", Auth::user()->username . " added a claim");

        return $newTask;
    }

    public function addTask($type, $title, $desc, Carbon $due = null): Task|false
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
        $car_id = NULL
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

    public function setIsReviewed($is_reviewed, $is_valid_data, $comment = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('review', $this)) return false;

        $this->is_reviewed = $is_reviewed;
        $this->reviewed_at = Carbon::now()->format('Y-m-d H:i');
        $this->is_valid_data = $is_valid_data;
        $this->review_comment = $comment;
        $this->save();
    }

    public function cancelSoldPolicy()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $this)) return false;
        DB::transaction(function () {
            $this->client_payments()->notCollected()->get()->each(function ($payment) {
                $payment->setAsCancelled(Carbon::now(), true);
            });
            $this->client_payments()->notCollected()->delete();
            $this->sales_comms()->notPaid()->get()->each(function ($comm) {
                $comm->setAsCancelled(Carbon::now(), true);
            });
            $this->sales_comms()->notPaid()->delete();

            $this->company_comm_payments()->notPaid()->get()->each(function ($comm) {
                $comm->setAsCancelled(Carbon::now(), true);
            });
            $this->company_comm_payments()->notPaid()->delete();

            $this->load('company_comm_payments');
            if ($this->company_comm_payments->count()) {
                $this->addCompanyPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, -1 * $this->company_comm_payments->sum('amount'), "Generated automatic after sold policy cancellation");
            }
        });

        return true;
    }

    public function deleteSoldPolicy()
    {
        try {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('delete', $this)) return false;
            DB::transaction(function () {
                $this->client_payments()->delete();
                $this->tasks()->delete();
                $this->files()->delete();
                $this->claims()->delete();
                $this->endorsements()->delete();
                $this->benefits()->delete();
                $this->exclusions()->delete();
                $this->watcher_ids()->delete();
                $this->comms_details()->delete();
                $this->company_comm_payments()->delete();
                $this->sales_comms()->delete();
                $this->delete();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function getFromAmount($from)
    {
        switch ($from) {
            case CommProfileConf::FROM_NET_PREM:
                return  $this->net_premium;
            case CommProfileConf::FROM_SUM_INSURED:
                return  $this->insured_value;
            case CommProfileConf::FROM_NET_COMM:
                return   $this->after_tax_comm;
        }
    }

    //files functions
    public static function cleanSoldPolicyDirectory()
    {
        $file = new Filesystem;
        $file->cleanDirectory(storage_path(SoldPolicyDoc::FILES_DIRECTORY));
    }


    public function addFile($name, $url, $user_id = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('update', $this)) return false;


        try {
            if ($this->files()->create([
                "name"  =>  $name,
                "user_id"   => $user_id ?? Auth::id(),
                "url"  =>  $url,
            ])) {
                $this->sendPolicyNotifications("New Sold Policy File attached", "A new file is attached on Sold Policy#$this->id");

                AppLog::info("File added", loggable: $this);
                return true;
            } else {
                AppLog::error("File addition failed", desc: "Failed to add file", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("File addition failed", desc: $e->getMessage(), loggable: $this);
            return true;
        }
    }


    private function sendPolicyNotifications($title, $message)
    {
        $notifier_id = Auth::id();

        if ($notifier_id != $this->creator_id) {
            $this->load('creator');
            $this->creator?->pushNotification($title, $message, "sold-policies/" . $this->id);
        }
        $this->load('watchers');
        foreach ($this->watchers as $watcher) {
            if ($notifier_id != $watcher->id) {
                $watcher->pushNotification($title, $message, "sold-policies/" . $this->id);
            }
        }
    }


    ///static functons
    public static function newSoldPolicy(Customer|Corporate $client, $policy_id, $policy_number, $insured_value, $net_rate, $net_premium, $gross_premium, $installements_count, $payment_frequency, Carbon $start, Carbon $expiry, $discount = 0, $offer_id = null, $customer_car_id = null, $car_chassis = null, $car_plate_no = null, $car_engine = null, $is_valid = true, $note = null, $in_favor_to = null, $policy_doc = null, Carbon $issuing_date = null, $renewal_policy_id = null, $sales_id = null, $origin_discount = 0): self|bool
    {
        $created_at = $issuing_date ? $issuing_date->format('Y-m-d') : (Carbon::now()->format('Y-m-d'));
        $newSoldPolicy = new self([
            'creator_id' => Auth::id() ?? ($sales_id ?? 1),
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
            'origin_discount'   => $origin_discount,
            'note'          => $note,
            'in_favor_to'   => $in_favor_to,
            'policy_doc'    => $policy_doc,
            'created_at'    => $created_at,
            "renewal_policy_id" =>  $renewal_policy_id,
        ]);
        $newSoldPolicy->client()->associate($client);
        try {
            $newSoldPolicy->save(['timestamps' => false]);
            AppLog::info("New Sold Policy", loggable: $newSoldPolicy);
            return $newSoldPolicy;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create Sold Policy", desc: $e->getMessage());
            return false;
        }
    }

    public static function exportOutstanding(
        $search = null,
        $commission_outstanding = false,
        $client_outstanding = false,
        $invoice_outstanding = false,
        ?Carbon $start_from = null,
        ?Carbon $start_to = null,
        ?array $company_ids = [],
        ?Carbon $payment_from = null,
        ?Carbon $payment_to = null,
        $has_invoice = null,
        $invoice_payment_from = null,
        $invoice_payment_to = null,
        $invoice_paid = null

    ) {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('viewCommission', self::class)) abort(403, 'Unauthorized');

        $data = self::outstandingPolicies(
            $search,
            $commission_outstanding,
            $client_outstanding,
            $invoice_outstanding,
            $start_from,
            $start_to,
            $company_ids,
            $payment_from,
            $payment_to,
            $has_invoice,
            $invoice_payment_from,
            $invoice_payment_to,
            $invoice_paid,
        )->get();

        $template = IOFactory::load(resource_path('import/sold_policies_outstanding_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();


        $i = 2;
        foreach ($data as $policy) {
            $activeSheet->getCell('A' . $i)->setValue($policy->policy->company->name . ' - ' . $policy->policy->name);
            $activeSheet->getCell('B' . $i)->setValue($policy->gross_premium);
            $activeSheet->getCell('C' . $i)->setValue($policy->net_premium);
            $activeSheet->getCell('D' . $i)->setValue(Carbon::parse($policy->expiry)->format('d-m-Y'));
            $activeSheet->getCell('E' . $i)->setValue(Carbon::parse($policy->start)->format('d-m-Y'));
            $activeSheet->getCell('F' . $i)->setValue(Carbon::parse($policy->client_payment_date)->format('d-m-Y'));
            $activeSheet->getCell('G' . $i)->setValue($policy->policy_number);
            $activeSheet->getCell('H' . $i)->setValue($policy->client?->name);
            $activeSheet->getCell('I' . $i)->setValue($policy->after_tax_comm);
            $activeSheet->getCell('J' . $i)->setValue($policy->total_policy_comm);
            $activeSheet->getCell('K' . $i)->setValue($policy->total_comp_paid);
            $activeSheet->getCell('L' . $i)->setValue($policy->total_comp_paid - $policy->tax_amount);
            $activeSheet->getCell('M' . $i)->setValue(($policy->after_tax_comm - $policy->tax_amount) - ($policy->total_comp_paid - $policy->tax_amount));
            $activeSheet->getCell('N' . $i)->setValue($policy->last_company_comm_payment ? \Carbon\Carbon::parse($policy->last_company_comm_payment?->created_at)->format('d-m-Y') : 'N/A');
            $activeSheet->getCell('O' . $i)->setValue($policy->last_company_comm_payment?->invoice?->serial);
            $activeSheet->getCell('P' . $i)->setValue($policy->last_company_comm_payment?->payment_date ? \Carbon\Carbon::parse($policy->last_company_comm_payment->payment_date)->format('d-m-Y') : 'N/A');
            $activeSheet->getCell('Q' . $i)->setValue($policy->last_company_comm_payment?->status ?? 'N/A');
            $i++;
        }
        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "policies_outstanding_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public static function exportReport(?Carbon $start_from = null, ?Carbon $start_to = null, ?Carbon $expiry_from = null, ?Carbon $expiry_to = null, ?array $creator_ids = [], ?string $line_of_business = null, ?float $value_from = null, ?float $value_to = null, ?float $net_premium_to = null, ?float $net_premium_from = null, ?array $brand_ids = null, ?array $company_ids = null,  ?array $policy_ids = null, ?bool $is_valid = null, ?bool $is_paid = null, ?string $searchText = null, ?bool $is_renewal = null, ?int $main_sales_id = null, ?Carbon $issued_from = null, ?Carbon $issued_to = null, ?array $comm_profile_ids = [], ?bool $is_welcomed = null, ?bool $is_penalized = null, ?bool $is_cancelled = null, ?Carbon $paid_from = null, ?Carbon $paid_to = null, ?Carbon $cancel_time_from = null, ?Carbon $cancel_time_to = null, ?Carbon $bank_payment_time_from = null, ?Carbon $bank_payment_time_to = null)
    {
        $policies = self::report($start_from, $start_to, $expiry_from, $expiry_to, $creator_ids, $line_of_business, $value_from, $value_to, $net_premium_to, $net_premium_from, $brand_ids,  $company_ids,   $policy_ids, $is_valid, $is_paid, $searchText, $is_renewal, $main_sales_id, $issued_from, $issued_to, $comm_profile_ids, $is_welcomed, $is_penalized, $is_cancelled, $paid_from, $paid_to, $cancel_time_from, $cancel_time_to, $bank_payment_time_from, $bank_payment_time_to)->get();

        $template = IOFactory::load(resource_path('import/sold_policies_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        /** @var User */
        $user = Auth::user();
        if ($user->can('viewCommission', self::class)) {
            $activeSheet->getCell('I1')->setValue("Expected Comm.");
            $activeSheet->getCell('J1')->setValue("Collected Comm.");
        }
        foreach ($policies as $policy) {
            $activeSheet->getCell('A' . $i)->setValue($policy->policy->company->name);
            $activeSheet->getCell('B' . $i)->setValue($policy->policy->name);
            $activeSheet->getCell('C' . $i)->setValue(Carbon::parse($policy->start)->format('d-m-Y'));
            $activeSheet->getCell('D' . $i)->setValue(Carbon::parse($policy->expiry)->format('d-m-Y'));
            $activeSheet->getCell('E' . $i)->setValue($policy->policy_number);
            $activeSheet->getCell('F' . $i)->setValue($policy->client?->name);
            $activeSheet->getCell('G' . $i)->setValue($policy->is_valid ? "Valid" : '');
            $activeSheet->getCell('H' . $i)->setValue($policy->is_paid ? 'Paid' : '');
            if ($user->can('viewCommission', self::class)) {
                $activeSheet->getCell('I' . $i)->setValue($policy->after_tax_comm);
                $activeSheet->getCell('J' . $i)->setValue($policy->total_policy_comm);
                $activeSheet->getCell('K' . $i)->setValue($policy->total_comp_paid);
                $activeSheet->getCell('L' . $i)->setValue($policy->total_comp_paid - $policy->tax_amount);
                $activeSheet->getCell('M' . $i)->setValue(($policy->after_tax_comm - $policy->tax_amount) - ($policy->total_comp_paid - $policy->tax_amount));
            }

            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "policies_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public static function exportHay2aReport(
        ?Carbon $start_from = null,
        ?Carbon $start_to = null,
        ?Carbon $expiry_from = null,
        ?Carbon $expiry_to = null,
        ?array $creator_ids = [],
        ?string $line_of_business = null,
        ?float $value_from = null,
        ?float $value_to = null,
        ?float $net_premium_to = null,
        ?float $net_premium_from = null,
        ?array $brand_ids = null,
        ?array $company_ids = null,
        ?array $policy_ids = null,
        ?bool $is_valid = null,
        ?bool $is_paid = null,
        ?string $searchText = null,
        ?bool $is_renewal = null,
        ?int $main_sales_id = null,
        ?Carbon $issued_from = null,
        ?Carbon $issued_to = null,
        ?array $comm_profile_ids = [],
        ?bool $is_welcomed = null,
        ?bool $is_penalized = null,
        ?bool $is_cancelled = null,
        ?Carbon $paid_from = null,
        ?Carbon $paid_to = null,
        ?Carbon $cancel_time_from = null,
        ?Carbon $cancel_time_to = null
    ) {
        $policies = self::report($start_from, $start_to, $expiry_from, $expiry_to, $creator_ids, $line_of_business, $value_from, $value_to, $net_premium_to, $net_premium_from, $brand_ids,  $company_ids,   $policy_ids, $is_valid, $is_paid, $searchText, $is_renewal, $main_sales_id, $issued_from, $issued_to, $comm_profile_ids, $is_welcomed, $is_penalized, false, $paid_from, $paid_to, $cancel_time_from, $cancel_time_to)->get();

        $cancelledPolicies = self::report($start_from, $start_to, $expiry_from, $expiry_to, $creator_ids, $line_of_business, $value_from, $value_to, $net_premium_to, $net_premium_from, $brand_ids,  $company_ids,   $policy_ids, $is_valid, $is_paid, $searchText, $is_renewal, $main_sales_id, $issued_from, $issued_to, $comm_profile_ids, $is_welcomed, $is_penalized, true, $paid_from, $paid_to, $issued_from, $issued_to)->get();

        $edittedPoliciesIDs = TaskAction::changedSoldPoliciesIDs($issued_from, $issued_to);
        $edittedPolicies = self::whereIn('id', $edittedPoliciesIDs)->get();
        $edittedPolicies->each(function ($policy) {
            $policy->editted = true;
        });

        ///merge policies and editted policies
        foreach ($edittedPolicies as $policy) {
            if ($policies->contains(function ($p) use ($policy) {
                return $p->policy_number == $policy->policy_number && $p->id != $policy->id;
            })) {
                $policy->is_duplicate = true;
            }
            $policies->push($policy);
        }

        $policies = $policies->sortBy([['created_at', 'desc'], ['policy_number', 'asc']]);

        $template = IOFactory::load(resource_path('import/sold_policies_hay2a_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 4;
        /** @var User */
        $user = Auth::user();
        $activeSheet->getCell('D1')->setValue("سجل الوثائق والعمولات عن عام {$issued_from->format('Y')}");
        $activeSheet->getCell('D2')->setValue("  الفترة من {$issued_from->format('d / m / Y')} حتى {$issued_to->format('d / m / Y')}   ");

        $k = 1;
        foreach ($policies as $policy) {
            $activeSheet->getCell('A' . $i)->setValue($k++);
            $activeSheet->getCell('B' . $i)->setValue($policy->client?->full_name);
            $activeSheet->getCell('C' . $i)->setValue($policy->client?->address_city);
            $activeSheet->getCell('D' . $i)->setValue($policy->policy_number);
            $activeSheet->getCell('E' . $i)->setValue(Policy::LINES_OF_BUSINESS_ARBC[$policy->policy->business]);
            $activeSheet->getCell('F' . $i)->setValue(Carbon::parse($policy->created_at)->format('d-m-Y'));
            $activeSheet->getCell('G' . $i)->setValue($policy->policy->company->name);
            $activeSheet->getCell('H' . $i)->setValue($policy->insured_value);
            $activeSheet->getCell('I' . $i)->setValue($policy->totalClientPaidBetween($issued_from, $issued_to));
            $activeSheet->getCell('J' . $i)->setValue(OfferOption::PAYMENT_FREQS_ARBC[$policy->payment_frequency]);
            $activeSheet->getCell('K' . $i)->setValue(Carbon::parse($policy->start)->format('d-m-Y'));
            if ($user->can('viewCommission', self::class)) {
                $activeSheet->getCell('L' . $i)->setValue(round($policy->totalPaidBetween($issued_from, $issued_to) - $policy->totalTaxBetween($issued_from, $issued_to), 2)); //total_policy_comm
                // $activeSheet->getCell('J' . $i)->setValue($policy->total_comp_paid);
            }
            $activeSheet->getCell('M' . $i)->setValue($policy->editted ? "ملحق تعديل" : "");
            $activeSheet->getCell('N' . $i)->setValue(Carbon::parse($policy->expiry)->format('d-m-Y'));
            $activeSheet->getCell('O' . $i)->setValue("ريمون حنا بطرس");
            $i++;
        }

        $cancelledSheet = $newFile->getSheet(1);

        $i = 4;
        /** @var User */
        $user = Auth::user();
        $cancelledSheet->getCell('D1')->setValue("سجل الالغائات عن عام {$issued_from->format('Y')}");
        $cancelledSheet->getCell('D2')->setValue("  الفترة من {$issued_from->format('d / m / Y')} حتى {$issued_to->format('d / m / Y')}   ");

        $k = 1;
        foreach ($cancelledPolicies as $policy) {
            $cancelledSheet->getCell('A' . $i)->setValue($k++);
            $cancelledSheet->getCell('B' . $i)->setValue($policy->policy_number);
            $cancelledSheet->getCell('C' . $i)->setValue(Policy::LINES_OF_BUSINESS_ARBC[$policy->policy->business]);
            $cancelledSheet->getCell('D' . $i)->setValue(Carbon::parse($policy->start)->format('d-m-Y'));
            $cancelledSheet->getCell('E' . $i)->setValue($policy->policy->company->name);
            $cancelledSheet->getCell('F' . $i)->setValue(Carbon::parse($policy->client_payment_date)->format('d-m-Y'));
            $cancelledSheet->getCell('G' . $i)->setValue(OfferOption::PAYMENT_FREQS_ARBC[$policy->payment_frequency]);
            $cancelledSheet->getCell('H' . $i)->setValue($policy->client?->full_name);
            if ($user->can('viewCommission', self::class)) {
                $cancelledSheet->getCell('J' . $i)->setValue($policy->is_duplicate ? 0 : round($policy->totalPaidBetween($issued_from, $issued_to) - $policy->totalTaxBetween($issued_from, $issued_to), 2)); //total_policy_comm
                // $activeSheet->getCell('J' . $i)->setValue($policy->total_comp_paid);
            }
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "policies_gov_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function totalPaidBetween(Carbon $from, Carbon $to)
    {
        return $this->company_comm_payments()
            ->whereBetween('payment_date', [$from, $to])
            ->where('status', 'Paid')
            ->sum('amount');
    }

    public function totalTaxBetween(Carbon $from, Carbon $to)
    {
        return $this->company_comm_payments()
            ->whereBetween('payment_date', [$from, $to])
            ->where('status', 'Paid')
            ->sum('tax_amount');
    }

    public function totalClientPaidBetween(Carbon $from, Carbon $to)
    {
        return $this->client_payments()
            ->whereBetween('payment_date', [$from, $to])
            ->where('status', 'Paid')
            ->sum('amount');
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
                    if ($tel) $tmpclient?->addPhone(Phone::TYPE_MOBILE, $tel, true);
                } else {
                    $name_array = str_split($full_name);
                    $middle_name = "";
                    for ($j = 1; $j < count($name_array); $j++) $middle_name .= "$name_array[$j] ";
                    $tmpClient = Corporate::newCorporate(
                        owner_id: 10,
                        name: $full_name
                    );
                    if ($tel) $tmpclient?->addPhone(Phone::TYPE_MOBILE, $tel, true);
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

    public static function importData2($file)
    {
        $spreadsheet = IOFactory::load($file);
        if (!$spreadsheet) {
            throw new Exception('Failed to read files content');
        }
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();
        $rows_not_added = [];
        for ($i = 2; $i <= $highestRow; $i++) {
            try {
                //client data
                $full_name = $activeSheet->getCell('F' . $i)->getValue();
                $phone = $activeSheet->getCell('J' . $i)->getValue();

                //policy data
                $company_name = $activeSheet->getCell('A' . $i)->getValue();
                $policy_name = $activeSheet->getCell('B' . $i)->getValue();
                $is_corporate = $activeSheet->getCell('C' . $i)->getValue() == "Corp";
                $is_renewal = $activeSheet->getCell('D' . $i)->getValue() == "Renewal";

                if (!$activeSheet->getCell('G' . $i)) {
                    Log::warning("Row#$i missed, failed to get start date");
                    continue;
                }
                if (!$activeSheet->getCell('I' . $i)) {
                    Log::warning("Row#$i missed, failed to get end date");
                    continue;
                }

                //sold policy data
                $policy_number = $activeSheet->getCell('E' . $i)->getValue();
                $start_date =  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) $activeSheet->getCell('G' . $i)->getValue());
                $issued_date =  $start_date;
                $end_date =  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) $activeSheet->getCell('I' . $i)->getValue());

                $net_premium = $activeSheet->getCell('M' . $i)->getValue();
                $gross_premium = $activeSheet->getCell('N' . $i)->getValue();
                $insured_value = $activeSheet->getCell('U' . $i)->getValue();

                $chassis = $activeSheet->getCell('V' . $i)->getValue();
                $car = $activeSheet->getCell('X' . $i)->getValue();
                $year = $activeSheet->getCell('W' . $i)->getValue();
                $brand = $activeSheet->getCell('Y' . $i)->getValue();

                $sales1 = str_replace("sales in", "sales_in", $activeSheet->getCell('R' . $i)->getValue());
                $sales2 = str_replace("sales in", "sales_in", $activeSheet->getCell('S' . $i)->getValue());
                $sales3 = str_replace("sales in", "sales_in", $activeSheet->getCell('T' . $i)->getValue());
                $salesOut1 = null;
                $salesOut2 = null;
                $salesOut3 = null;
                $salesIn1 = null;
                $salesIn2 = null;
                $salesIn3 = null;

                /** @var CommProfile */
                if ($sales1) $salesOut1 = CommProfile::bytitle(trim($sales1))->salesOut()->first();
                if (!$salesOut1) $salesIn1 = CommProfile::bytitle(trim($sales1))->first();

                /** @var CommProfile */
                if ($sales2) $salesOut2 = CommProfile::bytitle(trim($sales2))->salesOut()->first();
                if (!$salesOut2) $salesIn2 = CommProfile::bytitle(trim($sales2))->first();

                /** @var CommProfile */
                if ($sales3) $salesOut3 = CommProfile::bytitle(trim($sales3))->salesOut()->first();
                if (!$salesOut3) $salesIn3 = CommProfile::bytitle(trim($sales3))->first();

                $discount = $activeSheet->getCell('AB' . $i)->getValue();

                $tmpClient = null;
                $client_payment_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) ($activeSheet->getCell('L' . $i)->getValue()));

                $name_array = explode(" ", $full_name);
                $middle_name = "";
                for ($j = 1; $j < count($name_array) - 1; $j++) $middle_name .= "$name_array[$j] ";

                $duplicatePolicy = self::getIfExists($policy_number, new Carbon($start_date));

                if ($duplicatePolicy) {
                    $duplicatePolicy->editInfo(
                        new Carbon($start_date),
                        new Carbon($end_date),
                        $policy_number,
                        $chassis,
                        issuing_date: new Carbon($issued_date)
                    );
                    $duplicatePolicy->updatePaymentInfo(
                        $insured_value,
                        $insured_value ? (($net_premium / $insured_value) * 100) : 0,
                        $net_premium,
                        $gross_premium,
                        1,
                        OfferOption::PAYMENT_FREQ_YEARLY,
                        $discount,
                        0
                    );
                    if ($client_payment_date) $duplicatePolicy->setPaid(true, new Carbon($client_payment_date));


                    $duplicatePolicy->load('policy');
                    if ($salesOut1 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesOut1->id)->count() == 0) {

                        $conf = $salesOut1->getValidDirectCommissionConf($duplicatePolicy->policy);

                        if ($conf) {
                            $duplicatePolicy->addSalesCommission($salesOut1->title, $conf->from, $conf->percentage, $salesOut1->id, "Added for direct commission during migration", true);
                        }
                    } else if ($salesIn1 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesIn1->id)->count() == 0) {

                        $duplicatePolicy->addSalesCommission($salesIn1->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn1->id, "Added for target commission during migration", true);
                    }

                    if ($salesOut2 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesOut2->id)->count() == 0) {
                        $conf = $salesOut2->getValidDirectCommissionConf($duplicatePolicy->policy);
                        if ($conf) {
                            $duplicatePolicy->addSalesCommission($salesOut2->title, $conf->from, $conf->percentage, $salesOut2->id, "Added for direct commission during migration", true);
                        }
                    } else if ($salesIn2 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesIn2->id)->count() == 0) {
                        $duplicatePolicy->addSalesCommission($salesIn2->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn2->id, "Added for target commission during migration", true);
                    }

                    if ($salesOut3 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesOut3->id)->count() == 0) {
                        $conf = $salesOut3->getValidDirectCommissionConf($duplicatePolicy->policy);
                        if ($conf) {
                            $duplicatePolicy->addSalesCommission($salesOut3->title, $conf->from, $conf->percentage, $salesOut3->id, "Added for direct commission during migration", true);
                        }
                    } else if ($salesIn3 && $duplicatePolicy->sales_comms()->where('comm_profile_id', $salesIn3->id)->count() == 0) {
                        $duplicatePolicy->addSalesCommission($salesIn3->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn3->id, "Added for target commission during migration", true);
                    }

                    Log::warning("Row#$i edited");
                } else {

                    $policy = Policy::getPolicyByName($company_name, $policy_name);


                    if (!$policy) {
                        Log::warning("Row#$i missed, failed to get policy");
                        array_push($rows_not_added, [$i, "Row#$i missed, failed to get policy"]);
                        continue;
                    }
                    if ($is_corporate) {
                        $tmpClient = Corporate::newCorporate(
                            owner_id: $salesIn1?->user_id ?? 1,
                            name: $full_name
                        );
                    } else {
                        $tmpClient = Customer::newCustomer(
                            owner_id: $salesIn1?->user_id ?? 1,
                            first_name: $name_array[0],
                            last_name: $name_array[count($name_array) - 1],
                            middle_name: trim($middle_name),
                            gender: Customer::GENDER_MALE,
                            email: "test@mail"
                        );
                    }
                    if ($phone) $tmpclient?->addPhone(Phone::TYPE_MOBILE, $phone, true);

                    if (is_numeric($net_premium)) {
                        $soldP = SoldPolicy::newSoldPolicy(
                            client: $tmpClient,
                            policy_id: $policy->id,
                            policy_number: $policy_number,
                            insured_value: $insured_value ?? 0,
                            net_rate: $insured_value ? (($net_premium / $insured_value) * 100) : 0,
                            net_premium: $net_premium ?? 0,
                            gross_premium: $gross_premium ?? 0,
                            installements_count: 1,
                            payment_frequency: OfferOption::PAYMENT_FREQ_YEARLY,
                            start: new Carbon($start_date),
                            expiry: new Carbon($end_date),
                            issuing_date: new Carbon($issued_date),
                            car_chassis: $chassis,
                            discount: $discount ?? 0,
                            note: $car . ' / ' . $year . ' / ' . $brand,
                            sales_id: $salesIn1?->user_id ?? 1,
                        );
                        if (!$soldP) {
                            Log::warning("Couldn't create Row#$i");
                            continue;
                        }

                        $soldP->setPaid(true, new Carbon($client_payment_date));
                        $soldP->load('policy');

                        if ($salesOut1) {
                            $conf = $salesOut1->getValidDirectCommissionConf($soldP->policy);
                            if ($conf) {
                                $soldP->addSalesCommission($salesOut1->title, $conf->from, $conf->percentage, $salesOut1->id, "Added for direct commission during migration", true);
                            }
                        } else if ($salesIn1) {
                            $soldP->addSalesCommission($salesIn1->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn1->id, "Added for target commission during migration", true);
                        }

                        if ($salesOut2) {
                            $conf = $salesOut2->getValidDirectCommissionConf($soldP->policy);
                            if ($conf) {
                                $soldP->addSalesCommission($salesOut2->title, $conf->from, $conf->percentage, $salesOut2->id, "Added for direct commission during migration", true);
                            }
                        } else if ($salesIn2) {
                            $soldP->addSalesCommission($salesIn2->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn2->id, "Added for target commission during migration", true);
                        }
                        if ($salesOut3) {
                            $conf = $salesOut3->getValidDirectCommissionConf($soldP->policy);
                            if ($conf) {
                                $soldP->addSalesCommission($salesOut3->title, $conf->from, $conf->percentage, $salesOut3->id, "Added for direct commission during migration", true);
                            }
                        } else if ($salesIn3) {
                            $soldP->addSalesCommission($salesIn3->title, CommProfileConf::FROM_NET_COMM, 0, $salesIn3->id, "Added for target commission during migration", true);
                        }
                        Log::warning("Row#$i added");
                    } else {
                        Log::warning("Invalid insured / net prem on Row#$i");
                        array_push($rows_not_added, [$i, "Invalid insured / net prem on Row#$i"]);
                    }
                }
            } catch (Exception $e) {
                Log::warning("Row#$i crashed");
                Log::warning($e->getMessage());
                Log::warning($e->getFile() . " " . $e->getLine());
            }
        }
        foreach ($rows_not_added as $i => $r) {
            echo $r[0] . " => " . $r[1] . "\n";
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
                $car_id = $client?->setDocInfo($full_name, $national_id, $address, $tel1, $tel2, $car, $model_year);
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
                        if ($tel1) $tmpclient?->addPhone(Phone::TYPE_MOBILE, $tel1, true);
                        if ($tel2) $tmpclient?->addPhone(Phone::TYPE_HOME, $tel2, false);
                        if ($address) $tmpclient?->addAddress(type: Address::TYPE_HOME, line_1: $address, country: "Egypt");
                        if ($car) $tmpCar = $tmpclient?->setCars([[
                            "car_id"        => $car->id,
                            "model_year"    => $model_year
                        ]]);
                    } else {
                        $tmpClient = Corporate::newCorporate(
                            owner_id: 10,
                            name: $full_name
                        );
                        if ($tel1) $tmpclient?->addPhone(CorporatesPhone::TYPE_WORK, $tel1, true);
                        if ($tel2) $tmpclient?->addPhone(CorporatesPhone::TYPE_WORK, $tel2, false);
                        if ($address) $tmpclient?->addAddress(type: CorporatesAddress::TYPE_HQ, line_1: $address, country: "Egypt");
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
                    } else Log::warning("Invalid insured / net prem on Row#$i");
                } catch (Exception $e) {
                    Log::warning("Row#$i crashed");
                    Log::warning($e->getMessage());
                    Log::warning($e->getFile() . " " . $e->getLine());
                }
            }
        }
    }

    public static function getIfExists($policy_number, Carbon $start): self|null
    {
        return self::whereMonth('start', $start->format('m'))
            ->whereYear('start', $start->format('Y'))
            ->where('policy_number', $policy_number)
            ->first();
    }

    public static function checkOverlap($policy_number, Carbon $from, Carbon $to)
    {
        return DB::table('sold_policies')->selectRaw('count(*) as found')
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start', [
                    $from->format('Y-m-d H:i:s'),
                    $to->format('Y-m-d H:i:s'),
                ])->orWhereBetween('expiry', [
                    $from->format('Y-m-d H:i:s'),
                    $to->format('Y-m-d H:i:s'),
                ]);
            })->where('policy_number', $policy_number)
            ->first()?->found;
    }

    ///scopes
    public function scopeSearchByPolicyNumber($query, $searchText)
    {
        return $query->where("policy_number", 'LIKE', "%$searchText%");
    }

    public function scopeNotCancelled($query)
    {
        return $query->whereNull("cancellation_time");
    }

    public function scopeNotExpired($query)
    {
        return $query->where("expiry", ">", Carbon::now()->format('Y-m-d'));
    }

    public function scopeUserData(
        $query,
        $searchText = null,
        $is_expiring = false,
        $is_commission_outstanding = false, //Client Payment Outstanding
        $is_client_outstanding = false, //Policy Outstanding
        $is_invoice_outstanding = false, //Invoice Outstanding
        $group_data = true
    ) {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('sold_policies.*', 'offers.is_renewal')
            ->join('users', "sold_policies.creator_id", '=', 'users.id')
            ->leftjoin('policy_watchers', 'policy_watchers.sold_policy_id', '=', 'sold_policies.id')
            ->leftjoin('offers', 'sold_policies.offer_id', '=', 'offers.id')
            ->leftjoin('client_payments', 'client_payments.sold_policy_id', '=', 'sold_policies.id')
            ->when($group_data, function ($q) {
                $q->groupBy('sold_policies.id');
            })
            ->orderByDesc('sold_policies.created_at');

        // if (!($loggedInUser->is_admin
        //     || (($loggedInUser->is_operations || $loggedInUser->is_finance) && ($searchText || $is_expiring)))) {
        if (!($loggedInUser->is_admin || $loggedInUser->is_operations || $loggedInUser->is_any_finance)) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->whereIn('users.manager_id', $loggedInUser->children_ids_array)
                    ->orwhere('users.id', $loggedInUser->id)
                    ->orwhere('sold_policies.main_sales_id', $loggedInUser->id)
                    ->orwhere('client_payments.assigned_to', $loggedInUser->id)
                    ->orwhere('policy_watchers.user_id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('sold_policies.client_id', '=', 'corporates.id')
                    ->where('sold_policies.client_type', Corporate::MORPH_TYPE)
                    ->join('corporate_phones', 'corporate_phones.corporate_id', '=', 'corporates.id');
            })->leftjoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', Customer::MORPH_TYPE)
                    ->join('customer_phones', 'customer_phones.customer_id', '=', 'customers.id');
            });

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
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
                    // }
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

        $query->when($is_commission_outstanding, function ($q) {
            $q->whereRaw("total_comp_paid < after_tax_comm")->only2025();
        });
        $query->when($is_client_outstanding, function ($q) {
            $q->whereRaw("total_client_paid < gross_premium")->fromOct2024();
        });

        $query->when($is_invoice_outstanding, function ($q) {
            if (!Helpers::joined($q, 'company_comm_payments')) {
                $q->join('company_comm_payments', 'company_comm_payments.sold_policy_id', 'sold_policies.id');
            }
            $q->havingRaw("total_comp_paid < SUM(company_comm_payments.amount)")->fromOct2024();
        });

        return $query->orderBy("sold_policies.start");
    }

    public function scopeFromOct2024($query)
    {
        return $query->where('sold_policies.created_at', ">=", "2024-10-01 00:00:00");
    }

    public function scopeOnly2025($query)
    {
        return $query->where('sold_policies.created_at', ">=", "2024-12-01 00:00:00");
    }

    public function scopeFromTo($query, Carbon $from, Carbon $to)
    {
        return $query->where(function ($query) use ($from, $to) {
            $query->whereBetween("sold_policies.start", [$from->format('Y-m-d'), $to->format('Y-m-d')])
                ->orWhereNull("sold_policies.start");
        });
    }

    public function scopeOutstandingPolicies(
        $query,
        $search = null,
        $commission_outstanding = false,
        $client_outstanding = false,
        $invoice_outstanding = false,
        $start_from = null,
        $start_to = null,
        $company_ids = [],
        $payment_from = null,
        $payment_to = null,
        $has_invoice = null,
        $invoice_payment_from = null,
        $invoice_payment_to = null,
        $invoice_paid = null,
        bool $group_data = true
    ) {
        return $query->userData(
            searchText: $search,
            is_commission_outstanding: $commission_outstanding,
            is_client_outstanding: $client_outstanding,
            is_invoice_outstanding: $invoice_outstanding,
            group_data: $group_data
        )
            ->when($start_from && $start_to, function ($q) use ($start_from, $start_to) {
                $q->fromTo($start_from, $start_to);
            })
            ->when($payment_from && $payment_to, function ($q) use ($payment_from, $payment_to) {
                $q->whereNotNull('client_payment_date')
                    ->whereBetween('client_payment_date', [
                        $payment_from->format('Y-m-d 00:00:00'),
                        $payment_to->format('Y-m-d 23:59:59')
                    ]);
            })
            ->when($invoice_payment_from && $invoice_payment_to, function ($q) use ($invoice_payment_from, $invoice_payment_to) {
                $q->whereHas('company_comm_payments', function ($query) use ($invoice_payment_from, $invoice_payment_to) {
                    $query->whereNotNull('payment_date')
                        ->whereBetween('payment_date', [
                            $invoice_payment_from->format('Y-m-d 00:00:00'),
                            $invoice_payment_to->format('Y-m-d 23:59:59')
                        ]);
                });
            })
            ->when(!is_null($has_invoice), function ($q) use ($has_invoice) {
                $q->hasInvoice($has_invoice);
            })
            ->when(!is_null($invoice_paid), function ($q) use ($invoice_paid) {
                $q->invoicePaid($invoice_paid);
            })
            ->when($company_ids, fn($q) => $q->byCompanyIDs($company_ids))
            ->with('last_company_comm_payment', 'last_company_comm_payment.invoice');
    }

    public function scopeReport(
        $query,
        ?Carbon $start_from = null,
        ?Carbon $start_to = null,
        ?Carbon $expiry_from = null,
        ?Carbon $expiry_to = null,
        ?array $creator_ids = [],
        ?string $line_of_business = null,
        ?float $value_from = null,
        ?float $value_to = null,
        ?float $net_premium_to = null,
        ?float $net_premium_from = null,
        ?array $brand_ids = null,
        ?array $company_ids = null,
        ?array $policy_ids = null,
        ?bool $is_valid = null,
        ?bool $is_paid = null,
        ?string $searchText = null,
        ?bool $is_renewal = null,
        ?int $main_sales_id = null,
        ?Carbon $issued_from = null,
        ?Carbon $issued_to = null,
        ?array $comm_profile_ids = [],
        ?bool $is_welcomed = null,
        ?bool $is_penalized = null,
        ?bool $is_cancelled = null,
        ?Carbon $paid_from = null,
        ?Carbon $paid_to = null,
        ?Carbon $cancel_time_from = null,
        ?Carbon $cancel_time_to = null,
        ?Carbon $bank_payment_time_from = null,
        ?Carbon $bank_payment_time_to = null
    ) {
        $query->userData($searchText)
            ->when($start_from, function ($q, $v) {
                $q->where('start', ">=", $v->format('Y-m-d 00:00:00'));
            })->when($start_to, function ($q, $v) {
                $q->where('start', "<=", $v->format('Y-m-d 23:59:59'));
            })->when($issued_from, function ($q, $v) {
                $q->where(function ($qq) use ($v) {
                    $qq->where('sold_policies.created_at', ">=", $v->format('Y-m-d 00:00:00'))
                        ->orWhere('sold_policies.cancellation_time', ">=", $v->format('Y-m-d 00:00:00'));
                });
            })->when($issued_to, function ($q, $v) {
                $q->where(function ($qq) use ($v) {
                    $qq->where('sold_policies.created_at', "<=", $v->format('Y-m-d 23:59:59'))
                        ->orWhere('sold_policies.cancellation_time', "<=", $v->format('Y-m-d 23:59:59'));
                });
            })->when($cancel_time_from, function ($q, $v) {
                $q->where('sold_policies.cancellation_time', ">=", $v->format('Y-m-d 00:00:00'));
            })->when($cancel_time_to, function ($q, $v) {
                $q->where('sold_policies.cancellation_time', "<=", $v->format('Y-m-d 23:59:59'));
            })->when($expiry_from, function ($q, $v) {
                $q->where('expiry', ">=", $v->format('Y-m-d 00:00:00'));
            })->when($expiry_to, function ($q, $v) {
                $q->where('expiry', "<=", $v->format('Y-m-d 23:59:59'));
            })->when($paid_from, function ($q, $v) {
                $q->where('client_payment_date', ">=", $v->format('Y-m-d 00:00:00'));
            })->when($paid_to, function ($q, $v) {
                $q->where('client_payment_date', "<=", $v->format('Y-m-d 23:59:59'));
            })->when($brand_ids, function ($q, $v) {
                $q->join('customer_cars', 'customer_car_id', '=', 'customer_cars.id')
                    ->join('cars', 'cars.id', '=', 'customer_cars.car_id')
                    ->join('car_models', 'car_models.id', '=', 'cars.car_model_id')
                    ->whereIn('brand_id', $v);
            })->when(count($creator_ids) > 0, function ($q) use ($creator_ids) {
                $q->whereIn('sold_policies.creator_id', $creator_ids);
            })->when($main_sales_id, function ($q, $v) {
                $q->where('main_sales_id', "=", $v);
            })->when($is_valid !== null, function ($q, $v) use ($is_valid) {
                $q->where('is_valid', "=", $is_valid);
            })->when($is_renewal !== null, function ($q, $v) use ($is_renewal) {
                $q->where('offers.is_renewal', "=", $is_renewal);
            })->when($is_penalized !== null, function ($q) use ($is_penalized) {
                $q->where('sold_policies.is_penalized', "=", $is_penalized);
            })->when($is_cancelled !== null, function ($q) use ($is_cancelled) {
                if ($is_cancelled) $q->whereNotNull('cancellation_time');
                else $q->whereNull('cancellation_time');
            })->when($is_welcomed !== null, function ($q, $v) use ($is_welcomed) {
                if (!Helpers::joined($q, 'customers')) {
                    $q->join('customers', function ($qq) {
                        $qq->on('sold_policies.client_id', '=', 'customers.id')
                            ->where('sold_policies.client_type', Customer::MORPH_TYPE);
                    });
                }
                $q->where('customers.is_welcomed', "=", $is_welcomed);
            })->when($is_paid !== null, function ($q, $v) use ($is_paid) {
                $q->where('is_paid', "=", $is_paid);
            })->when($value_from, function ($q, $v) {
                $q->where('insured_value', ">=", $v);
            })->when($value_to, function ($q, $v) {
                $q->where('insured_value', "<=", $v);
            })->when($net_premium_from, function ($q, $v) {
                $q->where('net_premium', ">=", $v);
            })->when($net_premium_to, function ($q, $v) {
                $q->where('net_premium', "<=", $v);
            })->when($line_of_business || $company_ids || $policy_ids, function ($q) use ($line_of_business, $company_ids, $policy_ids) {
                $q->join('policies', 'policies.id', '=', 'sold_policies.policy_id')
                    ->when($line_of_business, function ($qq, $vv) {
                        $qq->where('policies.business', $vv);
                    })->when($company_ids, function ($qq, $vv) {
                        $qq->whereIn('policies.company_id', $vv);
                    })->when($policy_ids, function ($qq, $vv) {
                        $qq->whereIn('policies.id', $vv);
                    });
            })->when(count($comm_profile_ids), function ($qq) use ($comm_profile_ids) {
                $qq->join('sales_comms', 'sales_comms.sold_policy_id', '=', 'sold_policies.id')
                    ->whereIn('sales_comms.comm_profile_id', $comm_profile_ids);
            })->when($bank_payment_time_from || $bank_payment_time_to, function ($q) use ($bank_payment_time_from, $bank_payment_time_to) {
                $q->join('company_comm_payments', 'company_comm_payments.sold_policy_id', '=', 'sold_policies.id')
                    ->when($bank_payment_time_from, function ($qq) use ($bank_payment_time_from) {
                        $qq->where('company_comm_payments.payment_date', ">=", $bank_payment_time_from->format('Y-m-d 00:00:00'));
                    })->when($bank_payment_time_to, function ($qq) use ($bank_payment_time_to) {
                        $qq->where('company_comm_payments.payment_date', "<=", $bank_payment_time_to->format('Y-m-d 23:59:59'));
                    });
            });

        $query->with('client', 'policy', 'policy.company', 'creator', 'customer_car', "customer_car.car");

        return $query;
    }

    public function scopeByPaid($query, $is_paid)
    {
        return $query->where('sold_policies.is_paid', $is_paid);
    }

    public function scopeByCompanyIDs($query, $company_ids)
    {
        if (!Helpers::joined($query, 'policies')) {
            $query->join('policies', 'policies.id', '=', 'sold_policies.policy_id');
        }
        return $query->whereIn('policies.company_id', $company_ids);
    }

    public function scopeByProfileId($query, $profile_id)
    {
        return $query->join('sales_comms', 'sales_comms.sold_policy_id', '=', 'sold_policies.id')
            ->where('sales_comms.comm_profile_id', $profile_id);
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

    public function scopeUnpaidSum($query)
    {
        return $query->selectRaw('SUM(after_tax_comm - total_comp_paid) as unpaid_sum');
    }

    public function scopeByPolicyNumber($query, $number)
    {
        return $query->where('policy_number', $number);
    }

    public function scopeByOfferID($query, $id)
    {
        return $query->where('offer_id', $id);
    }

    public function scopeByCreators($query, array $creator_ids)
    {
        return $query->whereIn('sold_policies.creator_id', $creator_ids);
    }

    public function scopeByCompany($query, $company_id, $is_paid = null)
    {
        if (!Helpers::joined($query, "policies")) {
            $query->join('policies', 'policies.id', '=', 'sold_policies.policy_id');
        }

        return $query->select('sold_policies.*')
            ->where('policies.company_id', $company_id)
            ->when($is_paid !== null, fn($q) => $is_paid ? $q->whereRaw('sold_policies.total_comp_paid >= sold_policies.after_tax_comm') : $q->whereRaw('sold_policies.total_comp_paid < sold_policies.after_tax_comm'));
    }

    ///attributes
    public function getIsExpiredAttribute()
    {
        $now = Carbon::now();
        return $now->isAfter(new Carbon($this->expiry));
    }

    public function getTotalCommSubtractionsAttribute()
    {
        return $this->sales_out_comm + $this->discount;
    }

    public function getLeftToPayAttribute()
    {
        return $this->gross_premium - $this->total_client_paid;
    }

    public function getHasSalesOutAttribute()
    {
        $this->load('sales_comms', 'sales_comms.comm_profile');
        foreach ($this->sales_comms as $sc) {
            if ($sc->comm_profile->is_sales_out) return true;
        }

        return false;
    }

    public function getinvoicedAmountAttribute()
    {
        $this->load('company_comm_payments');
        return $this->company_comm_payments->where('status', CompanyCommPayment::PYMT_STATE_NEW)->sum('amount');
    }

    public function getCommissionLeftAttribute()
    {
        $this->load('company_comm_payments');
        return $this->after_tax_comm - ($this->company_comm_payments->where('status', CompanyCommPayment::PYMT_STATE_NEW))->sum('amount') - ($this->company_comm_payments->where('status', CompanyCommPayment::PYMT_STATE_PAID))->sum('amount');
    }

    public function getSalesOutsAttribute()
    {
        $this->loadMissing('sales_comms', 'sales_comms.comm_profile');
        $txt = '';
        foreach ($this->sales_comms as $s) {
            if ($s->comm_profile->is_sales_out) {
                $txt .= $s->comm_profile->title . " ";
            }
        }
        return $txt;
    }

    public function getSalesOutCommPaidAttribute()
    {
        $this->loadMissing('sales_comms', 'sales_comms.comm_profile');
        $sum = 0;
        foreach ($this->sales_comms as $s) {
            if ($s->comm_profile->is_sales_out && $s->is_paid) {
                $sum += $s->amount;
            }
        }
        return $sum;
    }

    public function getSalesOutCommPaidDateAttribute()
    {
        $this->loadMissing('sales_comms', 'sales_comms.comm_profile');
        $sum = 0;
        foreach ($this->sales_comms as $s) {
            if ($s->comm_profile->is_sales_out && $s->is_paid) {
                return (new Carbon($s->payment_date))->format('d m Y');
            }
        }
        return "N/A";
    }

    public function getFirstClientPaymentDateAttribute()
    {
        return $this->client_payments()
            ->where('status', ClientPayment::PYMT_STATE_PAID)
            ->orderBy('payment_date', 'asc')
            ->first()
            ?->payment_date ?? null;
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

    public function main_sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'main_sales_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function customer_car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'customer_car_id');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->where('type', Task::TYPE_TASK);
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

    public function last_company_comm_payment(): HasOne
    {
        return $this->hasOne(CompanyCommPayment::class)->whereNot('status', CompanyCommPayment::PYMT_STATE_CANCELLED)->latestOfMany();
    }

    public function client_payments(): HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function sales_comms(): HasMany
    {
        return $this->hasMany(SalesComm::class);
    }

    public function active_sales_comms(): HasMany
    {
        return $this->hasMany(SalesComm::class)->whereNot('sales_comms.status', SalesComm::PYMT_STATE_CANCELLED);
    }

    public function files(): HasMany
    {
        return $this->hasMany(SoldPolicyDoc::class);
    }

    public function renewal_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class, 'renewal_policy_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function scopeHasInvoice($query, $hasInvoice = true)
    {
        if ($hasInvoice) {
            return $query->whereHas('company_comm_payments', function ($q) {
                $q->whereNotNull('invoice_id');
            });
        } else {
            return $query->whereDoesntHave('company_comm_payments', function ($q) {
                $q->whereNotNull('invoice_id');
            });
        }
    }

    public function scopeInvoicePaid($query, $isPaid = true)
    {
        if ($isPaid) {
            return $query->whereHas('company_comm_payments', function ($q) {
                $q->whereNotNull('invoice_id')
                    ->whereNotNull('payment_date');
            });
        } else {
            return $query->whereHas('company_comm_payments', function ($q) {
                $q->whereNotNull('invoice_id')
                    ->whereNull('payment_date');
            });
        }
    }
}
