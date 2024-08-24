<?php

namespace App\Models\Offers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OptionDoc extends Model
{
    use HasFactory;
    const FILES_DIRECTORY = 'offers/options/';

    protected $table = 'option_docs';
    protected $fillable = [
        'name',
        'url',
        'user_id',
    ];

    //model functions
    public function delete()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $this)) throw new UnauthorizedException();
        try {
            $this->load('option');
            $tmpOption = $this->option;
            if (parent::delete()) {
                Storage::disk('s3')->delete($this->url);
                AppLog::info("Option File deleted", loggable: $tmpOption);
                return true;
            } else {
                AppLog::error("Option File deletetion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("File deletetion failed", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    ///relations
    public function option(): BelongsTo
    {
        return $this->belongsTo(OfferOption::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
