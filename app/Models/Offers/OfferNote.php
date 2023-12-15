<?php

namespace App\Models\Offers;

use App\Exceptions\UnauthorizedException;
use App\Models\Insurance\Policy;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OfferNote extends Model
{
    use HasFactory;

    protected $table = 'offer_notes';
    protected $fillable = [
        'note',
        'user_id',
    ];

    ////static functions


    ////model functions
    public function editInfo($note)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser->id != $this->user_id) return false;
        try {
            if ($this->update([
                "note"  =>  $note
            ])) {
                AppLog::info("Offer note edited", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLog::error("Can't add Note", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function delete()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $this)) throw new UnauthorizedException();
        try {
            if (parent::delete()) {
                AppLog::info("Note deleted");
                return true;
            } else {
                AppLog::error("Note deletetion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("Note deletetion failed", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    ////scopes


    ////relations
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function policy_condition(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
