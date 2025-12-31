<?php

namespace App\Models\Accounting;

use App\Helpers\Helpers;
use App\Models\Business\SoldPolicy;
use App\Models\Payments\CommProfilePayment;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use ArPHP\I18N\Arabic;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JournalEntry extends Model
{
    const MORPH_TYPE = 'journal_entry';
    const FILES_DIRECTORY = 'journal_entries/';

    const INVOICE_CREATED_ID = 7;
    const INVOICE_PAID_ID = 8;

    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'user_id',
        'entry_title_id',
        'comment',
        'is_reviewed',
        'day_serial',
        'receiver_name',
        'cash_entry_type',
        'approver_id',
        'approved_at',
        'revert_entry_id',
        'cash_serial',
        'extra_note'
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

    const CASH_ID = 2874;
    // const CHEQUE_ID = 2920;

    //atr
    public function getCashTitleAttribute()
    {
        $oppAccounts = $this->accounts()->wherePivot('nature', $this->cash_entry_type == self::CASH_ENTRY_RECEIVED ? 'credit' : 'debit')->get();
        return $this->entry_title->name . " - " . $this->comment . " - " . $oppAccounts->implode('name', ',');
    }

    ////static functions

    /** 
     * @param array $accounts example [
     *  $account_id => [
     *  'nature'    =>  'debit' ,
     *  'amount'    =>  102000 ,
     *  'currency' => 'USD' ,
     *  'currency_amount' => 200 ,
     *  'currency_rate' => 50.1 , 
     *  'doc_url' => url
     * ]
     * ]
     */
    public static function newJournalEntry(
        $entry_title_id, //changed
        $cash_entry_type = null,
        $receiver_name = null,
        Carbon $approved_at = null,
        $revert_entry_id = null,
        $comment = null,
        $user_id = null,
        $approver_id = null,
        $skip_auth = false,
        $accounts = [],
        $extra_note = null
    ): self|UnapprovedEntry|string|false {

        /** @var EntryTitle */
        $entry = EntryTitle::findOrFail($entry_title_id);
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$skip_auth && !$loggedInUser->can('createEntry', $entry)) return false;


        $total_debit = 0;
        $total_credit = 0;

        foreach ($accounts as $ac) {
            if ($ac['nature'] == 'debit') $total_debit += round($ac['amount'], 2);
            else $total_credit += round($ac['amount'], 2);
        }

        if (round($total_credit - $total_debit) != 0) return "Debit not equal to credit. Debit is $total_debit & Credit is $total_credit";


        //////////////////////////////loading & checking data//////////////////////////////

        $day_serial = self::getTodaySerial();

        if (!$revert_entry_id && !$approver_id && !$entry->isEntryValid($accounts)) return UnapprovedEntry::newEntry(
            $entry_title_id,
            $cash_entry_type,
            $receiver_name,
            $comment,
            $accounts,
            $extra_note
        );


        ///////////////////////////////preparing entry
        $updates = [
            "user_id"           => $user_id ?? Auth::id(),
            "entry_title_id"    =>  $entry_title_id,
            "day_serial"        =>  $day_serial,
            "revert_entry_id"   =>  $revert_entry_id,
            "cash_entry_type"   =>  $cash_entry_type,
            "receiver_name"     =>  $receiver_name,
            "approver_id"       =>  $approver_id,
            "approved_at"       =>  $approved_at ? $approved_at->format('Y-m-d H:i:s') : null,
            "comment"           =>  $comment,
            "extra_note"           =>  $extra_note,
        ];
        if ($cash_entry_type) {
            $updates['cash_serial'] = self::getCashSerial($cash_entry_type);
        }
        $newEntry = new self($updates);

        try {
            ///////////////////////////////saving entry
            DB::transaction(function () use ($newEntry, $accounts, $skip_auth) {

                $newEntry->save();
                foreach ($accounts as $account_id => $entry_arr) {
                    /** @var Account */
                    $account = Account::findOrFail($account_id);
                    $entry_arr['account_balance'] =  $account->updateBalance($entry_arr['amount'], $entry_arr['nature'], $skip_auth);
                    if ($entry_arr['currency'] && $entry_arr['currency'] != self::CURRENCY_EGP && $entry_arr['currency'] == $account->default_currency) {
                        $entry_arr['account_foreign_balance'] =  $account->updateForeignBalance($entry_arr['amount'], $entry_arr['nature'], $skip_auth);
                    }
                    $newEntry->accounts()->attach($account_id, $entry_arr);
                }
            });
            AppLog::info("Created entry", loggable: $newEntry);
            return $newEntry;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create entry", desc: $e->getMessage());
            return false;
        }
    }

    /**
     * Refresh all account balances by recalculating from scratch
     * This function:
     * 1. Calculates starting balance for each account (before first entry)
     * 2. Replays all entries chronologically to rebuild balances
     * 3. Updates both account balances and pivot table snapshots
     * 
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int]
     */
    public static function refreshAllBalances(): array
    {
        try {
            DB::beginTransaction();

            $accounts = Account::all();
            $accountsProcessed = 0;
            $errors = [];

            foreach ($accounts as $account) {
                try {
                    // Get all journal entries for this account, ordered chronologically
                    $entries = self::byAccount($account->id)
                        ->orderBy('created_at', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();

                    if ($entries->isEmpty()) {
                        // No entries for this account, reset to 0
                        $account->balance = 0;
                        $account->foreign_balance = 0;
                        $account->save();
                        $accountsProcessed++;
                        continue;
                    }

                    // Get the first entry's pivot data directly from the pivot table
                    $firstEntry = $entries->first();
                    $firstPivot = DB::table('entry_accounts')
                        ->where('journal_entry_id', $firstEntry->id)
                        ->where('account_id', $account->id)
                        ->first();

                    if (!$firstPivot) {
                        // Skip if pivot data not found (shouldn't happen, but safety check)
                        $accountsProcessed++;
                        continue;
                    }

                    // Calculate starting balance: reverse the effect of the first entry
                    $startingBalance = $firstPivot->account_balance;
                    $entryAmount = $firstPivot->amount;
                    $entryNature = $firstPivot->nature;
                    $accountNature = $account->nature;

                    // Reverse the first entry's effect
                    if ($entryNature == $accountNature) {
                        // Entry increased the balance, so subtract to get starting balance
                        $startingBalance = abs($startingBalance - $entryAmount);
                    } else {
                        // Entry decreased the balance, so add to get starting balance
                        $startingBalance = abs($startingBalance + $entryAmount);
                    }

                    // Calculate starting foreign balance if applicable
                    $startingForeignBalance = 0;
                    if ($firstPivot->currency && $firstPivot->currency != self::CURRENCY_EGP && $firstPivot->currency == $account->default_currency) {
                        $startingForeignBalance = $firstPivot->account_foreign_balance ?? 0;
                        $entryForeignAmount = $firstPivot->currency_amount ?? 0;

                        if ($entryNature == $accountNature) {
                            $startingForeignBalance = abs($startingForeignBalance - $entryForeignAmount);
                        } else {
                            $startingForeignBalance = abs($startingForeignBalance + $entryForeignAmount);
                        }
                    }

                    // Reset account balances to starting values
                    $account->balance = $startingBalance;
                    $account->foreign_balance = $startingForeignBalance;
                    $account->save();

                    // Now replay all entries chronologically
                    $currentBalance = $startingBalance;
                    $currentForeignBalance = $startingForeignBalance;

                    foreach ($entries as $entry) {
                        // Get pivot data directly from the pivot table
                        $pivot = DB::table('entry_accounts')
                            ->where('journal_entry_id', $entry->id)
                            ->where('account_id', $account->id)
                            ->first();

                        if (!$pivot) {
                            // Skip if pivot data not found
                            continue;
                        }

                        $entryAmount = $pivot->amount;
                        $entryNature = $pivot->nature;

                        // Calculate new balance using the same logic as updateBalance()
                        $balanceChange = $entryAmount;
                        if ($entryNature != $accountNature) {
                            $balanceChange = -1 * $balanceChange;
                        }

                        $currentBalance = $currentBalance + $balanceChange;

                        // Update account balance
                        $account->balance = $currentBalance;
                        $account->save();

                        // Update pivot table snapshot
                        DB::table('entry_accounts')
                            ->where('journal_entry_id', $entry->id)
                            ->where('account_id', $account->id)
                            ->update(['account_balance' => $currentBalance]);

                        // Handle foreign balance if applicable
                        if ($pivot->currency && $pivot->currency != self::CURRENCY_EGP && $pivot->currency == $account->default_currency) {
                            $entryForeignAmount = $pivot->currency_amount ?? 0;
                            $foreignBalanceChange = $entryForeignAmount;

                            if ($entryNature != $accountNature) {
                                $foreignBalanceChange = -1 * $foreignBalanceChange;
                            }

                            $currentForeignBalance = $currentForeignBalance + $foreignBalanceChange;

                            // Update account foreign balance
                            $account->foreign_balance = $currentForeignBalance;
                            $account->save();

                            // Update pivot table foreign balance snapshot
                            DB::table('entry_accounts')
                                ->where('journal_entry_id', $entry->id)
                                ->where('account_id', $account->id)
                                ->update(['account_foreign_balance' => $currentForeignBalance]);
                        } else {
                            // Ensure foreign balance is set to 0 or existing value if not applicable
                            DB::table('entry_accounts')
                                ->where('journal_entry_id', $entry->id)
                                ->where('account_id', $account->id)
                                ->update(['account_foreign_balance' => 0]);
                        }
                    }

                    $accountsProcessed++;
                } catch (Exception $e) {
                    $errors[] = "Account ID {$account->id} ({$account->name}): " . $e->getMessage();
                    report($e);
                }
            }

            DB::commit();

            $message = "Successfully refreshed balances for {$accountsProcessed} accounts.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return [
                'success' => true,
                'message' => $message,
                'accounts_processed' => $accountsProcessed,
                'errors' => $errors
            ];
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            AppLog::error("Failed to refresh balances", desc: $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to refresh balances: ' . $e->getMessage(),
                'accounts_processed' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    private static function getCashSerial($type)
    {
        return (self::where('cash_entry_type', $type)
            ->orderByDesc('cash_serial')
            ->limit(1)->first()?->cash_serial ?? 0) + 1;
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
        return $this->save();
    }

    public function revertEntry()
    {
        return DB::transaction(function () {
            // Lock this entry to prevent concurrent reversals
            $entry = self::where('id', $this->id)->lockForUpdate()->first();
            
            if (!$entry) {
                return false;
            }
            
            // Check if this entry has already been reverted
            $existingReversal = self::where('revert_entry_id', $this->id)->first();
            if ($existingReversal) {
                AppLog::warning("Entry already reverted", loggable: $this);
                return "This entry has already been reverted (Reversal Entry ID: {$existingReversal->id})";
            }
            
            // Prevent reverting an entry that is itself a reversal
            if ($this->revert_entry_id) {
                AppLog::warning("Cannot revert a reversal entry", loggable: $this);
                return "Cannot revert an entry that is itself a reversal";
            }
            
            $entry->load('accounts');
            $accounts = [];
            foreach ($entry->accounts as $ac) {
                $accounts[$ac->id] = [
                    'nature'    =>  $ac->pivot->nature == 'debit' ? 'credit' : 'debit',
                    'amount'    =>  $ac->pivot->amount,
                    'currency' => $ac->pivot->currency,
                    'currency_amount' => $ac->pivot->currency_amount,
                    'currency_rate' => $ac->pivot->currency_rate,
                    'doc_url' => $ac->pivot->doc_url,
                ];
            }
            
            return self::newJournalEntry(
                $entry->entry_title_id,
                cash_entry_type: null,
                receiver_name: null,
                revert_entry_id: $entry->id,
                comment: $entry->comment,
                accounts: $accounts
            );
        });
    }

    public function uploadDoc($account_id, $file_url)
    {
        return $this->accounts()->updateExistingPivot($account_id, ['doc_url' => $file_url]);
    }

    public function downloadDoc($account_id)
    {
        $account_entry = $this->accounts()->where('accounts.id', $account_id)->first();
        $fileContents = Storage::disk('s3')->get($account_entry->pivot->doc_url);
        $fileExtension = last(explode('.', $account_entry->pivot->doc_url));
        $headers = [
            'Content-Type' => 'application/octet-stream',
        ];
        if ($fileExtension)
            $headers['Content-Disposition'] = 'attachment; filename="' . $account_entry->name . "_" . $this->id . "_doc." . $fileExtension . '"';

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    /** per entry */
    public function downloadCashReceipt()
    {

        $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();

        $Arabic = new Arabic('Numbers');

        $Arabic->setNumberFeminine(1);
        $Arabic->setNumberFormat(1);

        if ($this->cash_entry_type == self::CASH_ENTRY_RECEIVED) {
            $activeSheet = $newFile->getSheet(1);
            $number_text = $Arabic->int2str($this->debit_total) . ' جنيه لا غير';
            $number_format = number_format($this->debit_total, 2);
        } elseif ($this->cash_entry_type == self::CASH_ENTRY_DELIVERED) {
            $activeSheet = $newFile->getSheet(2);
            $number_text = $Arabic->int2str($this->credit_total) . ' جنيه لا غير';
            $number_format = number_format($this->credit_total, 2);
        } else {
            return false;
        }

        $activeSheet->getCell('B7')->setValue($number_format);
        $activeSheet->getCell('F5')->setValue(str_pad($this->cash_serial, 5, '0', STR_PAD_LEFT));
        $activeSheet->getCell('F7')->setValue(Carbon::parse($this->created_at)->format('d / m / Y'));
        $activeSheet->getCell('B9')->setValue("/      {$this->receiver_name}");
        $activeSheet->getCell('B11')->setValue("/     {$number_text}       نقدا / شيك رقم : ............................................				");
        $activeSheet->getCell('B13')->setValue("/    {$this->cash_title}				");



        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "cash_receipt.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    /** modal needed to query by day */
    public static function downloadDailyTransaction(Carbon $day)
    {

        $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getSheet(3);

        $activeSheet->getCell('B3')->setValue("كشف حركة الخزينة عن يوم ال" . Helpers::dayInArabic($day->dayOfWeek) . "  الموافق $day->day / $day->month / $day->year						");

        $trans = self::byDay($day)->cashOnly()->get();

        $activeSheet->getCell('E6')->setValue("رصيد المرحل");
        $activeSheet->getCell('D6')->setValue(self::getLatestCashBalance($day));

        $i = 7;
        /** @var self */
        foreach ($trans as $t) {
            $cash_ac = $t->accounts()->where('accounts.id', self::CASH_ID)->first();
            if ($cash_ac->pivot->nature == 'credit') {
                foreach ($t->accounts()->wherePivot('nature', 'debit')->get() as $ac) {
                    $activeSheet->getCell('I' . $i)->setValue($ac->name);
                    $activeSheet->getCell('H' . $i)->setValue(
                        $ac->pivot->amount
                    );
                    $i++;
                }
                $activeSheet->getCell('F' . $i)->setValue($t->cash_serial);
            } else {
                foreach ($t->accounts()->wherePivot('nature', 'credit')->get() as $ac) {
                    $activeSheet->getCell('E' . $i)->setValue($ac->name);
                    $activeSheet->getCell('D' . $i)->setValue(
                        $ac->pivot->amount
                    );
                    $i++;
                }
            }
            $activeSheet->getCell('B' . $i)->setValue($t->cash_serial);
            $i++;
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "daily_cash.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public static function getLatestCashBalance(Carbon $day)
    {
        $latestCashEntry = self::whereDate('created_at', '<', $day->format('Y-m-d'))
            ->whereNotNull('cash_entry_type')
            ->orderByDesc('created_at')
            ->limit(1)
            ->first();
        return $latestCashEntry ? $latestCashEntry->accounts()->where('accounts.id', self::CASH_ID)->first()->pivot->account_balance : 0;
    }

    /**
     * Download journal entries with account hierarchy
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function downloadJournalEntries(Carbon $from, Carbon $to, $account_id = null)
    {
        $newFile = new Spreadsheet();
        $activeSheet = $newFile->getSheet(0);

        // Set headers
        $activeSheet->setCellValue('A1', 'Transaction ID');
        $activeSheet->setCellValue('B1', 'Date');
        $activeSheet->setCellValue('C1', 'Time');
        $activeSheet->setCellValue('D1', 'Main Account Code');
        $activeSheet->setCellValue('E1', 'Main Account Name');
        $activeSheet->setCellValue('F1', 'GL Account Code');
        $activeSheet->setCellValue('G1', 'GL Account Name');
        $activeSheet->setCellValue('H1', 'Account Code');
        $activeSheet->setCellValue('I1', 'Account Name');
        $activeSheet->setCellValue('J1', 'Sub Account Code');
        $activeSheet->setCellValue('K1', 'Sub Account Name');
        $activeSheet->setCellValue('L1', 'Debit');
        $activeSheet->setCellValue('M1', 'Credit');
        $activeSheet->setCellValue('N1', 'Transaction Description');
        $activeSheet->setCellValue('O1', 'User ID');
        $activeSheet->setCellValue('P1', 'Approved');

        // Style headers
        $activeSheet->getStyle('A1:P1')->getFont()->setBold(true);
        $activeSheet->getStyle('A1:P1')->getFill()->setFillType(Fill::FILL_SOLID);
        $activeSheet->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('FFFFFF00'); // Yellow background

        // Get journal entries with accounts
        $entries = self::between($from, $to)
            ->when($account_id, function ($query) use ($account_id) {
                $query->whereHas('accounts', function ($query) use ($account_id) {
                    $query->where('account_id', $account_id);
                });
            })
            ->with(['accounts.parent_account', 'accounts.parent_account.parent_account', 'accounts.parent_account.parent_account.parent_account', 'creator', 'entry_title'])
            ->orderBy('id', 'desc')
            ->get();

        $row = 2;
        foreach ($entries as $entry) {
            foreach ($entry->accounts as $accountEntry) {
                $account = $accountEntry->pivot;

                // Get account hierarchy
                $hierarchy = self::getAccountHierarchy($accountEntry);

                // Set transaction details
                $activeSheet->setCellValue('A' . $row, $entry->id . ' / #' . $entry->day_serial);
                $activeSheet->setCellValue('B' . $row, Carbon::parse($entry->created_at)->format('d/m/Y'));
                $activeSheet->setCellValue('C' . $row, Carbon::parse($entry->created_at)->format('h:i:s A'));
                $activeSheet->setCellValue('M' . $row, $entry->comment);
                $activeSheet->setCellValue('N' . $row, $entry->creator->username ?? '');
                $activeSheet->setCellValue('O' . $row, ucfirst($account->nature));

                // Set account hierarchy columns
                $activeSheet->setCellValue('D' . $row, $hierarchy['main_code'] ?? '');
                $activeSheet->setCellValue('E' . $row, $hierarchy['main_name'] ?? '');
                $activeSheet->setCellValue('F' . $row, $hierarchy['gl_code'] ?? '');
                $activeSheet->setCellValue('G' . $row, $hierarchy['gl_name'] ?? '');
                $activeSheet->setCellValue('H' . $row, $hierarchy['account_code'] ?? '');
                $activeSheet->setCellValue('I' . $row, $hierarchy['account_name'] ?? '');
                $activeSheet->setCellValue('J' . $row, $hierarchy['sub_code'] ?? '');
                $activeSheet->setCellValue('K' . $row, $hierarchy['sub_name'] ?? '');

                // Set debit/credit amounts
                if ($account->nature == 'debit') {
                    $activeSheet->setCellValue('L' . $row, number_format($account->amount, 2));
                    $activeSheet->setCellValue('M' . $row, '');
                } else {
                    $activeSheet->setCellValue('L' . $row, '');
                    $activeSheet->setCellValue('M' . $row, number_format($account->amount, 2));
                }

                $row++;
            }
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "journal_entries.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    /**
     * Get account hierarchy for export
     * @param Account $account
     * @return array
     */
    private static function getAccountHierarchy($account)
    {
        $hierarchy = [
            'main_code' => '',
            'main_name' => '',
            'gl_code' => '',
            'gl_name' => '',
            'account_code' => '',
            'account_name' => '',
            'sub_code' => '',
            'sub_name' => ''
        ];

        // Load the account with its relationships
        // $account->loadMissing(['parent_account', 'parent_account.parent_account', 'parent_account.parent_account.parent_account']);

        // Get the hierarchy levels (only Account objects)
        $levels = [];
        $currentAccount = $account;

        // Build hierarchy from bottom up (only Account objects)
        while ($currentAccount) {
            array_unshift($levels, $currentAccount);
            if ($currentAccount->parent_account?->id == $currentAccount->id) break;
            $currentAccount = $currentAccount->parent_account;
        }

        // Map hierarchy to columns based on the requirements
        // Column D: Main Account (top-level account in the tree)
        // Column F: First parent (if exists)
        // Column H: Second parent (if exists) 
        // Column J: Final account (if exists)

        if (count($levels) >= 1) {
            // Account with no parents - it is the main account
            $hierarchy['main_code'] = $levels[0]->saved_full_code ?? '';
            $hierarchy['main_name'] = $levels[0]->name ?? '';
        }

        if (count($levels) >= 2) {
            // Account with one parent
            $hierarchy['main_code'] = $levels[0]->saved_full_code ?? ''; // First parent is the main account
            $hierarchy['main_name'] = $levels[0]->name ?? '';
            $hierarchy['gl_code'] = $levels[1]->saved_full_code ?? ''; // Account itself
            $hierarchy['gl_name'] = $levels[1]->name ?? '';
        }

        if (count($levels) >= 3) {
            // Account with two parents
            $hierarchy['main_code'] = $levels[0]->saved_full_code ?? ''; // First parent is the main account
            $hierarchy['main_name'] = $levels[0]->name ?? '';
            $hierarchy['gl_code'] = $levels[1]->saved_full_code ?? ''; // Second parent
            $hierarchy['gl_name'] = $levels[1]->name ?? '';
            $hierarchy['account_code'] = $levels[2]->saved_full_code ?? ''; // Account itself
            $hierarchy['account_name'] = $levels[2]->name ?? ''; // Account itself
        }

        if (count($levels) >= 4) {
            // Account with three or more parents
            $hierarchy['main_code'] = $levels[0]->saved_full_code ?? ''; // First parent is the main account
            $hierarchy['main_name'] = $levels[0]->name ?? '';
            $hierarchy['gl_code'] = $levels[1]->saved_full_code ?? ''; // Second parent
            $hierarchy['gl_name'] = $levels[1]->name ?? '';
            $hierarchy['account_code'] = $levels[2]->saved_full_code ?? ''; // Third parent
            $hierarchy['account_name'] = $levels[2]->name ?? ''; // Third parent
            $hierarchy['sub_code'] = $levels[3]->saved_full_code ?? ''; // Account itself
            $hierarchy['sub_name'] = $levels[3]->name ?? '';
        }

        return $hierarchy;
    }

    ///scopes

    /** this will add account model & pivot to the returning journal entries
     * $entry->account->{any_account column} 
     * $entry->account->pivot-> (entry_accounts table columns)
     */
    public function scopeIncludeAccountsName($query)
    {
        return $query->with('accounts');
    }

    /** this will add entry_title_name to the returning journal entries */
    public function scopeIncludeEntryName($query)
    {
        return $query->select('entry_titles.name as entry_title_name')
            ->join('entry_titles', 'entry_titles.id', '=', 'journal_entries.entry_title_id');
    }


    public function scopeByAccount($query, $account_id)
    {
        return $query->select('journal_entries.*')
            ->join('entry_accounts', 'entry_accounts.journal_entry_id', '=', 'journal_entries.id')
            ->where('entry_accounts.account_id', $account_id)
            ->groupBy('journal_entries.id');
    }

    public function scopeCashOnly($query)
    {
        return $query->whereHas('accounts', function ($query) {
            $query->where('accounts.id', self::CASH_ID);
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

    ///attribute
    public function getDebitTotalAttribute()
    {
        $debits = $this->accounts()->wherePivot('nature', 'debit')->get();
        $sum = 0;
        foreach ($debits as $d) {
            $sum += $d->pivot->amount;
        }
        return $sum;
    }

    public function getCreditTotalAttribute()
    {
        $credits = $this->accounts()->wherePivot('nature', 'credit')->get();
        $sum = 0;
        foreach ($credits as $c) {
            $sum += $c->pivot->amount;
        }
        return $sum;
    }

    ////relations
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'entry_accounts')->withPivot([
            'nature',
            'amount',
            'account_foreign_balance',
            'account_balance',
            'currency',
            'currency_amount',
            'currency_rate',
            'doc_url'
        ]);
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

    public function comm_profile_payment(): HasOne
    {
        return $this->hasOne(CommProfilePayment::class, 'journal_entry_id');
    }
}
