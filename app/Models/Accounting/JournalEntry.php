<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class JournalEntry extends Model
{
    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'credit',
        'debit',
        'currency',
        'doc_url',
        'currency_amount',
        'currency_rate'
    ];

    const CURRENCY_EGP  = 'EGP';
    const CURRENCY_USD  = 'USD';
    const CURRENCY_EUR  = 'EUR';
    const CURRENCY_TL   = 'TL';
    const CURRENCY_SAU  = 'SAR';
    const CURRENCIES = [
        self::CURRENCY_EGP,
        self::CURRENCY_USD,
        self::CURRENCY_EUR,
        self::CURRENCY_TL,
        self::CURRENCY_SAU,
    ];

    ////static functions
    public static function newJournalEntry(
        $amount,
        $credit_id,
        $debit_id,
        $currency,
        $currency_amount = null,
        $currency_rate = null,
        $credit_doc_url = null,
        $debit_doc_url = null,
        $revert_entry_id = null
    ): self|false {
        $newAccount = new self([
            "credit_id"     =>  $credit_id,
            "debit_id"      =>  $debit_id,
            "amount"        =>  $amount,
            "currency"      =>  $currency,
            "currency_amount"   =>  $currency_amount,
            "currency_rate"     =>  $currency_rate,
            "credit_doc_url"    =>  $credit_doc_url,
            "debit_doc_url"     =>  $debit_doc_url,
            "revert_entry_id"   =>  $revert_entry_id,
        ]);
        try {
            DB::transaction(function() use ($newAccount, $credit_id, $debit_id){
                
                /** @var Account */
                $credit_account = Account::findOrFail($credit_id);
                $new_credit_balance = $credit_account->updateBalance($this->amount);
                /** @var Account */
                $debit_account = Account::findOrFail($debit_id);
                $new_debit_balance = $debit_account->updateBalance(-1 * $this->amount);

                $newAccount->credit_balance = $new_credit_balance;
                $newAccount->debit_balance = $new_debit_balance;
                
                $newAccount->save();
                
            });
            AppLog::info("Created account", loggable: $newAccount);
            return $newAccount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create account", desc: $e->getMessage());
            return false;
        }
    }

    ////model functions
    public function revertEntry()
    {
        return self::newJournalEntry($this->amount, $this->debit_id, $this->credit_id, $this->currency, $this->currency_amount, $this->currency_rate, revert_entry_id: $this->id);
    }

    ////relations
    public function credit_account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function debit_account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
