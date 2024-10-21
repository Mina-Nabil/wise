<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PolicyComm extends Model
{
    const MORPH_TYPE = 'policy_comm';

    use HasFactory;

    protected $table = 'sold_policy_comms';
    protected $fillable = ['title', 'amount', 'is_manual'];

    public function editAmount($amount)
    {
        $this->load('sold_policy');
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this->sold_policy)) return false;
        try {
            AppLog::info("Editing sold policy cost amount", loggable: $this->sold_policy);
            DB::transaction(function () use ($amount) {
                $this->update([
                    "amount"   =>  $amount,
                ]);
                $this->sold_policy->calculateTotalPolicyComm();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing sold policy cost amount failed", loggable: $this->sold_policy, desc: $e->getMessage());
            return false;
        }
    }

    public function deleteCommission()
    {
        $this->load('sold_policy');
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('updatePayments', $this->sold_policy)) return false;
        try {
            AppLog::info("Deleting sold policy commission", loggable: $this->sold_policy);

            DB::transaction(function () {
                parent::delete();
                $this->sold_policy->calculateTotalPolicyComm();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Deleting policy comp commission failed", loggable: $this->sold_policy, desc: $e->getMessage());
            return false;
        }
    }

    ///scopes
    public function scopeAutomatic($query)
    {
        return $query->where('is_manual', false);
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
}
