<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    protected $fillable = [
        'name',
        'desc',
        'nature',
        'type',
        'limit',
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
    public static function newAccount($name, $nature, $type, $limit, $desc = null): self|false
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('create', self::class)) return false;

        $newAccount = new self([
            "name"  =>  $name,
            "nature"  =>  $nature,
            "type"  =>  $type,
            "desc"  =>  $desc,
            "limit"  =>  $limit,
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
    public function downloadAccountDetails(Carbon $from, Carbon $to) {}


    /** returns new balance after update */
    public function updateBalance($amount)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        $this->balance = $this->balance + $amount;
        try {
            $this->save();
            return $this->balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    public function needsApproval($amount)
    {
        return $this->limit <= $amount;
    }

    public function editInfo($name, $nature, $type, $limit, $desc = null): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        try {
            $this->update([
                "name"  =>  $name,
                "nature"  =>  $nature,
                "type"  =>  $type,
                "desc"  =>  $desc,
                "limit"  =>  $limit,
            ]);
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
