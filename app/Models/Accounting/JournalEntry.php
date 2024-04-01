<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'credit', 'debit', 'currency', 'doc_url', 'currency_amount', 'currency_rate'
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
    public static function newAccount($name, $nature, $type, $desc = null): self|false
    {
        $newAccount = new self([
            "name"  =>  $name,
            "nature"  =>  $nature,
            "type"  =>  $type,
            "desc"  =>  $desc,
            "balance"  =>  0,
        ]);
        try {
            $newAccount->save();
            AppLog::info("Created account", loggable: $newAccount);
            return $newAccount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create account", desc: $e->getMessage());
            return false;
        }
    }

    ////model functions
    public function editInfo($name, $nature, $type, $desc = null): bool
    {
        $this->update([
            "name"  =>  $name,
            "nature"  =>  $nature,
            "type"  =>  $type,
            "desc"  =>  $desc,
        ]);
        try {
            AppLog::info("Updating account", loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit account", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ////relations
    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }
    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }
}
