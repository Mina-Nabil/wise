<?php

namespace App\Models\Payments;

use App\Models\Users\AppLog;
use App\Models\Users\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubSalesComm extends Model
{
    const MORPH_TYPE = 'sub_sales_comm';

    const SOURCE_MANUAL = 'manual';
    const SOURCE_TARGET = 'target';
    const SOURCE_CLIENT_PAYMENT = 'client_payment';
    const SOURCES = [
        self::SOURCE_MANUAL,
        self::SOURCE_TARGET,
        self::SOURCE_CLIENT_PAYMENT
    ];

    use HasFactory;

    protected $fillable = [
        'sales_comm_id',
        'source',
        'target_id',
        'client_payment_id',
        'title',
        'percentage',
        'amount',
        'note'
    ];

    ///model functions
    /** Only manually added subs can be deleted. */
    public function deleteSub()
    {
        if ($this->source !== self::SOURCE_MANUAL) return false;

        /** @var User */
        $user = Auth::user();
        $this->load('sales_comm');
        if (!$user->can('update', $this->sales_comm)) return false;

        try {
            DB::transaction(function () {
                $salesComm = $this->sales_comm;
                $this->delete();
                $salesComm->recalculateAmount();
            });
            AppLog::info("Sub sales comm deleted", loggable: $this->sales_comm);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete sub sales comm", desc: $e->getMessage(), loggable: $this->sales_comm);
            return false;
        }
    }

    ///attributes
    public function getIsManualAttribute()
    {
        return $this->source === self::SOURCE_MANUAL;
    }

    ///relations
    public function sales_comm(): BelongsTo
    {
        return $this->belongsTo(SalesComm::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }

    public function client_payment(): BelongsTo
    {
        return $this->belongsTo(ClientPayment::class);
    }
}
