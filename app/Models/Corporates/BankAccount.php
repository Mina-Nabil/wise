<?php

namespace App\Models\Corporates;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BankAccount extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'bank_account';

    const TYPE_DEBIT = 'debit';
    const TYPE_CREDIT = 'credit';
    const TYPES = [
        self::TYPE_DEBIT,
        self::TYPE_CREDIT,
    ];

    protected $table = 'corporate_bank_accounts';
    protected $fillable = [
        'bank_name',
        'account_number',
        'owner_name',
        'is_default',
        'evidence_doc',
        'iban',
        'bank_branch',
    ];

    ///model functions
    public function setAsDefault()
    {
        try {
            DB::transaction(function () {
                DB::table('corporate_bank_accounts')->where('corporate_id', $this->corporate_id)->update([
                    'is_default'    =>  false
                ]);
                $this->is_default = true;
                $this->save();
            });
            AppLog::info('Bank account set as default', loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Can\'t set bank account as default', desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function editInfo($type, $bank_name, $account_number, $owner_name, $evidence_doc = null, $iban = null, $bank_branch = null)
    {
        try {
            $this->update([
                "type"              =>  $type,
                "bank_name"         =>  $bank_name,
                "account_number"    =>  $account_number,
                "owner_name"        =>  $owner_name,
                "evidence_doc"      =>  $evidence_doc,
                "iban"              =>  $iban,
                "bank_branch"       =>  $bank_branch,
            ]);
            AppLog::info("Editing bank account", loggable: $this);
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing bank account failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}
