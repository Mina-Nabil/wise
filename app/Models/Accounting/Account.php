<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'account_types';
    protected $fillable = [
        'name',
        'desc',
        'nature',
        'type',
        'balance'
    ];

    const NATURE_CREDIT = 'credit';
    const NATURE_DEBIT = 'debit';
    const NATURES = [
        self::NATURE_DEBIT,
        self::NATURE_CREDIT,
    ];

    const TYPE_EXPENSE = 'expense';
    const TYPE_REVENUE = 'revenue';
    const TYPE_ASSET = 'asset';
    const TYPE_LIABILITY = 'liability';
    const TYPES = [
        self::TYPE_EXPENSE,
        self::TYPE_REVENUE,
        self::TYPE_ASSET,
        self::TYPE_LIABILITY,
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
    /** returns new balance after update */
    public function updateBalance($amount)
    {
        $this->balance = $this->balance + $amount;
        try {
            $this->save();
            return $this->balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }


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
