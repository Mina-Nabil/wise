<?php

namespace App\Models\Offers;

use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use App\Traits\Loggable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Offer extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    const STATUS_NEW = 'new';
    const STATUS_PENDING_OPERATIONS = 'pending_operations';
    const STATUS_PENDING_INSUR = 'pending_insurance_companies';
    const STATUS_PENDING_CUSTOMER = 'pending_customer';
    const STATUS_DECLINED_INSUR = 'declined_by_insurance';
    const STATUS_DECLINED_CUSTOMER = 'declined_by_customer';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_APPROVED = 'approved';

    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_PENDING_OPERATIONS,
        self::STATUS_PENDING_INSUR,
        self::STATUS_PENDING_CUSTOMER,
        self::STATUS_DECLINED_INSUR,
        self::STATUS_DECLINED_CUSTOMER,
        self::STATUS_CANCELLED,
        self::STATUS_APPROVED,
    ];

    protected $table = 'offers';
    protected $fillable = [
        'creator_id', 'type', 'status', 'item_id', 'item_type',
        'item_title', 'item_value', 'item_desc', 'selected_option_id',
        'note', 'due', 'closed_by_id'

    ];


    ////static functions
    public function newOffer(Customer|Corporate $client, string $type, $item_value = null, $item_title = null, $item_desc = null, string $note = null, Carbon $due = null, Model $item = null): self|false
    {
        $newOffer = new self([
            "creator_id"    =>  Auth::id(),
            "assignee_id"    =>  Auth::id(),
            "type"          =>  $type,
            "status"        =>  self::STATUS_NEW,
            "item_value"    =>  $item_value,
            "item_title"    =>  $item_title,
            "item_desc"     =>  $item_desc,
            "note"          =>  $note,
            "due"           =>  $due->format('Y-m-d H:i:s'),
        ]);
        $newOffer->client()->associate($client);
        if ($item)
            $newOffer->item()->associate($item);

        try {
            if ($newOffer->save()) {
                AppLog::info("New Offer", loggable: $newOffer);
            }
            return $newOffer;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create offer", desc: $e->getMessage());
            return false;
        }
    }

    ////model functions
    public function editInfo($item_value = null, $item_title = null, $item_desc = null, string $note = null)
    {
        $this->update([
            "item_value"    =>  $item_value,
            "item_title"    =>  $item_title,
            "item_desc"     =>  $item_desc,
            "note"          =>  $note,
        ]);

        try {
            if ($this->save()) {
                AppLog::info("Offer Main Info Updated", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit offer", desc: $e->getMessage());
            return false;
        }
    }

    /**
     * @return string if failed, an error message will return
     * @return true if done
     */
    public function setStatus($status): string|true
    {
        $updates = array();
        //perform checks
        switch ($status) {
            case self::STATUS_CANCELLED | self::STATUS_DECLINED_INSUR | self::STATUS_DECLINED_CUSTOMER:
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
                $this->loadMissing('assignee');
                if (!$this->assignee?->is_operations) return "Offer not assigned to operations";
                if (!$this->options_count) return "No offer options found";
                break;

            case self::STATUS_PENDING_CUSTOMER:
                $approvedCount = $this->options()->where('status', OfferOption::STATUS_APPROVED)
                    ->get()->count();
                if (!($this->assignee?->is_sales || $this->assignee?->is_manager))
                    return "Offer not assigned to sales";
                if (!$approvedCount) return "No offer options approved";
                break;

            case self::STATUS_APPROVED:
                $approvedCount = $this->options()->where('status', OfferOption::STATUS_APPROVED)
                    ->get()->count();
                if (!$approvedCount) return "No offer options approved";
                break;

            default:
                return "Invalid status";
        }

        try {
            $updates['status']  = $status;
            if ($this->update($updates)) {
                AppLog::info("Changed status to " . $status, loggable: $this);
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

    public function setItemDetails($item_value, Model $item = null, $item_title = null, $item_desc = null)
    {
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
                AppLog::error("Offer item updated", loggable: $this);
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
        try {

            if ($this->update([
                "due"   =>  $newDue->format('Y-m-d H:i:s')
            ])) {
                AppLog::info("Offer due updated", loggable: $this);
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

    public function addOption($policy_id, $policy_condition_id, $insured_value, $payment_frequency)
    {
        switch ($payment_frequency) {
            case OfferOption::PAYMENT_FREQ_YEARLY:
                $periodic_payment = $insured_value;
                break;
            case OfferOption::PAYMENT_FREQ_QUARTER:
                $periodic_payment = round($insured_value / 4, 2);
                break;
            case OfferOption::PAYMENT_FREQ_MONTHLY:
                $periodic_payment = round($insured_value / 12, 2);
                break;

            default:
                return false;
        }
        try {
            if ($this->options()->firstOrCreate(
                [
                    "policy_id"             =>  $policy_id,
                ],
                [
                    "policy_condition_id"   =>  $policy_condition_id,
                    "insured_value"         =>  $insured_value,
                    "periodic_payment"      =>  $periodic_payment,
                    "payment_frequency"     =>  $payment_frequency,
                ]
            )) {
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

    public function addComment($comment)
    {
        try {
            if ($this->comments()->create([
                "user_id"   =>  Auth::id(),
                "comment"  =>  $comment
            ])) {
                AppLog::info("Comment added", loggable: $this);
                return true;
            } else {
                AppLog::error("Comment addition failed", desc: "Failed to add comment", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Comment addition failed", desc: $e->getMessage(), loggable: $this);
            return true;
        }
    }

    public function addFile($name, $url)
    {
        try {
            if ($this->files()->create([
                "name"  =>  $name,
                "user_id"   =>  Auth::id(),
                "url"  =>  $url,
            ])) {
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

    ////scopes


    ////relations
    public function client(): MorphTo
    {
        return $this->morphTo();
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee_id(): BelongsTo
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
        return $this->hasMany(OfferComment::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(OfferDoc::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function closed_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_id');
    }
}
