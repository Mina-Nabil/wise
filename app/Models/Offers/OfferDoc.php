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
use Illuminate\Support\Facades\Storage;

class OfferDoc extends Model
{
    use HasFactory;
    const FILES_DIRECTORY = 'offers/docs/';

    protected $table = 'offer_docs';
    protected $fillable = [
        'name',
        'url',
        'user_id',
    ];
    ////static functions


    ////model functions
    public function delete()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $this)) throw new UnauthorizedException();
        try {
            $this->loadMissing('offer');
            $tmpOffer = $this->offer;
            if (parent::delete()) {
                Storage::delete($this->url);
                AppLog::info("File deleted", loggable: $tmpOffer);
                return true;
            } else {
                AppLog::error("File deletetion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("File deletetion failed", loggable: $this, desc: $e->getMessage());
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
        return $this->belongsTo(User::class, 'user_id');
    }
}
