<?php

namespace App\Models\Payments;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommProfile extends Model
{
    use HasFactory;

    const TYPE_SALES_IN = 'sales_in';
    const TYPE_SALES_OUT = 'sales_out';
    const TYPE_OVERRIDE = 'override';

    const TYPES = [
        self::TYPE_SALES_IN,
        self::TYPE_SALES_OUT,
        self::TYPE_OVERRIDE,
    ];

    protected $fillable = [
        'title', 'type', 'per_policy', 'desc'
    ];

    ///static functions
    public static function newCommProfile(
        $type, //enum one of TYPES
        bool $per_policy, //switch
        $user_id = null, //can be linked to user
        string $title = null, //can be null if a user is selected 
        string $desc = null
    ): self|bool {
        try {
            $formattedTitle = $title;
            if ($user_id & !$formattedTitle) {
                $user = User::findOrFail($user_id);
                $formattedTitle = "$user->username's $type";
            }
            $newComm = new self([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "user_id"       =>  $user_id,
                "title"         =>  $formattedTitle,
                "desc"          =>  $desc,
            ]);
            AppLog::error("creating new comm profile" );
            $newComm->save();
            return $newComm;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create comm profile", desc: $e->getMessage());
            return false;
        }
    }

    ///model functions
    public function editProfile(
        $type,
        bool $per_policy,
        string $title = null,
        string $desc = null
    ) {
        try {
            $this->update([
                "type"          =>  $type,
                "per_policy"    =>  $per_policy,
                "title"         =>  $title,
                "desc"          =>  $desc,
            ]);
            AppLog::info("Can't edit comm profile",  loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit comm profile", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
