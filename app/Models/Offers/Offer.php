<?php

namespace App\Models\Offers;

use App\Exceptions\InvalidSoldPolicyException;
use App\Helpers\Helpers;
use App\Models\Business\SoldPolicy;
use App\Models\Corporates\Corporate;
use App\Models\Customers\Car;
use App\Models\Customers\Customer;
use App\Models\Insurance\LineField;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CommProfile;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\SalesComm;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use App\Traits\Loggable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Customers\Relative;

class Offer extends Model
{

    use HasFactory, SoftDeletes, Loggable;

    protected $casts = [
        'due' => 'datetime',
    ];

    const FILES_DIRECTORY = 'offers/comparisons/';

    const MORPH_TYPE = 'offer';

    const STATUS_NEW = 'new';
    const STATUS_PENDING_OPERATIONS = 'pending_operations';
    const STATUS_PENDING_INSUR = 'pending_insurance_companies';
    const STATUS_PENDING_SALES = 'pending_sales';
    const STATUS_PENDING_CUSTOMER = 'pending_customer';
    const STATUS_DECLINED_INSUR = 'declined_by_insurance';
    const STATUS_DECLINED_CUSTOMER = 'declined_by_customer';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_APPROVED = 'approved';

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PENDING_OPERATIONS,
        self::STATUS_PENDING_SALES,
        self::STATUS_PENDING_INSUR,
        self::STATUS_PENDING_CUSTOMER,
        self::STATUS_DECLINED_INSUR,
        self::STATUS_DECLINED_CUSTOMER,
        self::STATUS_CANCELLED,
        self::STATUS_APPROVED,
    ];

    const ACTIVE_STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PENDING_OPERATIONS,
        self::STATUS_PENDING_SALES,
        self::STATUS_PENDING_INSUR,
        self::STATUS_PENDING_CUSTOMER,
    ];

    protected $table = 'offers';
    protected $fillable = [
        'creator_id',
        'type',
        'status',
        'item_id',
        'item_type',
        'is_renewal',
        'item_title',
        'item_value',
        'item_desc',
        'selected_option_id',
        'note',
        'due',
        'closed_by_id',
        'assignee_id',
        'in_favor_to',
        'renewal_policy',
        'sub_status',
        'renewal_policy_id'
    ];


    ////static functions
    public static function newOffer(Customer|Corporate $client, string $type, $item_value = null, $item_title = null, $item_desc = null, ?string $note = null, ?Carbon $due = null, ?Model $item = null, $is_renewal = false, $in_favor_to = null, $renewal_policy = null, $renewal_policy_id = null): self|false
    {
        $newOffer = new self([
            "creator_id"    =>  Auth::id(),
            "assignee_id"   =>  Auth::id(),
            "type"          =>  $type,
            "status"        =>  self::STATUS_NEW,
            "item_value"    =>  $item_value,
            "item_title"    =>  $item_title,
            "item_desc"     =>  $item_desc,
            "in_favor_to"   =>  $in_favor_to,
            "note"          =>  $note,
            "is_renewal"    =>  $is_renewal,
            "renewal_policy" =>  $renewal_policy,
            "renewal_policy_id" =>  $renewal_policy_id,
            "due"           =>  $due->format('Y-m-d H:i:s'),
        ]);
        $newOffer->client()->associate($client);
        if ($item)
            $newOffer->item()->associate($item);

        try {
            if ($newOffer->save()) {
                AppLog::info("New Offer", loggable: $newOffer);
                $sales_in = Auth::user()->sales_in_profile;
                if ($sales_in)
                    $newOffer->addCommProfile($sales_in->id, true);
                $newOffer->addComment("Created offer", false);
                $lineFields = LineField::ByLineOfBusiness($type)->get();
                foreach ($lineFields as $lf) {
                    $newOffer->fields()->create([
                        'field' => $lf->field,
                        'is_mandatory' => $lf->is_mandatory,
                        'value' => null,
                    ]);
                }
            }
            return $newOffer;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create offer", desc: $e->getMessage());
            return false;
        }
    }

    public static function cleanOffersDirectory()
    {
        $file = new Filesystem;
        $file->cleanDirectory(storage_path(self::FILES_DIRECTORY));
    }

    public static function exportReport(?Carbon $from = null, ?Carbon $to = null, array $statuses = [], $creator_ids = [], $assignee_id_or_type = null, $closed_by_id = null, $line_of_business = null, $value_from = null, $value_to = null, $searchText = null, $is_renewal = null, array $comm_profile_ids = [], ?Carbon $expiry_from = null, ?  Carbon $expiry_to = null)
    {
        $offers = self::report($from, $to, $statuses, $creator_ids, $assignee_id_or_type, $closed_by_id, $line_of_business, $value_from, $value_to, $searchText, $is_renewal, $comm_profile_ids, $expiry_from, $expiry_to)->get();
        $template = IOFactory::load(resource_path('import/offers_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        foreach ($offers as $of) {
            $activeSheet->getCell('A' . $i)->setValue($of->client->full_name);
            $activeSheet->getCell('B' . $i)->setValue($of->client_type);
            $activeSheet->getCell('C' . $i)->setValue(ucwords(str_replace('_', ' ', $of->type)));
            $activeSheet->getCell('D' . $i)->setValue(ucwords(str_replace('_', ' ', $of->status)));
            $activeSheet->getCell('E' . $i)->setValue($of->renewal_policy);
            $activeSheet->getCell('F' . $i)->setValue(number_format($of->item_value, 2, '.', ','));
            $activeSheet->getCell('G' . $i)->setValue(
                $of->assignee ? ucwords($of->assignee->first_name) . ' ' . ucwords($of->assignee->last_name) : ($of->assignee_type ? ucwords($of->assignee_type) : 'No one/team assigned')
            );
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "offers_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    ////model functions
    /** 
     * Generate sold policy from selected option
     * Policy number, start and expiry shall be presented as new empty fields
     * Other fields can be populated from the selected option, expect the car details(chassis, engine & plate)
     */
    public function generateSoldPolicy($policy_number, $policy_doc,  Carbon $start, Carbon $expiry,  $installements_count, $payment_frequency, $insured_value, $net_rate, $net_premium, $gross_premium, $car_chassis = null, $car_engine = null, $car_plate_no = null, $in_favor_to = null, ?Carbon $issuing_date = null)
    {
        $foundSoldPolicy = SoldPolicy::byOfferID($this->id)->notCancelled()->notExpired()->first();
        if ($foundSoldPolicy && $foundSoldPolicy->id && !$foundSoldPolicy->is_expired) return $foundSoldPolicy;
        if (!$this->selected_option_id) return false;
        $this->load('client');
        $this->load('selected_option');
        $this->load('selected_option.policy');
        $this->load('selected_option.policy_condition');

        assert($insured_value || $this->selected_option->insured_value, new InvalidSoldPolicyException("No insured value found"));
        assert($net_rate || $this->selected_option->policy_condition->rate, new InvalidSoldPolicyException("No net rate found"));
        assert($net_premium || $this->selected_option->net_premium, new InvalidSoldPolicyException("No net premium found"));
        assert($gross_premium || $this->selected_option->gross_premium, new InvalidSoldPolicyException("No gross premium found"));
        assert(!SoldPolicy::checkOverlap($policy_number, $start, $expiry), new InvalidSoldPolicyException("Overlapping sold policy found with the same policy number"));
        // assert($installements_count || $this->selected_option->installements_count, "No installement count found"); 

        $customer_car = ($this->item_type == Car::MORPH_TYPE) ? $this->item_id : null;

        $main_sales_id = $this->getMainSales();

        $soldPolicy = SoldPolicy::newSoldPolicy(
            client: $this->client,
            policy_id: $this->selected_option->policy_id,
            policy_number: $policy_number,
            insured_value: $insured_value ?? $this->selected_option->insured_value,
            net_rate: $net_rate ?? $this->selected_option->policy_condition->rate,
            net_premium: $net_premium ?? $this->selected_option->net_premium,
            gross_premium: $gross_premium ?? $this->selected_option->gross_premium,
            installements_count: $installements_count ?? $this->selected_option->installements_count ?? 1,
            payment_frequency: $payment_frequency ?? $this->selected_option->payment_frequency,
            in_favor_to: $in_favor_to ?? $this->in_favor_to,
            start: $start,
            expiry: $expiry,
            offer_id: $this->id,
            customer_car_id: $customer_car,
            car_chassis: $car_chassis,
            car_plate_no: $car_plate_no,
            car_engine: $car_engine,
            policy_doc: $policy_doc,
            issuing_date: $issuing_date,
            renewal_policy_id: $this->renewal_policy_id,
            discount: $this->getDiscountTotal('comm'),
            origin_discount: $this->getDiscountTotal('origin')
        );
        $clientDueDate = $issuing_date ?  ($issuing_date->isBefore($start) ? $start : $issuing_date) : $start;
        if ($soldPolicy) {
            $this->setStatus(self::STATUS_APPROVED);
            foreach ($this->selected_option->policy->benefits as $b) {
                $soldPolicy->addBenefit($b->benefit, $b->value);
            }
            foreach ($this->sales_comms()->notConfirmed()->get() as $commaya) {
                $commaya->update([
                    "sold_policy_id"    =>  $soldPolicy->id,
                    "created_at"        =>  $issuing_date
                ]);
                $commaya->refreshPaymentInfo();
            }

            foreach ($this->fields()->get() as $field) {
                $soldPolicy->fields()->create([
                    "field"    =>  $field->field,
                    'is_mandatory'  =>  $field->is_mandatory,
                    "value"        =>  $field->value
                ]);
            }

            if ($main_sales_id) {
                $soldPolicy->setMainSales($main_sales_id, false);
            }
            $this->load('files', 'selected_option.docs');
            foreach ($this->files as $f) {
                $soldPolicy->addFile($f->name, $f->url, $f->user_id);
            }
            foreach ($this->selected_option->docs as $f) {
                $soldPolicy->addFile($f->name, $f->url, $f->user_id);
            }

            switch ($payment_frequency) {
                case OfferOption::PAYMENT_FREQ_YEARLY:
                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium, $start, $main_sales_id ? $main_sales_id : $this->creator_id);
                    break;

                case OfferOption::PAYMENT_FREQ_HALF_YEARLY:
                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 2, $clientDueDate, $main_sales_id ? $main_sales_id : $this->creator_id);
                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 2, $clientDueDate->addMonths(6), $main_sales_id ? $main_sales_id : $this->creator_id);
                    break;

                case OfferOption::PAYMENT_FREQ_QUARTER:
                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 4, $clientDueDate, $main_sales_id ? $main_sales_id : $this->creator_id);

                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 4, $clientDueDate->addMonths(3), $main_sales_id ? $main_sales_id : $this->creator_id);

                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 4, $clientDueDate->addMonths(3), $main_sales_id ? $main_sales_id : $this->creator_id);

                    $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 4, $clientDueDate->addMonths(3), $main_sales_id ? $main_sales_id : $this->creator_id);
                    break;

                case OfferOption::PAYMENT_FREQ_MONTHLY:
                    for ($i = 0; $i < 12; $i++)
                        $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / 12, $i == 0 ? $clientDueDate : $clientDueDate->addMonth(), $main_sales_id ? $main_sales_id : $this->creator_id);
                    break;

                case OfferOption::PAYMENT_INSTALLEMENTS:
                    for ($i = 0; $i < $installements_count; $i++)
                        $soldPolicy->addClientPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $gross_premium / $installements_count, $i == 0 ? $clientDueDate : $clientDueDate->addMonth(), $main_sales_id ? $main_sales_id : $this->creator_id);
                    break;
            }
        }
        return $soldPolicy;
    }

    public function getMainSales()
    {
        return $this->comm_profiles()->salesIn()->first()?->user_id;
    }

    public function exportComparison($ids = [], $saveAndGetFileUrl = false)
    {
        $template = IOFactory::load(resource_path('import/comparison_template.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 5;
        foreach (PolicyBenefit::BENEFITS as $b) {
            $activeSheet->insertNewRowBefore($i++);
            $cell = $activeSheet->getCell('A' . $i - 1);
            $cell->setValue($b);
        }
        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $options = $this->options()->with('policy_condition', 'policy', 'policy.company', 'policy.benefits')->when(count($ids), function ($q) use ($ids) {
            $q->whereIn('id', $ids);
        })->get();
        $startChar = 'B';
        foreach ($options as $op) {
            $activeSheet->insertNewColumnBefore($startChar);
            $activeSheet->getCell($startChar . '1')->setValue($op->policy->name  . " - " . $op->policy?->company->name);
            $activeSheet->getCell($startChar . '2')->setValue($op->net_premium);
            $activeSheet->getCell($startChar . '3')->setValue($op->gross_premium);

            foreach ($op->policy->benefits as $b) {
                $cellIndex = $startChar . 5 + array_search($b->benefit, PolicyBenefit::BENEFITS);
                $activeSheet->getCell($cellIndex)->setValue($b->value);
                $activeSheet->getColumnDimension($startChar)->setAutoSize(true);
            }
            $startChar++;
        }
        $activeSheet->removeColumn($activeSheet->getHighestColumn());
        $activeSheet->removeRow($activeSheet->getHighestRow());
        $startChar--;
        $i--;
        $activeSheet->getStyle("A1:$startChar$i")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $activeSheet->setRightToLeft(false);

        $writer = new Mpdf($newFile);
        $file_path = self::FILES_DIRECTORY . "offer{$this->id}_comparison.pdf";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);
        if ($saveAndGetFileUrl) {
            if (Storage::disk('s3')->put($file_path, file_get_contents($public_file_path))) {
                File::delete($public_file_path);
                /** @disregard */
                return Storage::disk('s3')->url($file_path);
            }
        }
        return response()->download($public_file_path);
    }

    public function generateWhatsappUrl($client_phone, $ids = [])
    {
        $num = "2" . preg_replace('/\D/', '', $client_phone);
        $whatsapp_url = "https://wa.me/" . $num;
        $exportFileUrl = $this->exportComparison($ids, true);
        return $whatsapp_url . "?text=" . urlencode("Please find the offer comparison url: " . $exportFileUrl);
    }

    public function addCommProfile(int $profile_id, bool $skipCheck = false)
    {
        if (!$skipCheck) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('updateCommission', $this)) return false;
        }

        try {
            $prof = CommProfile::findOrFail($profile_id);
            $this->comm_profiles()->attach($profile_id);
            if ($prof->auto_override_id) $this->comm_profiles()->attach($prof->auto_override_id);
            if ($this->selected_option_id)
                $this->generateSalesCommissions();
            $this->addComment("Added commission profiles", false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add commission profiles", $e->getMessage(), $this);
            return false;
        }
    }

    public function removeCommProfile(int $profile_id)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updateCommission', $this)) return false;

        try {
            $this->comm_profiles()->detach([$profile_id]);
            if ($this->selected_option_id)
                $this->generateSalesCommissions();
            $this->addComment("Removed commission profiles", false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't remove commission profiles", $e->getMessage(), $this);
            return false;
        }
    }

    public function generateSalesCommissions()
    {
        if (!$this->selected_option_id) throw new Exception("No option selected");
        $this->sales_comms()->delete();
        $this->load('comm_profiles', 'selected_option');
        foreach ($this->comm_profiles as $prof) {
            $prof->load('user');
            $title = $prof->user ? $prof->user->username . " - " . $prof->type : $prof->title;
            $valid_conf = $prof->getValidDirectCommissionConf($this->selected_option);
            if (!$valid_conf) {
                $this->addSalesCommission($title, CommProfileConf::FROM_NET_COMM, 0, $prof->id, "Added automatically for target calculations");
            } else {
                $this->addSalesCommission($title, $valid_conf->from, $valid_conf->percentage, $prof->id, "Added automatically for direct commission", true);
            }
        }
    }

    private function addSalesCommission($title, $from, $comm_percentage, $comm_profile_id = null, $note = null, $is_direct = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateCommission', $this)) return false;

        try {
            $sales_comm = $this->sales_comms()->create([
                "title"             => $title,
                "comm_profile_id"   => $comm_profile_id,
                "from"              => $from,
                "comm_percentage"   => $comm_percentage,
                "is_direct"         => $is_direct,
                "note"              => $note
            ]);
            if ($sales_comm && $is_direct) {
                $sales_comm->confirmPayment();
                AppLog::info("Offer commission added", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit offer commission", desc: $e->getMessage());
            return false;
        }
    }

    public function generateEmailUrl($client_email, $ids = [])
    {
        $mailto_url = "mailto:" . $client_email;
        $exportFileUrl = $this->exportComparison($ids, true);
        return $mailto_url . "?subject:" . urlencode("New Offer Comparison") . "?body=" . urlencode("Please find the offer comparison url: " . $exportFileUrl);
    }

    public function setNote(?string $in_favor_to = null, ?string $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateNote', $this)) return false;

        $this->update([
            "note"          =>  $note,
            "in_favor_to"   =>  $in_favor_to,
        ]);

        try {
            if ($this->save()) {
                AppLog::info("Offer details Updated", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit offer details", desc: $e->getMessage());
            return false;
        }
    }

    public function setRenewalFlag(bool $is_renewal, $renewal_policy_id = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateFlag', $this)) return false;

        $this->update([
            "is_renewal"            =>  $is_renewal,
            "renewal_policy_id"     =>  $renewal_policy_id,
        ]);

        try {
            if ($this->save()) {
                AppLog::info("Offer renewal flag Updated", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit offer renewal flag", desc: $e->getMessage());
            return false;
        }
    }

    public function getDiscountTotal(string $type)
    {
        switch ($type) {
            case 'comm':
                return $this->discounts()->where('type', OfferDiscount::TYPE_COMMISSION)->get()->sum('value');

            case 'origin':
                return $this->discounts()->whereNot('type', OfferDiscount::TYPE_COMMISSION)->get()->sum('value');

            default:
                return 0;
        }
    }

    /**
     * @return string if failed, an error message will return
     * @return true if done
     */
    public function setStatus($status, $sub_status = null): string|bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateStatus', $this))
            return false;

        $updates = array();
        //perform checks
        switch ($status) {
            case self::STATUS_CANCELLED:
            case self::STATUS_DECLINED_INSUR:
            case self::STATUS_DECLINED_CUSTOMER:
                //closing the offer, no need to check
                $updates['closed_by_id']    =   Auth::id();
                break;

            case self::STATUS_PENDING_OPERATIONS:
                //check if there is options before sending it to operations
                $this->loadCount('options');
                if (!$this->options_count) return "No offer options found";
                break;

            case self::STATUS_PENDING_INSUR:
                $this->loadCount('options');
                $this->load('assignee');
                if (!$this->assignee?->is_operations) return "Offer not assigned to operations";
                if (!$this->options_count) return "No offer options found";
                break;

            case self::STATUS_PENDING_CUSTOMER:
                $approvedCount = $this->options()->where('status', OfferOption::STATUS_QTTN_RECV)
                    ->get()->count();
                if (!($this->assignee?->is_sales || $this->assignee?->is_manager))
                    return "Offer not assigned to sales";
                if (!$approvedCount) return "No qoutation received";
                break;

            case self::STATUS_APPROVED:
                $approvedCount = $this->options()->whereIn('status', [OfferOption::STATUS_CLNT_ACPT, OfferOption::STATUS_ISSUED])
                    ->get()->count();
                if (!$approvedCount) return "No offer options approved";
                break;
            case self::STATUS_PENDING_SALES:
                break;
            default:
                return "Invalid status";
        }

        try {
            $updates['status']  = $status;
            if ($sub_status && $status == self::STATUS_PENDING_INSUR) $updates['sub_status'] = $sub_status;
            else $updates['sub_status'] = null;
            if ($this->update($updates)) {
                AppLog::info("Changed status to " . $status, loggable: $this);
                $this->sendOfferNotifications("Offer status changed", "Offer#$this->id's status changed");
                $this->addComment("set Status to $status", false);

                return true;
            } else {
                AppLog::error("Changing status failed", desc: "No Exception found", loggable: $this);
                return "Changing status failed";
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Changing status failed", desc: $e->getMessage(), loggable: $this);
            return "Changing status failed";
        }
    }

    public function setLineFields($fields)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateLineFields', $this)) return false;

        try {
            foreach ($fields as $id => $fieldData) {
                foreach ($fieldData as $field => $value) {

                    $f = $this->fields()->find($id);

                    if ($f->is_mandatory && !$value) {
                        throw new Exception("Required value for mandatory field: {$field}");
                    }

                    $f->update([
                        'value' => $value,
                    ]);
                }
            }

            $this->addComment("Line fields updated", false);
            AppLog::info("Line fields updated", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't update line fields", $e->getMessage(), $this);
            return false;
        }
    }

    public function setItemDetails($item_value, ?Model $item = null, ?string $item_title = null, ?string $item_desc = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateItem', $this))
            return false;

        $updates['item_value'] = $item_value;
        $updates['item_title'] = $item_title;
        $updates['item_desc'] = $item_desc;
        if ($item) $this->item()->associate($item);
        else {
            $updates['item_id'] = null;
            $updates['item_type'] = null;
        }
        try {
            if ($this->update($updates)) {
                AppLog::info("Offer item updated", loggable: $this);
                $this->sendOfferNotifications("Offer item change", "Offer#$this->id's item details changed");
                $this->addComment("Details changed", false);
                return true;
            } else {
                AppLog::error("Can't set offer item", desc: "Failed to update", loggable: $this);
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set offer item", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function changeDue(Carbon $newDue)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateDue', $this)) return false;

        try {
            if ($this->update([
                "due"   =>  $newDue->format('Y-m-d H:i:s')
            ])) {
                AppLog::info("Offer due updated", loggable: $this);
                $this->sendOfferNotifications("Offer due change", "Offer#$this->id's next action is set to {$newDue->format('Y-m-d H:i:s')}");
                $this->addComment("Due set to {$newDue->format('Y-m-d H:i:s')}", false);

                return true;
            } else {
                AppLog::error("Due edit failed", "Update failed with no exception", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Due edit failed", $e->getMessage(), loggable: $this);
            return false;
        }
    }

    /**
     * @param array $fields should contain an array of arrays.. each child array should contain 'name' & 'value'
     */
    public function addOption(
        $policy_id,
        $policy_condition_id = null,
        $insured_value = null,
        $payment_frequency = null,
        $net_premium = null,
        $gross_premium = null,
        $is_renewal = false,
        $installements_count = null,
        array $fields = [],
        array $docs = []
    ) {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateOptions', $this)) return false;

        try {
            /** @var OfferOption */
            if ($tmpOption = $this->options()->firstOrCreate(
                [
                    "policy_id"             =>  $policy_id,
                    "insured_value"  =>  $insured_value,
                ],
                [
                    "policy_condition_id"   =>  $policy_condition_id,
                    "net_premium"  =>  $net_premium,
                    "gross_premium"  =>  $gross_premium,
                    "payment_frequency"  =>  $payment_frequency,
                    "is_renewal"  =>  $is_renewal,
                    "installements_count"  =>  $installements_count
                ]
            )) {

                foreach ($fields as $field) {
                    $tmpOption->addField($field['name'], $field['value']);
                }
                foreach ($docs as $doc) {
                    $tmpOption->addFile($doc['name'], $doc['url']);
                }

                $this->sendOfferNotifications("New Offer option", "A new option is attached on Offer#$this->id");
                AppLog::info("Offer option added", loggable: $this);

                //assign offer to operations team when create a new option 
                $this->assignTo(User::TYPE_OPERATIONS, bypassUserCheck: true);
                $this->setStatus(self::STATUS_PENDING_OPERATIONS);
                return $tmpOption;
            } else {
                AppLog::error("Can't add offer option", desc: "No stack found", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add offer option", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function downloadMedicalTemplate()
    {

        $template = IOFactory::load(resource_path('import/medical_template.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "medical_template.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function setMedicalClients($clients)
    {
        $this->medical_offer_clients()->delete();
        foreach ($clients as $client) {
            $this->addMedicalClient(
                $client['name'], 
                Carbon::parse($client['birth_date']), 
                $client['relation'] ?? Relative::RELATION_MAIN
            );
        }
        return true;
    }

    public function addMedicalClient($name, Carbon $birth_date, $relation = Relative::RELATION_MAIN)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('update', $this)) return false;

        try {
            $this->medical_offer_clients()->firstOrCreate([
                "name"          =>  $name,
            ], [
                "birth_date"    =>  $birth_date->format('Y-m-d'),
                "relation"      =>  $relation
            ]);
            AppLog::info("Medical client added", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add medical client", $e->getMessage(), $this);
            return false;
        }
    }

    public function importMedicalTemplate($file)
    {
        $spreadsheet = IOFactory::load($file);
        $activeSheet = $spreadsheet->getActiveSheet();
        $highestRow = $activeSheet->getHighestDataRow();

        for ($i = 6; $i <= $highestRow; $i++) {
            try {
                $name       = $activeSheet->getCell('A' . $i)->getValue();
                $birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) $activeSheet->getCell('B' . $i)->getValue());
                $relation   = $activeSheet->getCell('C' . $i)->getValue() ?: Relative::RELATION_MAIN;
                
                if (!$name) continue;
                
                // Validate relation
                if (!in_array($relation, Relative::RELATIONS)) {
                    $relation = Relative::RELATION_MAIN;
                }
                
                $this->addMedicalClient($name, Carbon::parse($birth_date), $relation);
            } catch (Exception $e) {
                report($e);
                AppLog::error("Can't import medical template", $e->getMessage(), $this);
                return false;
            }
        }
    }

    public function downloadCalculatedMedicalTemplate($policy_id)
    {
        /** @var Policy */
        $policy = Policy::findOrFail($policy_id);
        $template = IOFactory::load(resource_path('import/medical_template.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 6;
        $totalPrem = 0;
        $totalGross = 0;
        foreach ($this->medical_offer_clients as $client) {
            $activeSheet->getCell('A' . $i)->setValue($client->name);
            $birth_date = Carbon::parse($client->birth_date);
            $activeSheet->getCell('B' . $i)->setValue($birth_date->format('Y-m-d'));
            $activeSheet->getCell('C' . $i)->setValue($client->relation);
            $age = Carbon::now()->diffInYears($birth_date);
            $cond = $policy->getConditionByAge($age);
            $activeSheet->getCell('D' . $i)->setValue($cond->rate);
            $totalPrem += $cond->rate;
            $gross = $policy->calculateGrossValue($cond->rate);
            $totalGross += $gross;
            $activeSheet->getCell('E' . $i)->setValue($gross);
            $i++;
        }

        $activeSheet->getCell('B1')->setValue($policy->company->name . ' - ' . $policy->name);
        $activeSheet->getCell('B2')->setValueExplicit($totalPrem);
        $activeSheet->getCell('C2')->setValue($totalGross);

        $writer = new Xlsx($newFile);
        $file_path = self::FILES_DIRECTORY . "calculated_medical_template.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function addDiscount($type, $value, $note = null): OfferDiscount|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateDiscount', $this)) return false;

        try {
            $discount = $this->discounts()->create([
                "user_id"   =>  $loggedInUser->id,
                "type"   =>  $type,
                "value"   =>  $value,
                "note"   =>  $note
            ]);

            AppLog::info("Discount added", loggable: $this);
            $this->sendOfferNotifications("Discount added", "Offer#$this->id has a new discount by $loggedInUser->username");

            return $discount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add discount", $e->getMessage(), $this);
            return false;
        }
    }

    public function addComment($comment, $logEvent = true): OfferComment|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        try {
            $comment = $this->comments()->create([
                "user_id"   =>  $loggedInUser ? $loggedInUser->id : null,
                "comment"   =>  $comment
            ]);
            if ($logEvent && $loggedInUser) {
                AppLog::info("Comment added", "User $loggedInUser->username added new comment to offer $this->id", $this);
                $this->sendOfferNotifications("Comment added", "Offer#$this->id has a new comment by $loggedInUser->username");
            }

            return $comment;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add comment", $e->getMessage(), $this);
            return false;
        }
    }

    public function addFile($name, $url)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateItem', $this)) return false;


        try {
            if ($this->files()->create([
                "name"  =>  $name,
                "user_id"   =>  Auth::id(),
                "url"  =>  $url,
            ])) {
                $this->sendOfferNotifications("New Offer File attached", "A new file is attached on Offer#$this->id");
                $this->addComment("New Offer file", false);

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

    public function assignTo($user_id_or_type, $comment = null, $bypassUserCheck = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$bypassUserCheck && !$loggedInUser?->can('updateAssignTo', $this)) return false;

        $assignedToTitle = null;
        if (is_numeric($user_id_or_type)) {
            $this->assignee_id = $user_id_or_type;
            $this->assignee_type = null;
            $assignedToTitle = User::findOrFail($user_id_or_type)->username;
        } else if (in_array($user_id_or_type, User::TYPES)) {
            $this->assignee_id = null;
            $this->assignee_type = $user_id_or_type;
            $assignedToTitle = $user_id_or_type;
        } else {
            AppLog::warning("Wrong input", "Trying to set Offer#$this->id to $user_id_or_type", $this);
            return false;
        }

        try {
            $this->save();

            if ($comment) {
                $this->addComment($comment, false);
            } else {
                $this->addComment("Offer assigned to $assignedToTitle", false);
            }
            AppLog::info("Offer Assigned to $assignedToTitle", null, $this);
            $this->sendOfferNotifications("Offer Assignee change", "A new assignee is assigned for Offer#$this->id");

            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't assign offer", $e->getMessage(), $this);
            return false;
        }
    }

    public function setOptionState($option_id, $state)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateOptions', $this)) return false;

        if ($state == OfferOption::STATUS_CLNT_ACPT && !$this->client->is_data_full) throw new Exception("Client data is not complete, please check client's profile", 22);

        $option = OfferOption::findOrFail($option_id);
        $option->status = $state;
        try {
            $option->save();
            if ($state == OfferOption::STATUS_CLNT_ACPT) {
                $this->selected_option_id = $option_id;
                $this->generateSalesCommissions();
                $this->save();
                $this->sendOfferNotifications("Offer option accepted", "Option accepted on Offer#$this->id");
                $this->addComment("Offer option accepted", false);
                // if (!$this->with_operations) { // assigned to operations even if it was accepted by on of the operations
                $this->assignTo(User::TYPE_OPERATIONS, bypassUserCheck: true);
                // }
                $this->setStatus(self::STATUS_PENDING_OPERATIONS);
            }
            if ($state == OfferOption::STATUS_RQST_QTTN) {
                $this->assignTo(User::TYPE_OPERATIONS, bypassUserCheck: true);
            }
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option accept failed", desc: $e->getMessage(), loggable: $this);
            if ($e->getCode() == 22) throw $e;
            return false;
        }
    }

    public function deleteOption($option_id)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser?->can('updateOptions', $this)) return false;

        try {
            $option = OfferOption::findOrFail($option_id);
            if ($this->selected_option_id == $option_id && $option->delete()) {
                $this->selected_option_id = null;
                $this->save();
            }
            $option->delete();
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option deletion failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function setWatchers(array $user_ids = [])
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updateItem', $this)) return false;

        try {
            $this->watchers()->sync($user_ids);
            $this->addComment("Changed watchers list", false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't Set watchers", $e->getMessage(), $this);
            return false;
        }
    }

    private function sendOfferNotifications($title, $message)
    {
        $notifier_id = Auth::id();

        if ($notifier_id != $this->assignee_id) {
            $this->load('assignee');
            $this->assignee?->pushNotification($title, $message, "offers/" . $this->id);
        }
        if ($notifier_id != $this->creator_id) {
            $this->load('creator');
            $this->creator?->pushNotification($title, $message, "offers/" . $this->id);
        }
        $this->load('watchers');
        foreach ($this->watchers as $watcher) {
            if ($notifier_id != $watcher->id) {
                $watcher->pushNotification($title, $message, "offers/" . $this->id);
            }
        }
    }

    ////attributes
    public function getWithOperationsAttribute()
    {
        $this->load('assignee');
        return $this->assignee_type === User::TYPE_OPERATIONS || $this->assignee?->type == User::TYPE_OPERATIONS;
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getIsMotorAttribute()
    {
        return in_array($this->type, Policy::MOTOR_LINES);
    }

    public function getIsMedicalAttribute()
    {
        return in_array($this->type, Policy::MEDICAL_LINES);
    }

    public function getSoldPolicyIdAttribute()
    {
        return DB::table('sold_policies')->where('offer_id', $this->id)->first()?->id;
    }


    ////scopes
    public function scopeUserData($query, $searchText = null, $assignedToMe = null, $upcomingOnly = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('offers.*')
            ->leftjoin(
                'users',
                function ($j) {
                    $j->on("offers.assignee_id", '=', 'users.id')
                        ->orOn("offers.assignee_type", '=', 'users.type')
                        ->orOn('offers.creator_id', '=', 'users.id');
                }
            )
            ->leftjoin('offer_watchers', 'offer_watchers.offer_id', '=', 'offers.id')
            ->leftjoin('offer_comm_profiles', 'offer_comm_profiles.offer_id', '=', 'offers.id')
            ->leftjoin('comm_profiles', 'comm_profiles.id', '=', 'offer_comm_profiles.comm_profile_id');

        if (!(($loggedInUser->is_admin || $loggedInUser->id == 12) ||
            (($loggedInUser->is_operations || $loggedInUser->is_any_finance) && $searchText))) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->orwhereIn('users.manager_id', $loggedInUser->children_ids_array)
                    ->orwhere('offers.creator_id', $loggedInUser->id)
                    ->orwhere('offers.assignee_type', $loggedInUser->type)
                    ->orwhere('offers.assignee_id', $loggedInUser->id)
                    ->orwhere('comm_profiles.user_id', $loggedInUser->id)
                    ->orwhere('offer_watchers.user_id', $loggedInUser->id);
            });
            if ($loggedInUser->is_operations && !$assignedToMe) {
                $query->orWhere(function ($q) {
                    $q->whereHas('assignee', function ($query) {
                        $query->where('username', 'Sales.Renewal');
                    });
                });
            }
        }


        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('offers.client_id', '=', 'corporates.id')
                    ->where('offers.client_type', Corporate::MORPH_TYPE);
            })
                ->leftjoin('customers', function ($j) {
                    $j->on('offers.client_id', '=', 'customers.id')
                        ->where('offers.client_type', Customer::MORPH_TYPE);
                })
                ->leftjoin('corporate_phones', 'corporate_phones.corporate_id', '=', 'corporates.id')
                ->leftjoin('customer_phones', 'customer_phones.customer_id', '=', 'customers.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    $qq->where('customers.first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.middle_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_middle_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.arabic_last_name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('customer_phones.number', 'LIKE', "%$tmp%")
                        ->orwhere('corporate_phones.number', 'LIKE', "%$tmp%")
                        ->orwhere('renewal_policy', 'LIKE', "%$tmp%")
                        ->orwhere('offers.id', '=', $tmp);
                });
            }
        });
        $query->when($assignedToMe, function ($q) {
            $q->where('assignee_id', Auth::id());
        });

        $query->when($upcomingOnly, function ($q) {
            $now = new Carbon();
            $q->whereBetween('offers.due', [
                $now->format('Y-m-01'),
                $now->addMonth()->format('Y-m-t')
            ]);
        });

        return $query->groupBy('offers.id')->latest();
        // ->orderByDesc('due');
    }

    public function scopeReport($query, ?Carbon $from = null, ?Carbon $to = null, array $statuses = [], $creator_ids = [], $assignee_id_or_type = null, $closed_by_id = null, $line_of_business = null, $value_from = null, $value_to = null, $searchText = null, $is_renewal = null, array $comm_profile_ids = [], ?Carbon $expiry_from = null, ?  Carbon $expiry_to = null)
    {
        $query->userData($searchText)
            ->when($from, function ($q, $v) {
                $q->where('offers.due', ">=", $v->format('Y-m-d 00:00:00'));
            })->when($to, function ($q, $v) {
                $q->where('offers.due', "<=", $v->format('Y-m-d 23:59:59'));
            })->when(count($statuses) > 0, function ($q, $v) use ($statuses) {
                $q->byStates($statuses);
            })->when(count($creator_ids) > 0, function ($q) use ($creator_ids) {
                $q->whereIn('offers.creator_id', $creator_ids);
            })->when($assignee_id_or_type, function ($q, $v) {
                $q->where(function ($qq) use ($v) {
                    $qq->where('assignee_id', "=", $v)->orwhere('assignee_type', "=", $v);
                });
            })->when($closed_by_id, function ($q, $v) {
                $q->where('closed_by_id', "=", $v);
            })->when($value_from, function ($q, $v) {
                $q->where('item_value', ">=", $v);
            })->when($value_to, function ($q, $v) {
                $q->where('item_value', "<=", $v);
            })->when($line_of_business, function ($q, $v) {
                $q->where('offers.type', "=", $v);
            })->when($is_renewal !== null, function ($q, $v) use ($is_renewal) {
                $q->where('offers.is_renewal', "=", $is_renewal);
            })->when(count($comm_profile_ids), function ($q) use ($comm_profile_ids) {
                if (!Helpers::joined($q, 'offer_comm_profiles')) {
                    $q->join('offer_comm_profiles', 'offer_comm_profiles.offer_id', '=', 'offers.id');
                }
                $q->whereIn('offer_comm_profiles.comm_profile_id', $comm_profile_ids);
            })->when($expiry_from || $expiry_to, function ($q) use ($expiry_from, $expiry_to) {
                $q->join('sold_policies', 'sold_policies.id', '=', 'offers.renewal_policy_id')
                    ->when($expiry_from, function ($qq, $v) {
                        $qq->where('sold_policies.expiry', ">=", $v->format('Y-m-d 00:00:00'));
                    })->when($expiry_to, function ($qq, $v) {
                        $qq->where('sold_policies.expiry', "<=", $v->format('Y-m-d 23:59:59'));
                    });
            });
        $query->with('client', 'creator', 'assignee', 'selected_option', 'item', 'renewal_sold_policy');
        return $query;
    }

    public function scopeIsRenewal($query)
    {
        return $query->where('is_renewal', 1);
    }

    public function scopeNotRenewal($query)
    {
        return $query->where('is_renewal', 0);
    }

    public function scopeByRenewal($query, $val)
    {
        return $query->where('is_renewal', $val);
    }

    public function scopeByStates($query, array $states)
    {
        if (in_array('all', $states)) {
            return $query;
        }
        if (in_array('active', $states)) {
            array_push($states, self::STATUS_NEW);
            array_push($states, self::STATUS_PENDING_OPERATIONS);
            array_push($states, self::STATUS_PENDING_INSUR);
            array_push($states, self::STATUS_PENDING_CUSTOMER);
        }
        return $query->whereIn("offers.status", $states);
    }

    public function scopeByCreators($query, array $creator_ids)
    {
        return $query->whereIn('offers.creator_id', $creator_ids);
    }

    public function scopeFromTo($query, Carbon $from, Carbon $to)
    {
        return $query->where(function ($query) use ($from, $to) {
            $query->whereBetween("offers.due", [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        });
    }

    ////relations
    public function client(): MorphTo
    {
        return $this->morphTo();
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function selected_option(): BelongsTo
    {
        return $this->belongsTo(OfferOption::class, 'selected_option_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(OfferOption::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(OfferComment::class)->latest();
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(OfferDiscount::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(OfferDoc::class);
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_id');
    }

    public function renewal_sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class, 'renewal_policy_id');
    }

    public function watcher_ids(): HasMany
    {
        return $this->hasMany(OfferWatcher::class);
    }

    public function medical_offer_clients(): HasMany
    {
        return $this->hasMany(MedicalOfferClient::class);
    }

    public function sales_comms(): HasMany
    {
        return $this->hasMany(SalesComm::class);
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'offer_watchers');
    }

    public function comm_profiles(): BelongsToMany
    {
        return $this->belongsToMany(CommProfile::class, "offer_comm_profiles");
    }
}
