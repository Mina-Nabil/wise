<?php

namespace App\Models\Corporates;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Relative extends Model
{
    use HasFactory;

    protected $table = 'corporate_bank_accounts';
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'is_default',
        'evidence_doc',
        'bank_address',
        'iban',
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

    public function editInfo($bank_name, $account_number, $account_name, $evidence_doc = null, $bank_address = null, $iban = null)
    {
        try {
            $this->update([
                "bank_name"         =>  $bank_name,
                "account_number"    =>  $account_number,
                "account_name"      =>  $account_name,
                "evidence_doc"      =>  $evidence_doc,
                "bank_address"      =>  $bank_address,
                "iban"              =>  $iban,
            ]);
            AppLog::info("Editing corporate bank account", loggable: $this);
            return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Editing corporate bank account failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///relations
    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }
}
