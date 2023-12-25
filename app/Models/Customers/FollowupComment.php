<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowupComment extends Model
{
    use HasFactory;
    protected $table = 'followups_comments';

    protected $fillable = [
        'comment',
        'user_id'
    ];

    ///model functions
    public function delete()
    {
        try {
            if (parent::delete()) {
                AppLog::info("Followup comment deleted");
                return true;
            } else {
                AppLog::error("Followup comment can't be delete", desc: "No stack found", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Followup comment can't be delete", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function followup(): BelongsTo
    {
        return $this->belongsTo(Followup::class);
    }
}
