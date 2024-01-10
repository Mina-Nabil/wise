<?php

namespace App\Models\Offers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\User;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OfferDiscount extends Model
{
    use HasFactory;

    protected $table = 'offer_discounts';
    protected $fillable = [
        'value',
        'note',
        'type',
        'user_id',
    ];

    const TYPE_COMMISSION = 'commission';
    const TYPE_NO_CLAIM = 'no_claim';
    const TYPE_FAMILY = 'family';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_COMMISSION,
        self::TYPE_NO_CLAIM,
        self::TYPE_FAMILY,
        self::TYPE_OTHER
    ];

    ////static functions


    ////model functions
    public function editInfo($type, $value, $note = null)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        try {
            if ($this->update([
                "type"  =>  $type,
                "value"  =>  $value,
                "note"  =>  $note
            ])) {
                AppLog::info("Offer discount edited", loggable: $this);
                return true;
            }
            return false;
        } catch (Exception $e) {
            AppLog::error("Can't edit discount", desc: $e->getMessage(), loggable: $this);
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
                AppLog::info("Discount deleted", loggable: $tmpOffer);
                return true;
            } else {
                AppLog::error("Discount deletion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("Discount deletetion failed", loggable: $this, desc: $e->getMessage());
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
