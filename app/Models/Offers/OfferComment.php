<?php

namespace App\Models\Offers;

use App\Exceptions\UnauthorizedException;
use App\Models\Insurance\Policy;
use App\Models\Users\User;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OfferComment extends Model
{
    use HasFactory;

    protected $table = 'offer_comments';
    protected $fillable = [
        'comment',
        'user_id',
    ];

    ////static functions


    ////model functions
    public function editInfo($comment)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if ($loggedInUser->id != $this->user_id) return false;
        try {
            if ($this->update([
                "comment"  =>  $comment
            ])) {
                AppLog::info("Offer comment edited", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLog::error("Can't edit Comment", desc: $e->getMessage(), loggable: $this);
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
            $this->loadMissing('offer');
            $tmpOffer = $this->offer;
            if (parent::delete()) {
                AppLog::info("Comment deleted", loggable: $tmpOffer);
                return true;
            } else {
                AppLog::error("Comment deletetion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("Comment deletetion failed", loggable: $this, desc: $e->getMessage());
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
        return $this->belongsTo(User::class);
    }
}
