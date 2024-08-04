<?php

namespace App\Models\Payments;

use App\Models\Insurance\Policy;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class PolicyCommConf extends Model
{
    const MORPH_TYPE = 'policy_comm_conf';

    use HasFactory;

    protected $table = 'policy_comm_conf';
    public $timestamps = false;
    protected $fillable = [
        'title', 'calculation_type', 'value', 'due_penalty', 'penalty_percent',
        'sales_out_only'
    ];

    ///model functions
    public function editInfo($title, $calculation_type, $value, $due_penalty = null, $penalty_percent = null, $sales_out_only = false)
    {
        $this->loadMissing('policy');
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this->policy)) return false;
        try {
            AppLog::info("Editing commission configuration", loggable: $this->policy);

            return $this->update([
                "title"   =>  $title,
                "calculation_type"  =>  $calculation_type,
                "value"             =>  $value,
                "due_penalty"       =>  $due_penalty,
                "penalty_percent"   =>  $penalty_percent,
                "sales_out_only"    =>  $sales_out_only
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing commission configuration failed", loggable: $this->policy, desc: $e->getMessage());
            return false;
        }
    }

    public function delete()
    {
        try {
            $this->loadMissing('policy');
            
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('update', $this->policy)) return false;

            AppLog::error("Deleting commission configuration", loggable: $this->policy);
            return parent::delete();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Deleting commission configuration failed", loggable: $this->policy, desc: $e->getMessage());
            return false;
        }
    }

    ///relations
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
