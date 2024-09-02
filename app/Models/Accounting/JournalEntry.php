<?php

namespace App\Models\Accounting;

use App\Helpers\Helpers;
use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use ArPHP\I18N\Arabic;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JournalEntry extends Model
{
    const MORPH_TYPE = 'journal_entry';

    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'user_id',
        'credit_id',
        'debit_id',
        'currency',
        'credit_doc_url',
        'debit_doc_url',
        'amount',
        'currency_amount',
        'currency_rate',
        'entry_title_id',
        'comment',
        'is_reviewed',
        'day_serial',
        'cash_entry_type',
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
            "currency_amount"   =>  $currency_amount ?? $amount,
            "currency_rate"     =>  $currency_rate ?? 1,
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
                $amount,
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
            DB::transaction(function () use ($amount, $newAccount, $credit_account, $debit_account) {
                $new_credit_balance = $credit_account->updateBalance($amount);
                $new_debit_balance = $debit_account->updateBalance(-1 * $amount);
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
        $latestToday = self::whereDate('created_at', Carbon::today())->limit(1)->first();
        if ($latestToday) return $latestToday->day_serial;

        $maxSerial = DB::table('journal_entries')->selectRaw('MAX(day_serial) as latest_serial')->first()->latest_serial;
        return $maxSerial ? $maxSerial + 1 : 1;
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
    public function downloadCashReceipt()
    {

        $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        if ($this->cash_entry_type == self::CASH_ENTRY_RECEIVED) {
            $activeSheet = $newFile->getSheet(1);
        } elseif ($this->cash_entry_type == self::CASH_ENTRY_DELIVERED) {
            $activeSheet = $newFile->getSheet(2);
        } else {
            return false;
        }
        $Arabic = new Arabic('Numbers');

        $Arabic->setNumberFeminine(1);
        $Arabic->setNumberFormat(1);
    
        
        $text = $Arabic->int2str($this->amount);
        $number_format = number_format($this->amount, 2);
    
        $activeSheet->getCell('B7')->setValue($this->amount);
        $activeSheet->getCell('F7')->setValue(Carbon::parse($this->created_at)->format('Y / m / d'));
        $activeSheet->getCell('B9')->setValue("/    .......................................{$this->receiver_name}................................................"  );
        $activeSheet->getCell('B11')->setValue("/    ......{$number_format}...... نقدا / شيك رقم : ............................................				"  );
        $activeSheet->getCell('B13')->setValue("/.............................$text............................................				"  );

        // foreach ($leads as $lead) {
        //     $activeSheet->getCell('A' . $i)->setValue($lead->id);
        //     $activeSheet->getCell('B' . $i)->setValue($lead->first_name);
        //     $activeSheet->getCell('C' . $i)->setValue($lead->last_name);
        //     $activeSheet->getCell('D' . $i)->setValue($lead->arabic_first_name);
        //     $activeSheet->getCell('E' . $i)->setValue($lead->arabic_last_name);
        //     $activeSheet->getCell('F' . $i)->setValue($lead->telephone1);
        //     $activeSheet->getCell('G' . $i)->setValue($lead->telephone2);
        //     $activeSheet->getCell('H' . $i)->setValue($lead->owner?->username);
        //     $i++;
        // }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "cash_receipt.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    /** modal needed to query by day */
    public function downloadDailyTransaction(Carbon $day) {

        // $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        // if (!$template) {
        //     throw new Exception('Failed to read template file');
        // }
        // $newFile = $template->copy();
        // $activeSheet = $newFile->getSheet(3);
    
        // $activeSheet->getCell('B3')->setValue("كشف حركة الخزينة عن يوم ال" . Helpers::dayInArabic($day->dayOfWeek) . "  الموافق $day->day / $day->month / $day->year						");
        // $trans = self::byDay($day)->get();
        // $i = 6;
        // foreach ($trans as $t) {
        //     $activeSheet->getCell('A' . $i)->setValue($lead->id);
        //     $activeSheet->getCell('B' . $i)->setValue($lead->first_name);
        //     $activeSheet->getCell('C' . $i)->setValue($lead->last_name);
        //     $activeSheet->getCell('D' . $i)->setValue($lead->arabic_first_name);
        //     $activeSheet->getCell('E' . $i)->setValue($lead->arabic_last_name);
        //     $activeSheet->getCell('F' . $i)->setValue($lead->telephone1);
        //     $activeSheet->getCell('G' . $i)->setValue($lead->telephone2);
        //     $activeSheet->getCell('H' . $i)->setValue($lead->owner?->username);
        //     $i++;
        // }

        // $writer = new Xlsx($newFile);
        // $file_path = SoldPolicy::FILES_DIRECTORY . "cash_receipt.xlsx";
        // $public_file_path = storage_path($file_path);
        // $writer->save($public_file_path);

        // return response()->download($public_file_path)->deleteFileAfterSend(true);


    }

    ///scopes
    public function scopeByAccount($query, $account_id)
    {
        return $query->where(function ($q) use ($account_id) {
            $q->where('debit_id', $account_id)->orwhere("credit_id", $account_id);
        });
    }

    public function scopeByDay($query, Carbon $day)
    {
        return $query->whereDate('created_at', $day->format('Y-m-d'));
    }

    public function scopeByDaySerial($query, int $day_serial)
    {
        return $query->where('day_serial', $day_serial);
    }

    public function scopeBetween($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween('created_at', [
            $from->format('Y-m-d 00:00:00'),
            $to->format('Y-m-d 23:59:59')
        ]);
    }

    ////relations
    public function credit_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_id');
    }

    public function debit_account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_id');
    }

    public function entry_title(): BelongsTo
    {
        return $this->belongsTo(EntryTitle::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
