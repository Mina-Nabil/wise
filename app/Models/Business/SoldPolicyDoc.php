<?php

namespace App\Models\Business;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SoldPolicyDoc extends Model
{
    use HasFactory;
    const FILES_DIRECTORY = 'sold_policies/docs/';

    protected $table = 'sold_policy_docs';
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
        $this->loadMissing('sold_policy');
        if (!$loggedInUser->can('update', $this->sold_policy)) throw new UnauthorizedException();
        try {
            $tmpSoldPolicy = $this->sold_policy;
            if (parent::delete()) {
                Storage::disk('s3')->delete($this->url);
                AppLog::info("File deleted", loggable: $tmpSoldPolicy);
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
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
