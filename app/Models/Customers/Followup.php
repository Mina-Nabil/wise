<?php

namespace App\Models\Customers;

use App\Models\Corporates\Corporate;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Followup extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'followup';

    const STATUS_NEW = 'new';
    const STATUS_CALLED = 'called';
    const STATUS_CANCELLED = 'canceled';
    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CALLED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'title',
        'status',
        'call_time',
        'action_time',
        'desc',
        'caller_note',
        'creator_id',
        'is_meeting',
        'line_of_business'
    ];

    ///model functions
    public function editInfo($title, $call_time = null, $desc = null, $is_meeting = false, $line_of_business = null)
    {
        try {
            $res = $this->update([
                "title"             =>  $title,
                "call_time"         =>  $call_time,
                "is_meeting"        =>  $is_meeting,
                "line_of_business"  =>  $line_of_business,
                "desc"              =>  $desc
            ]);
            AppLog::info("Follow-up updated", loggable: $this);
            return $res;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit followup", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsCalled($note = null)
    {
        if ($this->status !== self::STATUS_NEW) return false;
        try {
            $res = $this->update([
                "action_time"   =>  Carbon::now()->format('Y-m-d H:i:s'),
                "status"        =>  self::STATUS_CALLED,
                "caller_note"   =>  $note
            ]);
            AppLog::info("Follow-up done", loggable: $this);
            return $res;
        } catch (Exception $e) {
            AppLog::error("Can't set followup done", $e->getMessage(), $this);
            report($e);
            return false;
        }
    }

    public function setAsCancelled($note = null)
    {
        if ($this->status !== self::STATUS_NEW) return false;
        try {
            $res = $this->update([
                "action_time"   =>  Carbon::now()->format('Y-m-d H:i:s'),
                "status"        =>  self::STATUS_CANCELLED,
                "caller_note"   =>  $note
            ]);
            AppLog::info("Follow-up cancelled", loggable: $this);
            return $res;
        } catch (Exception $e) {
            AppLog::error("Can't cancel followup", $e->getMessage(), $this);
            report($e);
            return false;
        }
    }

    public function addComment($comment)
    {
        try {
            $this->comments()->create([
                "user_id"   =>  Auth::id(),
                "comment"   =>  $comment
            ]);
            AppLog::info("Follow up comment added", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create follow up comment", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }
    ///scopes
    public function scopeUserData($query, $searchText = null, $upcoming_only = false, $mineOnly = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('followups.*')
            ->join('users', "followups.creator_id", '=', 'users.id');

        if ($loggedInUser->type !== User::TYPE_ADMIN || $mineOnly) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('followups.called_id', '=', 'corporates.id')
                    ->where('followups.called_type', Corporate::MORPH_TYPE);
            })->leftjoin('customers', function ($j) {
                $j->on('followups.called_id', '=', 'customers.id')
                    ->where('followups.called_type', Customer::MORPH_TYPE);
            })->groupBy('followups.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    $qq->where('followups.title', 'LIKE', "%$tmp%")
                        ->orwhere('customers.first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.email', 'LIKE', "%$tmp%");
                });
            }
        })->when($upcoming_only, function ($q) {
            $now = new Carbon();
            $q->whereBetween('call_time', [
                $now->format('Y-m-01'),
                $now->addMonth()->format('Y-m-t')
            ]);
        });

        return $query->latest();
    }


    ///relations
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(FollowupComment::class);
    }
    public function called(): MorphTo
    {
        return $this->morphTo();
    }
}
