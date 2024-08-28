<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalEntry extends Model
{
    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'user_id',
        'credit_id',
        'debit_id',
        'currency',
        'credit_doc_url',
        'debit_doc_url',
        'currency_amount',
        'currency_rate',
        'entry_title_id',
        'comment',
        'is_reviewed',
        'day_serial',
        'receiver_name',
        'cash_type',
        'approver_id',
        'approved_at',
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
    const CASH_ENTRY_RECEIVED   = 'received';
    const CASH_ENTRY_DELIVERED   = 'delivered';
    const CASH_ENTRY_TYPES  = [
        self::CASH_ENTRY_RECEIVED,
        self::CASH_ENTRY_DELIVERED,
    ];

    ////static functions
    public static function newJournalEntry(
        $title,
        $amount,
        $credit_id,
        $debit_id,
        $currency,
        $currency_amount = null,
        $currency_rate = null,
        $credit_doc_url = null,
        $debit_doc_url = null,
        $revert_entry_id = null,
        $comment = null,
        $cash_entry_type = null,
        $receiver_name = null,
        $approver_id = null,
        Carbon $approved_at = null,
        $user_id = null,
        $is_seeding = false,
    ): self|UnapprovedEntry|false {
        $entryTitle = EntryTitle::newOrCreateEntry($title);
        $day_serial = self::getTodaySerial();
        $newAccount = new self([
            "user_id"           => $user_id ?? Auth::id(),
            "entry_title_id"    =>  $entryTitle->id,
            "credit_id"     =>  $credit_id,
            "debit_id"      =>  $debit_id,
            "amount"        =>  $amount,
            "currency"      =>  $currency,
            "day_serial"        =>  $day_serial,
            "currency_amount"   =>  $currency_amount,
            "currency_rate"     =>  $currency_rate,
            "credit_doc_url"    =>  $credit_doc_url,
            "debit_doc_url"     =>  $debit_doc_url,
            "revert_entry_id"   =>  $revert_entry_id,
            "comment"           =>  $comment,
            "cash_entry_type"   =>  $cash_entry_type,
            "receiver_name"     =>  $receiver_name,
            "approver_id"       =>  $approver_id,
            "approved_at"       =>  $approved_at ? $approved_at->format('Y-m-d H:i:s') : null,
        ]);

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$is_seeding && !$loggedInUser->can('create', self::class)) return false;

        /** @var Account */
        $credit_account = Account::findOrFail($credit_id);
        /** @var Account */
        $debit_account = Account::findOrFail($debit_id);

        if ($debit_account->needsApproval($amount) || $credit_account->needsApproval($amount)) {
            return UnapprovedEntry::newEntry(
                $entryTitle->id,
                $credit_id,
                $debit_id,
                $credit_doc_url,
                $debit_doc_url,
                $currency,
                $currency_amount,
                $currency_rate,
                $comment,
                $receiver_name,
                $cash_entry_type
            );
        }

        try {
            ///hat2kd en el title mwgood fl entry types .. law msh mwgod ha create new entry type
            DB::transaction(function () use ($newAccount, $credit_account, $debit_account) {


                $new_credit_balance = $credit_account->updateBalance($this->amount);
                $new_debit_balance = $debit_account->updateBalance(-1 * $this->amount);

                $newAccount->credit_balance = $new_credit_balance;
                $newAccount->debit_balance = $new_debit_balance;

                $newAccount->save();
            });
            AppLog::info("Created entry", loggable: $newAccount);
            return $newAccount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create entry", desc: $e->getMessage());
            return false;
        }
    }

    private static function getTodaySerial()
    {
        $latestToday = self::where('created_at', Carbon::now()->format('Y-m-d'))->limit(1)->first();
        if ($latestToday) return $latestToday->day_serial;

        $maxSerial = DB::table('journal_entries')->selectRaw('MAX(day_serial) as latest_serial')->first()->latest_serial;
        return $maxSerial ? $maxSerial + 1 : 0;
    }

    ////model functions
    public function reviewEntry()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('review', $this)) return false;

        $this->is_reviewed = 1;
        $this->save();
    }

    public function revertEntry()
    {
        return self::newJournalEntry($this->amount, $this->debit_id, $this->credit_id, $this->currency, $this->currency_amount, $this->currency_rate, revert_entry_id: $this->id);
    }

    /** per entry */
    public function downloadCashReceipt() {}

    /** modal needed to query by day */
    public function downloadDailyTransaction(Carbon $day) {}

    ///scopes
    public function scopeByAccount($query, $account_id)
    {
        return $query->where('account_id', $account_id);
    }

    public function scopeByDay($query, Carbon $day)
    {
        return $query->whereDate('created_at', $day->format('Y-m-d'));
    }

    public function scopeByDaySerial($query, int $day_serial)
    {
        return $query->where('day_serial', $day_serial);
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
