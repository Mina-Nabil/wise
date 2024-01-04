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
use Illuminate\Support\Facades\Log;

class Offer extends Model
{

    use HasFactory, SoftDeletes, Loggable;

    protected $casts = [
        'due' => 'datetime',
    ];

    const MORPH_TYPE = 'offer';

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
        'note', 'due', 'closed_by_id', 'assignee_id'

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
                $this->sendOfferNotifications("Offer status changed", "Offer#$this->id's status changed");
                $this->addComment("Status set to $status", false);

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
        if ($loggedInUser && !$loggedInUser->can('changeDue', $this)) return false;

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
    public function addOption($policy_id, $policy_condition_id = null, $insured_value = null, $payment_frequency = null, array $fields = [], $docs = [])
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
            /** @var OfferOption */
            if ($tmpOption = $this->options()->firstOrCreate(
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

                foreach ($fields as $field) {
                    $tmpOption->addField($field['name'], $field['value']);
                }
                foreach ($docs as $doc) {
                    $tmpOption->addFile($doc['name'], $doc['url']);
                }

                $this->sendOfferNotifications("New Offer option", "A new option is attached on Offer#$this->id");
                AppLog::info("Offer option added", loggable: $this);
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

    public function addFile($name, $url)
    {
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

    public function assignTo($user_id_or_type, $comment = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser && !$loggedInUser->can('updateAssignTo', $this)) return false;
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

    public function acceptOption($option_id)
    {
        if ($this->status == self::STATUS_APPROVED)
            throw new Exception('Offer already approved');

        $option = OfferOption::findOrFail($option_id);
        $option->status = OfferOption::STATUS_APPROVED;
        $this->selected_option_id = $option_id;
        try {
            $this->options()->where('status', OfferOption::STATUS_APPROVED)->update([
                'status'    =>  OfferOption::STATUS_DECLINED
            ]);
            $option->save();
            $this->save();
            $this->sendOfferNotifications("Offer option accepted", "Option accepted on Offer#$this->id");
            $this->addComment("Offer option accepted", false);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option accept failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    private function sendOfferNotifications($title, $message)
    {
        $notifier_id = Auth::id();
        if ($notifier_id != $this->assignee_id) {
            $this->loadMissing('assignee');
            $this->assignee?->pushNotification($title, $message, "offers/" . $this->id);
        }
        if ($notifier_id != $this->creator_id) {
            $this->loadMissing('creator');
            $this->assignee?->pushNotification($title, $message, "offers/" . $this->id);
        }
    }

    private function addComment($comment, $logEvent = true): OfferComment|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        try {
            $comment = $this->comments()->create([
                "user_id"   =>  $loggedInUser ? $loggedInUser->id : null,
                "comment"   =>  $comment
            ]);
            if ($logEvent && $loggedInUser) {
                AppLog::info("Comment added", "User $loggedInUser->username added new comment to task $this->id", $this);
                $this->sendOfferNotifications("Comment added", "Task#$this->id has a new comment by $loggedInUser->username");
            }

            return $comment;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't add comment", $e->getMessage(), $this);
            return false;
        }
    }

    ////scopes
    public function scopeUserData($query, $searchText = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('offers.*')
            ->join('users', "offers.assignee_id", '=', 'users.id');

        if ($loggedInUser->type !== User::TYPE_ADMIN) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('offers.client_id', '=', 'corporates.id')
                    ->where('offers.client_type', Corporate::MORPH_TYPE);
            })->leftjoin('customers', function ($j) {
                $j->on('offers.client_id', '=', 'customers.id')
                    ->where('offers.client_type', Customer::MORPH_TYPE);
            })->groupBy('offers.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    $qq->where('customers.name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.email', 'LIKE', "%$tmp%");
                });
            }
        });
        return $query;
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
        return $this->hasMany(OfferComment::class);
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
}
