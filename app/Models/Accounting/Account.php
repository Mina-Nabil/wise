<?php

namespace App\Models\Accounting;

use App\Models\Accounting\AccountSetting;
use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Account extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'account';

    const SALES_EGP_ACCOUNT_ID = 2896;
    const TAX_ACCOUNT_ID = 3133;
    const TRANS_FEES_ACCOUNT_ID = 3101;
    const BANK_ACCOUNT_PARENT_ID = 2878;

    const OHDA_ACCOUNT_ID = 2877;
    const OTHER_DEBIT_ACCOUNT_ID = 3197;

    protected $table = 'accounts';
    protected $fillable = [
        'code',
        'name',
        'desc',
        'nature',
        'main_account_id',
        'parent_account_id', //list of accounts from the same main account
        'limit',
        'balance',
        'foreign_balance',
        'default_currency',
        'is_show_dashboard',
        'saved_full_code',
    ];

    const NATURE_CREDIT = 'credit';
    const NATURE_DEBIT = 'debit';
    const NATURES = [self::NATURE_DEBIT, self::NATURE_CREDIT];

    ////static functions
    public static function getEntries($account_id, Carbon $from, Carbon $to, $search = null)
    {
        return JournalEntry::query()
            ->join('entry_accounts', 'entry_accounts.journal_entry_id', '=', 'journal_entries.id')
            ->join('entry_titles', 'entry_titles.id', '=', 'journal_entries.entry_title_id')
            ->join('users', 'users.id', '=', 'journal_entries.user_id')
            ->where('entry_accounts.account_id', $account_id)
            ->whereBetween('journal_entries.created_at', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('journal_entries.id', 'like', "%{$search}%")
                        ->orWhere('entry_titles.name', 'like', "%{$search}%")
                        ->orWhere('journal_entries.comment', 'like', "%{$search}%");
                });
            })
            ->groupBy('journal_entries.id')

            ->select('journal_entries.*', 'account_foreign_balance', 'account_balance', 'currency_rate', 'doc_url', 'users.username', 'entry_titles.name')
            ->selectRaw('IF(entry_accounts.nature = "debit" , entry_accounts.amount , 0 ) as debit_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit" , entry_accounts.amount , 0 ) as credit_amount')
            ->selectRaw('IF(entry_accounts.nature = "debit" , entry_accounts.currency_amount , 0 ) as debit_foreign_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit" , entry_accounts.currency_amount , 0 ) as credit_foreign_amount')
            ->orderBy('journal_entries.created_at', 'asc')
            ->orderBy('journal_entries.id', 'asc')
            ->get();
    }

    public static function newAccount($code, $name, $nature, $main_account_id, $parent_account_id = null, $desc = null, $is_seeding = false, $default_currency = JournalEntry::CURRENCY_EGP, $is_show_dashboard = false): self|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$is_seeding && !$loggedInUser->can('create', self::class)) {
            return false;
        }

        $newAccount = new self([
            'code' => self::getNextCode($main_account_id, $parent_account_id),
            'name' => $name,
            'nature' => $nature,
            'parent_account_id' => $parent_account_id,
            'main_account_id' => $main_account_id,
            'desc' => $desc,
            'balance' => 0,
            'foreign_balance' => 0,
            'default_currency' => $default_currency,
            'is_show_dashboard' => $is_show_dashboard,
        ]);
        try {
            $newAccount->save();
            AppLog::info('Created account', loggable: $newAccount);
            return $newAccount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create account", desc: $e->getMessage());
            return false;
        }
    }

    public static function importAccounts($file = null)
    {
        try {
            DB::transaction(function () use ($file) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                self::query()->update([
                    'parent_account_id' => null,
                ]);
                $titles = EntryTitle::all();
                foreach ($titles as $t) {
                    if ($t->id == 1) {
                        continue;
                    }
                    $t->accounts()->sync([]);
                    $t->delete();
                }

                $entries = JournalEntry::all();
                foreach ($entries as $e) {
                    $e->accounts()->sync([]);
                    $e->delete();
                }
                self::query()->delete();
                MainAccount::query()->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                if ($file) {
                    $spreadsheet = IOFactory::load($file);
                } else {
                    $spreadsheet = IOFactory::load(resource_path('import/AccountsTree.xlsx'));
                }
                if (!$spreadsheet) {
                    throw new Exception('Failed to read files content');
                }
                $activeSheet = $spreadsheet->getActiveSheet();
                $highestRow = $activeSheet->getHighestDataRow();
                $found_balances = [];
                $endLoop = false;
                for ($i = 2; $i <= $highestRow; $i++) {
                    $start_char = 'F';
                    $account_name = $activeSheet->getCell('F' . $i)->getValue();

                    while ($account_name == null) {
                        $start_char = chr(ord($start_char) - 1);
                        if ($start_char == 'A') {
                            $endLoop = true;
                            break;
                        }
                        $account_name = $activeSheet->getCell($start_char . $i)->getValue();
                    }
                    if ($endLoop) {
                        break;
                    }
                    $parent_name = $start_char == 'C' ? null : $activeSheet->getCell(chr(ord($start_char) - 1) . $i)->getValue();
                    $main_account_name = $activeSheet->getCell('B' . $i)->getValue();
                    $nature = strtolower($activeSheet->getCell('G' . $i)->getValue());
                    $desc = $activeSheet->getCell('H' . $i)->getValue();
                    $balance = $activeSheet->getCell('I' . $i)->getValue();

                    try {
                        $main_account = MainAccount::firstOrCreate(
                            [
                                'name' => $main_account_name,
                            ],
                            [
                                'code' => MainAccount::getNextCode(),
                                'type' => MainAccount::getTypeByArabicName($main_account_name),
                                'desc' => $desc,
                            ],
                        );
                    } catch (QueryException $e) {
                        if ($e->getCode() == 23000) {
                            $main_account = MainAccount::firstOrCreate(
                                [
                                    'name' => $main_account_name,
                                ],
                                [
                                    'code' => MainAccount::getNextCode(),
                                    'type' => MainAccount::getTypeByArabicName($main_account_name),
                                    'desc' => $desc,
                                ],
                            );
                        }
                    }

                    if (!$account_name) {
                        continue;
                    }
                    $parent_account = null;
                    if ($parent_name) {
                        $parent_account = self::byName($parent_name)->first();
                    }

                    $tmpAccount = self::newAccount(1, $account_name, $nature, $main_account->id, $parent_account?->id, $desc);

                    if ($balance) {
                        $found_balances[$tmpAccount->id] = [
                            'nature' => $balance > 0 ? $nature : ($nature == 'debit' ? 'credit' : 'debit'),
                            'amount' => abs($balance),
                            'currency' => 'EGP',
                        ];
                    }
                }

                $starting_entry = JournalEntry::newJournalEntry(1, skip_auth: true, accounts: $found_balances);
                if (!$starting_entry) {
                    throw new Exception('Import failed please check balances');
                }
                if (is_string($starting_entry)) {
                    throw new Exception($starting_entry);
                }
            });
        } catch (Exception $e) {
            report($e);
            return false;
        }
        return true;
    }

    public static function getNextCode($main_account_id, $parent_account_id)
    {
        return (DB::table('accounts')->selectRaw('MAX(code) as max_code')->where('parent_account_id', $parent_account_id)->where('main_account_id', $main_account_id)->first()?->max_code ?? 0) + 1;
    }

    /**
     * Refresh all account codes based on created_at ordering
     * Groups accounts by main_account_id and parent_account_id,
     * then assigns sequential codes based on creation date
     * 
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int, 'errors' => array]
     */
    public static function refreshAllCodes(): array
    {
        try {
            DB::beginTransaction();

            $accountsProcessed = 0;
            $errors = [];

            // Get all unique combinations of main_account_id and parent_account_id
            $groups = DB::table('accounts')
                ->select('main_account_id', 'parent_account_id')
                ->groupBy('main_account_id', 'parent_account_id')
                ->get();

            foreach ($groups as $group) {
                try {
                    // Get all accounts in this group, ordered by created_at
                    $accounts = self::where('main_account_id', $group->main_account_id)
                        ->where('parent_account_id', $group->parent_account_id)
                        ->orderBy('created_at', 'asc')
                        ->orderBy('id', 'asc') // Secondary sort by id for consistency
                        ->get();

                    // Assign sequential codes starting from 1
                    $code = 1;
                    foreach ($accounts as $account) {
                        $account->code = $code;
                        $account->save();
                        $code++;
                        $accountsProcessed++;
                    }
                } catch (Exception $e) {
                    $errors[] = "Group (main_account_id: {$group->main_account_id}, parent_account_id: " . ($group->parent_account_id ?? 'null') . "): " . $e->getMessage();
                    report($e);
                }
            }

            // Now update saved_full_code for all accounts
            // We need to process parent accounts first, then children
            // So we'll process accounts with no parent first, then recursively process children
            try {
                // Process accounts with no parent first
                $parentAccounts = self::whereNull('parent_account_id')->get();
                foreach ($parentAccounts as $account) {
                    $account->load('main_account');
                    $fullCode = $account->main_account->code . '-' . $account->code;
                    $account->saved_full_code = $fullCode;
                    $account->save();
                }

                // Process accounts with parents (need to process in hierarchy order)
                $accountIds = self::whereNotNull('parent_account_id')->pluck('id')->toArray();
                $maxDepth = 10; // Safety limit for hierarchy depth
                $depth = 0;

                while (!empty($accountIds) && $depth < $maxDepth) {
                    $processed = [];
                    
                    foreach ($accountIds as $accountId) {
                        $account = self::find($accountId);
                        if (!$account) continue;
                        
                        $parent = self::find($account->parent_account_id);
                        
                        // Only process if parent's saved_full_code is already set
                        if ($parent && $parent->saved_full_code) {
                            $account->load('parent_account');
                            $fullCode = $account->parent_account->saved_full_code . '-' . $account->code;
                            $account->saved_full_code = $fullCode;
                            $account->save();
                            $processed[] = $accountId;
                        }
                    }

                    // Remove processed account IDs from the list
                    $accountIds = array_diff($accountIds, $processed);

                    $depth++;
                }

                // If there are still unprocessed accounts, try to process them using the accessor
                foreach ($accountIds as $accountId) {
                    try {
                        $account = self::find($accountId);
                        if ($account) {
                            $account->load(['parent_account', 'main_account']);
                            // Use the full_code accessor which will calculate and save it
                            $account->full_code;
                        }
                    } catch (Exception $e) {
                        $errors[] = "Account ID {$accountId}: Failed to update full_code - " . $e->getMessage();
                    }
                }
            } catch (Exception $e) {
                $errors[] = "Failed to update saved_full_code: " . $e->getMessage();
                report($e);
            }

            DB::commit();

            $message = "Successfully refreshed codes for {$accountsProcessed} accounts.";
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
            AppLog::error("Failed to refresh account codes", desc: $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to refresh account codes: ' . $e->getMessage(),
                'accounts_processed' => $accountsProcessed ?? 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    ////model functions
    public function downloadAccountDetails(Carbon $from, Carbon $to, $search = null, bool $includeChildren = false, bool $sameSheet = false)
    {
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle(self::excelSheetTitle($this->name));

        if ($includeChildren && $sameSheet) {
            $children = $this->children_accounts()->get();
            $childIds = $children->pluck('id')->toArray();
            $allIds = array_merge([$this->id], $childIds);
            $this->fillMixedSheet($activeSheet, $allIds, $from, $to, $search);
        } else {
            $this->fillAccountSheet($activeSheet, $this->id, $from, $to, $search);

            if ($includeChildren) {
                foreach ($this->children_accounts as $child) {
                    $entries = self::getEntries($child->id, $from, $to, $search);
                    if ($entries->isEmpty()) continue;
                    $sheet = $spreadsheet->createSheet();
                    $sheet->setTitle(self::excelSheetTitle($child->name));
                    $this->fillAccountSheet($sheet, $child->id, $from, $to, $search, $entries);
                }
            }
        }

        $writer = new Xlsx($spreadsheet);
        $file_path = SoldPolicy::FILES_DIRECTORY . 'account_details.xlsx';
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    /**
     * Excel sheet titles must be valid UTF-8, max 31 characters, and cannot
     * contain \ / ? * [ ] :
     */
    private static function excelSheetTitle(string $name): string
    {
        $title = mb_substr($name, 0, 31);
        $title = str_replace(['\\', '/', '?', '*', '[', ']', ':'], '', $title);

        return $title !== '' ? $title : 'Account';
    }

    private static function writeExcelDate($sheet, string $coordinate, $date): void
    {
        $sheet->setCellValue($coordinate, ExcelDate::PHPToExcel(Carbon::parse($date)));
    }

    private static function applyExcelDateFormat($sheet, string $range): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
    }

    private static function applyExcelNumberFormat($sheet, string $range): void
    {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode('#,##0.00;[Red](#,##0.00)');
    }

    /**
     * Derive starting balance from the first entry in the range,
     * same logic as the blade: reverse the first entry's effect on account_balance.
     * debit account: starting = account_balance + credit_amount - debit_amount
     * credit account: starting = account_balance + debit_amount - credit_amount
     */
    private function deriveStartingBalance($firstEntry, string $accountNature): array
    {
        if (!$firstEntry) {
            return ['balance' => 0.0, 'foreign_balance' => 0.0];
        }

        if ($accountNature === 'debit') {
            $balance         = (float) $firstEntry->account_balance + (float) $firstEntry->credit_amount - (float) $firstEntry->debit_amount;
            $foreignBalance  = (float) $firstEntry->account_foreign_balance + (float) $firstEntry->credit_foreign_amount - (float) $firstEntry->debit_foreign_amount;
        } else {
            $balance         = (float) $firstEntry->account_balance + (float) $firstEntry->debit_amount - (float) $firstEntry->credit_amount;
            $foreignBalance  = (float) $firstEntry->account_foreign_balance + (float) $firstEntry->debit_foreign_amount - (float) $firstEntry->credit_foreign_amount;
        }

        return ['balance' => $balance, 'foreign_balance' => $foreignBalance];
    }

    private function fillAccountSheet($sheet, int $accountId, Carbon $from, Carbon $to, $search = null, $entries = null): void
    {
        $account  = $accountId === $this->id ? $this : self::findOrFail($accountId);
        $entries  = $entries ?? self::getEntries($accountId, $from, $to, $search);
        $starting = $this->deriveStartingBalance($entries->first(), $account->nature);

        // Row 1 — Starting balance info row
        $sheet->setCellValue('A1', 'Starting Balance');
        self::writeExcelDate($sheet, 'B1', $from);
        $sheet->setCellValue('G1', number_format($starting['balance'], 2));
        $sheet->setCellValue('J1', number_format($starting['foreign_balance'], 2));
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:K1')->getFill()->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Row 2 — Column headers
        $sheet->setCellValue('A2', '#');
        $sheet->setCellValue('B2', 'Date');
        $sheet->setCellValue('C2', 'Title');
        $sheet->setCellValue('D2', 'Comment');
        $sheet->setCellValue('E2', 'Debit');
        $sheet->setCellValue('F2', 'Credit');
        $sheet->setCellValue('G2', 'Balance');
        $sheet->setCellValue('H2', 'Debit $');
        $sheet->setCellValue('I2', 'Credit $');
        $sheet->setCellValue('J2', 'Balance $');
        $sheet->setCellValue('K2', 'Creator');
        $sheet->getStyle('A2:K2')->getFont()->setBold(true);
        $sheet->getStyle('A2:K2')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A2:K2')->getFill()->getStartColor()->setARGB('FFCCCCCC');

        $row = 3;
        foreach ($entries as $entry) {
            $sheet->setCellValue('A' . $row, $entry->id);
            self::writeExcelDate($sheet, 'B' . $row, $entry->created_at);
            $sheet->setCellValue('C' . $row, $entry->name . ' ' . ($entry->is_reverted_entry ? ' (R1)' : '') . ($entry->is_revert_entry ? ' (R2)' : ''));
            $sheet->setCellValue('D' . $row, $entry->cash_title);
            $sheet->setCellValue('E' . $row, number_format($entry->debit_amount, 2));
            $sheet->setCellValue('F' . $row, number_format($entry->credit_amount, 2));
            $sheet->setCellValue('G' . $row, number_format($entry->account_balance, 2));
            $sheet->setCellValue('H' . $row, number_format($entry->debit_foreign_amount, 2));
            $sheet->setCellValue('I' . $row, number_format($entry->credit_foreign_amount, 2));
            $sheet->setCellValue('J' . $row, number_format($entry->account_foreign_balance, 2));
            $sheet->setCellValue('K' . $row, $entry->username);
            $row++;
        }

        self::applyExcelDateFormat($sheet, 'B1:B' . max(1, $row - 1));

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function fillMixedSheet($sheet, array $accountIds, Carbon $from, Carbon $to, $search = null): void
    {
        $entries = self::getMixedEntries($accountIds, $from, $to, $search);

        // Derive combined starting balance using the same formula as the blade:
        // for each account, find its first entry in the range and reverse its effect.
        $runningBalance = 0.0;
        $runningForeignBalance = 0.0;

        $accountNatures = DB::table('accounts')
            ->whereIn('id', $accountIds)
            ->pluck('nature', 'id');

        foreach ($accountIds as $aid) {
            $accountNature = $accountNatures[$aid] ?? $this->nature;
            $firstEntry = $entries->firstWhere('entry_accounts_account_id', $aid)
                       ?? $entries->first(fn($e) => (int)$e->account_name === $aid); // fallback

            // Build a synthetic object matching what deriveStartingBalance expects
            // using the mixed-entry fields for this account's first row
            $firstForAccount = $entries->first(function ($e) use ($aid) {
                return DB::table('entry_accounts')
                    ->where('journal_entry_id', $e->id)
                    ->where('account_id', $aid)
                    ->exists();
            });

            if ($firstForAccount) {
                // Fetch the actual pivot values for this account from the first entry
                $pivot = DB::table('entry_accounts')
                    ->where('journal_entry_id', $firstForAccount->id)
                    ->where('account_id', $aid)
                    ->select('account_balance', 'account_foreign_balance', 'nature', 'amount', 'currency_amount')
                    ->first();

                if ($pivot) {
                    $isDebit = $accountNature === 'debit';
                    $debitAmt   = $pivot->nature === 'debit'   ? (float) $pivot->amount : 0;
                    $creditAmt  = $pivot->nature === 'credit'  ? (float) $pivot->amount : 0;
                    $debitFgn   = $pivot->nature === 'debit'   ? (float) ($pivot->currency_amount ?? 0) : 0;
                    $creditFgn  = $pivot->nature === 'credit'  ? (float) ($pivot->currency_amount ?? 0) : 0;

                    $acctStartBal = $isDebit
                        ? (float) $pivot->account_balance + $creditAmt - $debitAmt
                        : (float) $pivot->account_balance + $debitAmt - $creditAmt;
                    $acctStartFgn = $isDebit
                        ? (float) $pivot->account_foreign_balance + $creditFgn - $debitFgn
                        : (float) $pivot->account_foreign_balance + $debitFgn - $creditFgn;

                    if ($accountNature === $this->nature) {
                        $runningBalance        += $acctStartBal;
                        $runningForeignBalance += $acctStartFgn;
                    } else {
                        $runningBalance        -= $acctStartBal;
                        $runningForeignBalance -= $acctStartFgn;
                    }
                }
            }
        }

        // Row 1 — Starting balance info row
        $sheet->setCellValue('A1', 'Starting Balance');
        self::writeExcelDate($sheet, 'B1', $from);
        $sheet->setCellValue('H1', number_format($runningBalance, 2));
        $sheet->setCellValue('K1', number_format($runningForeignBalance, 2));
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:L1')->getFill()->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB('FFFFFFFF');

        // Row 2 — Column headers
        $headers = ['#', 'Date', 'Account', 'Title', 'Comment', 'Debit', 'Credit', 'Balance', 'Debit $', 'Credit $', 'Balance $', 'Creator'];
        $cols = range('A', 'L');
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '2', $header);
        }
        $sheet->getStyle('A2:L2')->getFont()->setBold(true);
        $sheet->getStyle('A2:L2')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A2:L2')->getFill()->getStartColor()->setARGB('FFCCCCCC');

        $row = 3;

        foreach ($entries as $entry) {
            $amount = (float) $entry->entry_amount;
            $foreignAmount = (float) ($entry->entry_currency_amount ?? 0);

            if ($entry->entry_nature === $entry->account_nature) {
                $runningBalance += $amount;
                $runningForeignBalance += $foreignAmount;
            } else {
                $runningBalance -= $amount;
                $runningForeignBalance -= $foreignAmount;
            }

            $sheet->setCellValue('A' . $row, $entry->id);
            self::writeExcelDate($sheet, 'B' . $row, $entry->created_at);
            $sheet->setCellValue('C' . $row, $entry->account_name);
            $sheet->setCellValue('D' . $row, $entry->title_name . ' ' . ($entry->is_reverted_entry ? ' (R1)' : '') . ($entry->is_revert_entry ? ' (R2)' : ''));
            $sheet->setCellValue('E' . $row, $entry->cash_title);
            $sheet->setCellValue('F' . $row, number_format($entry->debit_amount, 2));
            $sheet->setCellValue('G' . $row, number_format($entry->credit_amount, 2));
            $sheet->setCellValue('H' . $row, number_format($runningBalance, 2));
            $sheet->setCellValue('I' . $row, number_format($entry->debit_foreign_amount, 2));
            $sheet->setCellValue('J' . $row, number_format($entry->credit_foreign_amount, 2));
            $sheet->setCellValue('K' . $row, number_format($runningForeignBalance, 2));
            $sheet->setCellValue('L' . $row, $entry->username);
            $row++;
        }

        self::applyExcelDateFormat($sheet, 'B1:B' . max(1, $row - 1));

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public static function getMixedEntries(array $accountIds, Carbon $from, Carbon $to, $search = null)
    {
        return JournalEntry::query()
            ->join('entry_accounts', 'entry_accounts.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounts', 'accounts.id', '=', 'entry_accounts.account_id')
            ->join('entry_titles', 'entry_titles.id', '=', 'journal_entries.entry_title_id')
            ->join('users', 'users.id', '=', 'journal_entries.user_id')
            ->whereIn('entry_accounts.account_id', $accountIds)
            ->whereBetween('journal_entries.created_at', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('journal_entries.id', 'like', "%{$search}%")
                        ->orWhere('entry_titles.name', 'like', "%{$search}%")
                        ->orWhere('journal_entries.comment', 'like', "%{$search}%");
                });
            })
            ->select(
                'journal_entries.*',
                'currency_rate', 'doc_url', 'users.username',
                DB::raw('entry_titles.name as title_name'),
                DB::raw('accounts.name as account_name'),
                DB::raw('entry_accounts.nature as entry_nature'),
                DB::raw('entry_accounts.amount as entry_amount'),
                DB::raw('entry_accounts.currency_amount as entry_currency_amount'),
                DB::raw('accounts.nature as account_nature')
            )
            ->selectRaw('IF(entry_accounts.nature = "debit", entry_accounts.amount, 0) as debit_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit", entry_accounts.amount, 0) as credit_amount')
            ->selectRaw('IF(entry_accounts.nature = "debit", entry_accounts.currency_amount, 0) as debit_foreign_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit", entry_accounts.currency_amount, 0) as credit_foreign_amount')
            ->orderBy('journal_entries.created_at')
            ->orderBy('journal_entries.id')
            ->orderBy('entry_accounts.account_id')
            ->get();
    }

    public static function exportAllAccountsWithBalances($mode = 'balance', ?Carbon $from = null, ?Carbon $to = null, $main_accounts_only = false, $show_zero_balances = true, $included_levels = 999)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('viewAny', self::class)) {
            return false;
        }

        assert(in_array($mode, ['balance', 'entries']), 'Invalid mode');
        assert($to, 'To date is required for this report');
        assert($mode == 'balance' || ($from && $to), 'From and to dates are required for entries mode');

        if(!$included_levels || $mode == 'entries') $included_levels = 999;

        try {
            // Create new spreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();

            // Entries mode carries the balance at the start of the period and the
            // closing balance around the period movements
            $lastColumn = $mode == 'entries' ? 'I' : 'G';

            // Set headers
            $activeSheet->setCellValue('A1', 'Account Code');
            $activeSheet->setCellValue('B1', 'Account Name');
            if ($mode == 'entries') {
                $activeSheet->setCellValue('C1', 'Start Balance- Debit');
                $activeSheet->setCellValue('D1', 'Start Balance- Credit');
                $activeSheet->setCellValue('E1', 'Nature');
                $activeSheet->setCellValue('F1', 'Debit');
                $activeSheet->setCellValue('G1', 'Credit');
                $activeSheet->setCellValue('H1', 'Balance- Debit');
                $activeSheet->setCellValue('I1', 'Balance- Credit');
            } else {
                $activeSheet->setCellValue('C1', 'Nature');
                $activeSheet->setCellValue('D1', 'Debit');
                $activeSheet->setCellValue('E1', 'Credit');
                $activeSheet->setCellValue('F1', 'Debit Foreign');
                $activeSheet->setCellValue('G1', 'Credit Foreign');
            }

            // Style headers
            $headerRange = 'A1:' . $lastColumn . '1';
            $activeSheet->getStyle($headerRange)->getFont()->setBold(true);
            $activeSheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $activeSheet->getStyle($headerRange)->getFill()->getStartColor()->setARGB('FFCCCCCC');

            // Get all accounts with their relationships
            Log::info($to);
            $accounts = self::orderByCode()->includeLastEntryBalance($to)
                ->when($main_accounts_only && $mode == 'balance', fn($q) => $q->parentAccounts())
                ->when($mode == 'entries' && $from && $to, fn($q) => $q->totalEntries($from, $to));
            $accounts = $accounts->get();

            // Balance of every account (including its children) right before the period starts
            $startTotals = $mode == 'entries' && $from ? self::getStartBalanceTotals($accounts, $from) : [];

            // Get parent accounts (accounts with no parent)
            $parentAccounts = $accounts->whereNull('parent_account_id');

            $row = 2;
            $processedAccounts = [];

            // Process each parent account and its children
            foreach ($parentAccounts as $parentAccount) {
                $row = self::addAccountToExport(
                    $activeSheet,
                    $parentAccount,
                    $row,
                    $processedAccounts,
                    $accounts,
                    0,
                    $mode,
                    $show_zero_balances,
                    $main_accounts_only,
                    $from,
                    $to,
                    $included_levels,
                    $startTotals
                );
            }

            // Auto-size columns
            foreach (range('A', $lastColumn) as $col) {
                $activeSheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Create writer and save file
            $writer = new Xlsx($spreadsheet);
            $filename = 'accounts_with_balances_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            $file_path = SoldPolicy::FILES_DIRECTORY . $filename;
            $public_file_path = storage_path($file_path);

            // Ensure directory exists
            if (!file_exists(dirname($public_file_path))) {
                mkdir(dirname($public_file_path), 0755, true);
            }

            $writer->save($public_file_path);

            return response()->download($public_file_path)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Export accounts with their opening balances from the latest entry before a specific year
     * @param int $year The year to get opening balances for
     * @param bool $show_zero_balances Whether to include accounts with zero balances
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|false
     */
    public static function exportAccountsOpeningBalances(int $year, bool $show_zero_balances = true)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('viewAny', self::class)) {
            return false;
        }

        try {
            // Create new spreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();

            // Set headers
            $activeSheet->setCellValue('A1', 'Account Code');
            $activeSheet->setCellValue('B1', 'Account Name');
            $activeSheet->setCellValue('C1', 'Nature');
            $activeSheet->setCellValue('D1', 'Balance');
            $activeSheet->setCellValue('E1', 'Foreign Balance');

            // Style headers
            $activeSheet->getStyle('A1:E1')->getFont()->setBold(true);
            $activeSheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $activeSheet->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFCCCCCC');

            // Get cutoff date (start of the specified year)
            $cutoffDate = Carbon::createFromDate($year, 1, 1)->startOfDay();

            // Get all accounts ordered by code
            $accounts = self::orderByCode()->get();

            // Get the latest entry for each account before the cutoff date
            $accountBalances = [];
            foreach ($accounts as $account) {
                $latestEntry = DB::table('journal_entries')
                    ->join('entry_accounts', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
                    ->where('entry_accounts.account_id', $account->id)
                    ->where('journal_entries.created_at', '<', $cutoffDate->format('Y-m-d H:i:s'))
                    ->orderBy('journal_entries.created_at', 'desc')
                    ->orderBy('journal_entries.id', 'desc')
                    ->select('entry_accounts.account_balance', 'entry_accounts.account_foreign_balance')
                    ->first();

                if ($latestEntry) {
                    $accountBalances[$account->id] = [
                        'balance' => $latestEntry->account_balance ?? 0,
                        'foreign_balance' => $latestEntry->account_foreign_balance ?? 0
                    ];
                } else {
                    $accountBalances[$account->id] = [
                        'balance' => 0,
                        'foreign_balance' => 0
                    ];
                }
            }

            // Get parent accounts (accounts with no parent)
            $parentAccounts = $accounts->whereNull('parent_account_id');

            $row = 2;
            $processedAccounts = [];

            // Process each parent account and its children
            foreach ($parentAccounts as $parentAccount) {
                $row = self::addAccountToOpeningBalanceExport(
                    $activeSheet,
                    $parentAccount,
                    $row,
                    $processedAccounts,
                    $accounts,
                    $accountBalances,
                    0,
                    $show_zero_balances
                );
            }

            // Auto-size columns
            foreach (range('A', 'E') as $col) {
                $activeSheet->getColumnDimension($col)->setAutoSize(true);
            }

            if ($row > 2) {
                self::applyExcelNumberFormat($activeSheet, 'D2:E' . ($row - 1));
            }

            // Create writer and save file
            $writer = new Xlsx($spreadsheet);
            $filename = 'accounts_opening_balances_' . $year . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            $file_path = SoldPolicy::FILES_DIRECTORY . $filename;
            $public_file_path = storage_path($file_path);

            // Ensure directory exists
            if (!file_exists(dirname($public_file_path))) {
                mkdir(dirname($public_file_path), 0755, true);
            }

            $writer->save($public_file_path);

            return response()->download($public_file_path)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Add account to opening balance export with hierarchy
     */
    private static function addAccountToOpeningBalanceExport($activeSheet, $account, $row, &$processedAccounts, $allAccounts, $accountBalances, $indentLevel = 0, $show_zero = true)
    {
        // Skip if already processed
        if (in_array($account->id, $processedAccounts)) {
            return $row;
        }

        // Mark as processed
        $processedAccounts[] = $account->id;

        // Add indent to account name for visual hierarchy
        $indent = str_repeat('  ', $indentLevel);
        $accountName = $indent . $account->name;

        // Get balance data for this account
        $balance = $accountBalances[$account->id]['balance'] ?? 0;
        $foreignBalance = $accountBalances[$account->id]['foreign_balance'] ?? 0;

        // Add account to spreadsheet if it has balance or we're showing zero balances
        if ($show_zero || $balance != 0 || $foreignBalance != 0) {
            $activeSheet->setCellValue('A' . $row, $account->full_code);
            $activeSheet->setCellValue('B' . $row, $accountName);
            $activeSheet->setCellValue('C' . $row, ucfirst($account->nature));
            $activeSheet->setCellValue('D' . $row, (float) $balance);
            $activeSheet->setCellValue('E' . $row, (float) $foreignBalance);

            // Style parent accounts differently
            if ($indentLevel == 0) {
                $activeSheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
            }

            $row++;
        }

        // Process children recursively
        $children = $allAccounts->where('parent_account_id', $account->id);
        foreach ($children as $child) {
            $row = self::addAccountToOpeningBalanceExport($activeSheet, $child, $row, $processedAccounts, $allAccounts, $accountBalances, $indentLevel + 1, $show_zero);
        }

        return $row;
    }

    /**
     * Balance of each account - plus every account below it - right before $from
     *
     * Reads the running balance snapshot of the latest entry strictly before $from for
     * every account in one query, then rolls the values up the tree in memory.
     *
     * @return array [account_id => float]
     */
    private static function getStartBalanceTotals($accounts, Carbon $from): array
    {
        $ownBalances = [];
        foreach (self::includeBalanceBefore($from)->get() as $account) {
            $ownBalances[$account->id] = (float) ($account->start_entry_balance ?? 0);
        }

        $parents = [];
        $totals = [];
        foreach ($accounts as $account) {
            $parents[$account->id] = $account->parent_account_id;
            $totals[$account->id] = 0.0;
        }

        // Add every account's own balance to itself and to each of its ancestors
        foreach ($accounts as $account) {
            $own = $ownBalances[$account->id] ?? 0.0;
            $id = $account->id;
            $depth = 0;
            while ($id !== null && isset($totals[$id]) && $depth++ < 100) {
                $totals[$id] += $own;
                $id = $parents[$id] ?? null;
            }
        }

        return $totals;
    }

    /**
     * Place a balance in the debit or the credit column depending on the account
     * nature and the sign of the balance
     *
     * @return array [debit cell value, credit cell value]
     */
    private static function splitBalanceByNature($balance, $nature): array
    {
        if (round($balance, 2) == 0) {
            return ['', ''];
        }

        $onDebitSide = ($nature == self::NATURE_DEBIT) === ($balance > 0);
        $formatted = number_format(abs($balance), 2);

        return $onDebitSide ? [$formatted, ''] : ['', $formatted];
    }

    private static function addAccountToExport($activeSheet, $account, $row, &$processedAccounts, $allAccounts, $indentLevel = 0, $mode = 'balance', $show_zero = true, $main_accounts_only = false, Carbon $from = null, Carbon $to = null, $included_levels, array $startTotals = [])
    {
        // Skip if already processed
        if (in_array($account->id, $processedAccounts) || $indentLevel > $included_levels) {
            return $row;
        }

        // Mark as processed
        $processedAccounts[] = $account->id;

        // Add indent to account name for visual hierarchy
        $indent = str_repeat('  ', $indentLevel);
        $accountName = $indent . $account->name;

        // Calculate balance placement based on nature and sign
        $totalBalance = $mode == 'balance' ? $account->getTotalLastEntryBalance($to) : 0;
        $totalCurrencyBalance = $mode == 'balance' ? $account->getTotalLastEntryCurrencyBalance($to) : 0;
        $debitAmount = '';
        $creditAmount = '';
        $debitForeignAmount = '';
        $creditForeignAmount = '';
        $startDebit = '';
        $startCredit = '';
        $closingDebit = '';
        $closingCredit = '';
        if ($mode == 'balance') {
            if ($totalBalance != 0) {
                if ($account->nature == self::NATURE_DEBIT) {
                    if ($totalBalance >= 0) {
                        $debitAmount = number_format($totalBalance, 2);
                        $debitForeignAmount = number_format($totalCurrencyBalance, 2);
                    } else {
                        $creditAmount = number_format(abs($totalBalance), 2);
                        $creditForeignAmount = number_format(abs($totalCurrencyBalance), 2);
                    }
                } else { // NATURE_CREDIT
                    if ($totalBalance >= 0) {
                        $creditAmount = number_format($totalBalance, 2);
                        $creditForeignAmount = number_format($totalCurrencyBalance, 2);
                    } else {
                        $debitAmount = number_format(abs($totalBalance), 2);
                        $debitForeignAmount = number_format(abs($totalCurrencyBalance), 2);
                    }
                }
            }
        } else {
            $debitTotal = $account->sumChildrenEntries('debit', $from, $to);
            $creditTotal = $account->sumChildrenEntries('credit', $from, $to);
            $debitAmount = number_format($debitTotal, 2);
            $creditAmount = number_format($creditTotal, 2);

            // Balance before the period, and the closing balance it adds up to
            $startBalance = $startTotals[$account->id] ?? 0;
            $movement = $account->nature == self::NATURE_DEBIT
                ? $debitTotal - $creditTotal
                : $creditTotal - $debitTotal;
            [$startDebit, $startCredit] = self::splitBalanceByNature($startBalance, $account->nature);
            [$closingDebit, $closingCredit] = self::splitBalanceByNature($startBalance + $movement, $account->nature);
        }

        // Add account to spreadsheet
        if (($show_zero || $debitAmount || $creditAmount || $debitForeignAmount || $creditForeignAmount) && (!$main_accounts_only || $indentLevel == 0)) {
            $activeSheet->setCellValue('A' . $row, $account->full_code);
            $activeSheet->setCellValue('B' . $row, $accountName);

            if ($mode == 'entries') {
                $activeSheet->setCellValue('C' . $row, $startDebit);
                $activeSheet->setCellValue('D' . $row, $startCredit);
                $activeSheet->setCellValue('E' . $row, ucfirst($account->nature));
                $activeSheet->setCellValue('F' . $row, $debitAmount);
                $activeSheet->setCellValue('G' . $row, $creditAmount);
                $activeSheet->setCellValue('H' . $row, $closingDebit);
                $activeSheet->setCellValue('I' . $row, $closingCredit);
                $lastColumn = 'I';
            } else {
                $activeSheet->setCellValue('C' . $row, ucfirst($account->nature));
                $activeSheet->setCellValue('D' . $row, $debitAmount);
                $activeSheet->setCellValue('E' . $row, $creditAmount);
                $activeSheet->setCellValue('F' . $row, $debitForeignAmount);
                $activeSheet->setCellValue('G' . $row, $creditForeignAmount);
                $lastColumn = 'G';
            }

            // Style parent accounts differently
            if ($indentLevel == 0) {
                $activeSheet->getStyle('A' . $row . ':' . $lastColumn . $row)->getFont()->setBold(true);
            }

            $row++;
        }

        // Process children recursively
        $children = $allAccounts->where('parent_account_id', $account->id);
        foreach ($children as $child) {
            $row = self::addAccountToExport($activeSheet, $child, $row, $processedAccounts, $allAccounts, $indentLevel + 1, $mode, $show_zero, $main_accounts_only, $from, $to, $included_levels, $startTotals);
        }

        return $row;
    }

    private function sumChildrenEntries($mode = 'debit', Carbon $from, Carbon $to)
    {
        $children = $this->children_accounts()->totalEntries($from, $to)->get();
        if ($children->count() == 0) {
            switch ($mode) {
                case 'debit':
                    return $this->debit_amount;
                case 'credit':
                    return $this->credit_amount;
                case 'foreign_debit':
                    return $this->debit_foreign_amount;
                case 'foreign_credit':
                    return $this->credit_foreign_amount;
            }
        }

        $child_total = 0;
        switch ($mode) {
            case 'debit':
                $child_total = $this->debit_amount;
                break;
            case 'credit':
                $child_total = $this->credit_amount;
                break;
            case 'foreign_debit':
                $child_total = $this->debit_foreign_amount;
                break;
            case 'foreign_credit':
                $child_total = $this->credit_foreign_amount;
                break;
        }
        foreach ($children as $child) {
            switch ($mode) {
                case 'debit':
                    $child_total += $child->sumChildrenEntries($mode, $from, $to);
                    break;
                case 'credit':
                    $child_total += $child->sumChildrenEntries($mode, $from, $to);
                    break;
                case 'foreign_debit':
                    $child_total += $child->sumChildrenEntries($mode, $from, $to);
                    break;
                case 'foreign_credit':
                    $child_total += $child->sumChildrenEntries($mode, $from, $to);
                    break;
            }
        }
        return $child_total;
    }

    /** returns new balance after update */
    public function updateBalance($amount, $type, $skip_auth = false)
    {
        if (!$skip_auth) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('update', $this)) {
                return false;
            }
        }
        if ($this->nature != $type) {
            $amount = -1 * $amount;
        }

        $this->balance = $this->balance + $amount;
        try {
            $this->save();
            return $this->balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    public function updateForeignBalance($amount, $type, $skip_auth = false)
    {
        if (!$skip_auth) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('update', $this)) {
                return false;
            }
        }
        if ($this->nature != $type) {
            $amount = -1 * $amount;
        }

        $amount = $amount ? (float) $amount : 0;

        $this->foreign_balance = $this->foreign_balance + $amount;
        try {
            $this->save();
            return $this->foreign_balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    public function needsApproval($amount)
    {
        return $this->limit <= $amount;
    }

    /**
     * Set the opening balance for this account and refresh all entry balances
     * 
     * @param float $balance The opening balance amount (balance BEFORE any entries)
     * @param float|null $foreignBalance The opening foreign balance amount (optional)
     * @return array ['success' => bool, 'message' => string]
     */
    public function setOpeningBalance(float $balance, ?float $foreignBalance = null): array
    {
        try {
            return DB::transaction(function () use ($balance, $foreignBalance) {
                $this->applyOpeningBalance($balance, $foreignBalance);

                AppLog::info('Set opening balance', loggable: $this);

                // Refresh all balances to recalculate all entry snapshots
                $result = JournalEntry::refreshAllBalances([$this->id => ['balance' => $balance, 'foreign_balance' => $foreignBalance]]);

                return $result;
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set opening balance", desc: $e->getMessage(), loggable: $this);
            return [
                'success' => false,
                'message' => 'Failed to set opening balance: ' . $e->getMessage(),
                'accounts_processed' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Delete this account together with every journal entry it takes part in.
     *
     * Entries are removed whole - both sides - so total debits keep matching total
     * credits. That changes the balance of every other account those entries touched,
     * which is correct: the entry no longer exists. What must NOT change is their
     * opening balance (the balance carried in from archived history), so it is captured
     * before the delete and re-applied to whatever entry becomes their first one.
     *
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int, 'errors' => array]
     */
    public function deleteWithEntries(): array
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('deleteWithEntries', $this)) {
            return $this->failedResult('You are not allowed to delete accounts');
        }

        if ($this->children_accounts()->count()) {
            return $this->failedResult('This account has sub accounts. Delete or move them first.');
        }

        if ($blocker = $this->findExternalReferences()) {
            return $this->failedResult($blocker);
        }

        try {
            return DB::transaction(function () {
                $entryIds = DB::table('entry_accounts')
                    ->where('account_id', $this->id)
                    ->distinct()
                    ->pluck('journal_entry_id')
                    ->toArray();

                // Every other account standing on the other side of those entries
                $affected = self::whereIn(
                    'id',
                    DB::table('entry_accounts')
                        ->whereIn('journal_entry_id', $entryIds)
                        ->where('account_id', '!=', $this->id)
                        ->distinct()
                        ->pluck('account_id')
                        ->toArray()
                )->get();

                $openings = [];
                foreach ($affected as $account) {
                    $openings[$account->id] = $account->getOpeningBalance();
                }

                // Live entries - both sides
                DB::table('entry_accounts')->whereIn('journal_entry_id', $entryIds)->delete();
                JournalEntry::whereIn('id', $entryIds)->delete();

                // Entries still waiting for approval
                $pendingEntries = UnapprovedEntry::whereHas('accounts', fn($q) => $q->where('accounts.id', $this->id))->get();
                foreach ($pendingEntries as $pendingEntry) {
                    $pendingEntry->accounts()->sync([]);
                    $pendingEntry->delete();
                }

                // Archived entries aren't replayed, so only this account's side is dropped -
                // other accounts keep their history
                $archivedEntryIds = DB::table('archived_entry_accounts')
                    ->where('account_id', $this->id)
                    ->distinct()
                    ->pluck('archived_entry_id')
                    ->toArray();
                $archivedCount = DB::table('archived_entry_accounts')->where('account_id', $this->id)->delete();
                DB::table('archived_entries')
                    ->whereIn('id', $archivedEntryIds)
                    ->whereNotExists(
                        fn($q) => $q->select(DB::raw(1))
                            ->from('archived_entry_accounts')
                            ->whereColumn('archived_entry_accounts.archived_entry_id', 'archived_entries.id')
                    )
                    ->delete();

                DB::table('titles_accounts')->where('account_id', $this->id)->delete();

                $name = $this->name;
                $entryCount = count($entryIds);
                $this->delete();

                // Restore each affected account to the opening balance it had, then replay
                $initialBalances = [];
                foreach ($affected as $account) {
                    $account->applyOpeningBalance($openings[$account->id]['balance'], $openings[$account->id]['foreign']);
                    $initialBalances[$account->id] = [
                        'balance' => $openings[$account->id]['balance'],
                        'foreign_balance' => $openings[$account->id]['foreign'],
                    ];
                }

                AppLog::info(
                    "Deleted account $name",
                    desc: "Removed $entryCount journal entries, $archivedCount archived rows and "
                        . count($pendingEntries) . ' pending entries'
                );

                $result = JournalEntry::refreshAllBalances($initialBalances);

                if ($result['success']) {
                    $result['message'] = "Deleted account \"$name\" with $entryCount journal entries. " . $result['message'];
                }

                return $result;
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete account", desc: $e->getMessage(), loggable: $this);
            return $this->failedResult('Failed to delete account: ' . $e->getMessage());
        }
    }

    /**
     * Merge this account into $target. The target keeps its own name, code and place in
     * the tree; this account's entries, pending entries, history and children move over,
     * then this account is deleted.
     *
     * Balances: the running snapshots moving across belong to this account's history, so
     * they can't be trusted on the target. Both opening balances are captured first,
     * added together (flipped when the two natures differ, since a balance is stored
     * relative to its account's nature) and applied to the target before the replay.
     *
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int, 'errors' => array]
     */
    public function mergeInto(self $target): array
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('merge', $this)) {
            return $this->failedResult('You are not allowed to merge accounts');
        }

        if ($target->id == $this->id) {
            return $this->failedResult('Cannot merge an account into itself');
        }

        if ($this->default_currency != $target->default_currency) {
            return $this->failedResult(
                "The two accounts hold different currencies ($this->default_currency and $target->default_currency), "
                    . 'their foreign balances cannot be added together'
            );
        }

        // An entry holding both accounts collapses into a single row on the target, which
        // only works when the two rows share a currency
        $conflict = DB::table('entry_accounts as source')
            ->join('entry_accounts as destination', function ($join) use ($target) {
                $join->on('destination.journal_entry_id', '=', 'source.journal_entry_id')
                    ->where('destination.account_id', $target->id);
            })
            ->where('source.account_id', $this->id)
            ->whereColumn('source.currency', '!=', 'destination.currency')
            ->value('source.journal_entry_id');

        if ($conflict) {
            return $this->failedResult(
                "Journal entry #$conflict holds both accounts with different currencies, it has to be fixed first"
            );
        }

        try {
            return DB::transaction(function () use ($target) {
                $sourceOpening = $this->getOpeningBalance();
                $targetOpening = $target->getOpeningBalance();

                $movedEntries = DB::table('entry_accounts')->where('account_id', $this->id)->count();

                // Entries holding both accounts: net the two rows into one on the target
                $this->netPivotRowsInto('entry_accounts', 'journal_entry_id', $target);
                DB::table('entry_accounts')->where('account_id', $this->id)->update(['account_id' => $target->id]);

                $this->netPivotRowsInto('unapp_entry_accounts', 'unapproved_entry_id', $target);
                DB::table('unapp_entry_accounts')->where('account_id', $this->id)->update(['account_id' => $target->id]);

                DB::table('archived_entry_accounts')->where('account_id', $this->id)->update(['account_id' => $target->id]);

                // Titles the source is attached to, skipping the ones the target already has
                DB::table('titles_accounts')
                    ->where('account_id', $this->id)
                    ->whereIn(
                        'entry_title_id',
                        DB::table('titles_accounts')->where('account_id', $target->id)->pluck('entry_title_id')->toArray()
                    )
                    ->delete();
                DB::table('titles_accounts')->where('account_id', $this->id)->update(['account_id' => $target->id]);

                DB::table('account_settings')->where('account_id', $this->id)->update(['account_id' => $target->id]);
                DB::table('comm_profiles')->where('account_id', $this->id)->update(['account_id' => $target->id]);
                DB::table('insurance_companies')->where('account_id', $this->id)->update(['account_id' => $target->id]);

                // Children move under the target. If the target is one of them it takes
                // this account's own place instead, so the tree keeps its shape.
                foreach ($this->children_accounts()->get() as $child) {
                    $child->parent_account_id = $child->id == $target->id ? $this->parent_account_id : $target->id;
                    $child->save();
                }

                $name = $this->name;
                $this->delete();

                // Both histories now sit on the target, so both opening balances do too
                $sourceSign = $this->nature == $target->nature ? 1 : -1;
                $openingBalance = $targetOpening['balance'] + $sourceSign * $sourceOpening['balance'];
                $openingForeign = $targetOpening['foreign'] + $sourceSign * $sourceOpening['foreign'];

                $target->applyOpeningBalance($openingBalance, $openingForeign);

                AppLog::info("Merged account $name into $target->name", loggable: $target);

                $result = JournalEntry::refreshAllBalances([
                    $target->id => ['balance' => $openingBalance, 'foreign_balance' => $openingForeign],
                ]);

                if ($result['success']) {
                    $result['message'] = "Merged \"$name\" into \"$target->name\" with $movedEntries entry rows. " . $result['message'];
                }

                return $result;
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't merge account", desc: $e->getMessage(), loggable: $this);
            return $this->failedResult('Failed to merge account: ' . $e->getMessage());
        }
    }

    /**
     * Move this account and its entire subtree under a new parent.
     *
     * Only the root account's parent_account_id changes; children keep pointing at
     * their existing parent inside the moved subtree. Relative account codes are kept,
     * and the root code is reassigned only when it would collide with a sibling under
     * the new parent. saved_full_code is rebuilt for the whole subtree afterwards.
     *
     * Journal entry balances are tied to account IDs, not tree position, so a reparent
     * does not require refreshAllBalances().
     *
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int, 'errors' => array]
     */
    public function moveTo(?self $newParent): array
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('move', $this)) {
            return $this->failedResult('You are not allowed to move accounts');
        }

        $newParentId = $newParent?->id;
        if ($this->parent_account_id == $newParentId) {
            return $this->failedResult('Account is already under this parent');
        }

        $subtree = $this->getSelfAndDescendants();
        $subtreeIds = $subtree->pluck('id')->all();

        if ($newParent && in_array($newParent->id, $subtreeIds, true)) {
            return $this->failedResult('Cannot move an account under one of its descendants');
        }

        if ($newParent && $newParent->main_account_id != $this->main_account_id) {
            return $this->failedResult('The new parent must belong to the same main account');
        }

        if ($newParent && DB::table('entry_accounts')->where('account_id', $newParent->id)->exists()) {
            return $this->failedResult(
                'The target parent account has journal entries and cannot have sub-accounts'
            );
        }

        try {
            return DB::transaction(function () use ($newParent, $newParentId, $subtree) {
                $oldFullCode = $this->saved_full_code ?: $this->full_code;
                $oldCode = $this->code;

                $codeTaken = self::where('main_account_id', $this->main_account_id)
                    ->where('parent_account_id', $newParentId)
                    ->where('code', $this->code)
                    ->whereNotIn('id', $subtreeIds)
                    ->exists();

                if ($codeTaken) {
                    $this->code = self::getNextCode($this->main_account_id, $newParentId);
                }

                $this->parent_account_id = $newParentId;
                $this->save();

                $this->rebuildSubtreeFullCodes();

                $moved = $this->fresh();
                $newFullCode = $moved->saved_full_code;
                $codeNote = $oldCode == $moved->code
                    ? "code {$moved->code} kept"
                    : "code changed from {$oldCode} to {$moved->code} to avoid a sibling collision";

                AppLog::info(
                    "Moved account {$moved->name} from {$oldFullCode} to {$newFullCode} ({$codeNote})",
                    loggable: $moved
                );

                return [
                    'success' => true,
                    'message' => "Moved \"{$moved->name}\" and {$subtree->count()} account(s) under "
                        . ($newParent ? "\"{$newParent->name}\"" : 'the top level')
                        . ". New code path: {$newFullCode}. {$codeNote}.",
                    'accounts_processed' => $subtree->count(),
                    'errors' => [],
                ];
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't move account", desc: $e->getMessage(), loggable: $this);
            return $this->failedResult('Failed to move account: ' . $e->getMessage());
        }
    }

    /**
     * Rebuild saved_full_code for this account and every descendant.
     */
    private function rebuildSubtreeFullCodes(): void
    {
        $this->loadMissing(['main_account', 'parent_account']);

        if (!$this->parent_account_id) {
            $fullCode = $this->main_account->code . '-' . $this->code;
        } else {
            $parentFullCode = $this->parent_account->saved_full_code ?: $this->parent_account->full_code;
            $fullCode = $parentFullCode . '-' . $this->code;
        }

        if ($this->saved_full_code !== $fullCode) {
            $this->saved_full_code = $fullCode;
            $this->saveQuietly();
        }

        foreach ($this->children_accounts()->get() as $child) {
            $child->rebuildSubtreeFullCodes();
        }
    }

    /**
     * What deleting this account would take with it, so the confirmation can spell it out
     *
     * @return array ['entries' => int, 'accounts' => int, 'pending' => int, 'archived' => int]
     */
    public function deletionImpact(): array
    {
        $entryIds = DB::table('entry_accounts')
            ->where('account_id', $this->id)
            ->distinct()
            ->pluck('journal_entry_id')
            ->toArray();

        return [
            'entries' => count($entryIds),
            'accounts' => DB::table('entry_accounts')
                ->whereIn('journal_entry_id', $entryIds)
                ->where('account_id', '!=', $this->id)
                ->distinct()
                ->count('account_id'),
            'pending' => UnapprovedEntry::whereHas('accounts', fn($q) => $q->where('accounts.id', $this->id))->count(),
            'archived' => DB::table('archived_entry_accounts')->where('account_id', $this->id)->count(),
        ];
    }

    /**
     * The balance this account carried before its first live entry - everything archived
     * or set as an opening balance. Derived the same way refreshAllBalances() derives it,
     * by reversing the first entry off its own snapshot.
     *
     * @return array ['balance' => float, 'foreign' => float]
     */
    public function getOpeningBalance(): array
    {
        $firstEntry = JournalEntry::byAccount($this->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        $pivot = $firstEntry ? DB::table('entry_accounts')
            ->where('journal_entry_id', $firstEntry->id)
            ->where('account_id', $this->id)
            ->first() : null;

        if (!$pivot) {
            // No entries to reverse - whatever sits on the account is the opening balance
            return ['balance' => (float) $this->balance, 'foreign' => (float) $this->foreign_balance];
        }

        $sign = $pivot->nature == $this->nature ? 1 : -1;
        $balance = $pivot->account_balance - $sign * $pivot->amount;

        $foreign = 0.0;
        if ($pivot->currency && $pivot->currency != JournalEntry::CURRENCY_EGP && $pivot->currency == $this->default_currency) {
            $foreign = ($pivot->account_foreign_balance ?? 0) - $sign * ($pivot->currency_amount ?? 0);
        }

        return ['balance' => (float) $balance, 'foreign' => (float) $foreign];
    }

    /**
     * Collapse rows that would leave the target holding two rows on the same entry - the
     * balance replay only ever reads one row per (entry, account) pair. Amounts are netted
     * on the debit side; a pair that cancels out drops off the entry entirely.
     */
    private function netPivotRowsInto($table, $entryColumn, self $target)
    {
        $shared = DB::table($table . ' as source')
            ->join($table . ' as destination', function ($join) use ($entryColumn, $target) {
                $join->on('destination.' . $entryColumn, '=', 'source.' . $entryColumn)
                    ->where('destination.account_id', $target->id);
            })
            ->where('source.account_id', $this->id)
            ->select('source.id as source_id', 'destination.id as destination_id')
            ->get();

        foreach ($shared as $pair) {
            $source = DB::table($table)->where('id', $pair->source_id)->first();
            $destination = DB::table($table)->where('id', $pair->destination_id)->first();

            $signed = fn($row, $field) => ($row->nature == self::NATURE_DEBIT ? 1 : -1) * ($row->$field ?? 0);

            $amount = $signed($source, 'amount') + $signed($destination, 'amount');
            $currencyAmount = $signed($source, 'currency_amount') + $signed($destination, 'currency_amount');

            DB::table($table)->where('id', $pair->source_id)->delete();

            if (round($amount, 2) == 0 && round($currencyAmount, 2) == 0) {
                // The two sides cancel out - the entry no longer touches this account
                DB::table($table)->where('id', $pair->destination_id)->delete();
                continue;
            }

            DB::table($table)->where('id', $pair->destination_id)->update([
                'nature' => $amount >= 0 ? self::NATURE_DEBIT : self::NATURE_CREDIT,
                'amount' => abs($amount),
                'currency_amount' => abs($currencyAmount),
                'currency_rate' => $currencyAmount ? abs($amount / $currencyAmount) : $destination->currency_rate,
            ]);
        }
    }

    /** Tables that would silently lose their link if this account went away */
    private function findExternalReferences(): ?string
    {
        $references = [
            'comm_profiles' => 'commission profile',
            'insurance_companies' => 'insurance company',
            'account_settings' => 'account setting',
        ];

        foreach ($references as $table => $label) {
            $count = DB::table($table)->where('account_id', $this->id)->count();
            if ($count) {
                return "This account is linked to $count $label record(s). Unlink or merge it instead.";
            }
        }

        return null;
    }

    private function failedResult($message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'accounts_processed' => 0,
            'errors' => [$message],
        ];
    }

    /**
     * Set the opening balance of this account and every account below it to zero,
     * then refresh all entry balances
     *
     * @return array ['success' => bool, 'message' => string, 'accounts_processed' => int, 'errors' => array]
     */
    public function clearBalanceWithChildren(): array
    {
        try {
            return DB::transaction(function () {
                $accounts = $this->getSelfAndDescendants();
                $initialBalances = [];

                foreach ($accounts as $account) {
                    // Same per-account work setOpeningBalance() does, with a zero opening balance
                    $account->applyOpeningBalance(0, 0);
                    $initialBalances[$account->id] = ['balance' => 0, 'foreign_balance' => 0];
                }

                AppLog::info('Cleared balances of account and its children (' . $accounts->count() . ' accounts)', loggable: $this);

                // A single refresh pass rebuilds the snapshots for every account
                $result = JournalEntry::refreshAllBalances($initialBalances);

                if ($result['success']) {
                    $result['message'] = 'Cleared balances for ' . $accounts->count() . ' account(s). ' . $result['message'];
                }

                return $result;
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't clear balances", desc: $e->getMessage(), loggable: $this);
            return [
                'success' => false,
                'message' => 'Failed to clear balances: ' . $e->getMessage(),
                'accounts_processed' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * This account plus every descendant account, at any depth
     *
     * @return Collection<self>
     */
    public function getSelfAndDescendants(): Collection
    {
        $accounts = collect([$this]);
        $parentIds = [$this->id];

        while (count($parentIds)) {
            $children = self::whereIn('parent_account_id', $parentIds)
                ->whereNotIn('id', $accounts->pluck('id')->toArray()) //guard against a cyclic parent reference
                ->get();

            if ($children->isEmpty()) {
                break;
            }

            $accounts = $accounts->concat($children);
            $parentIds = $children->pluck('id')->toArray();
        }

        return $accounts;
    }

    /**
     * Rebase this account on a new opening balance by rewriting the snapshot of its
     * first entry - the caller is responsible for refreshing the following entries
     *
     * @param float $balance The opening balance amount (balance BEFORE any entries)
     * @param float|null $foreignBalance The opening foreign balance amount (optional)
     */
    private function applyOpeningBalance(float $balance, ?float $foreignBalance = null): void
    {
        // Get the first entry for this account
        $firstEntry = JournalEntry::byAccount($this->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($firstEntry) {
            // Get the first entry's pivot data
            $firstPivot = DB::table('entry_accounts')
                ->where('journal_entry_id', $firstEntry->id)
                ->where('account_id', $this->id)
                ->first();

            if ($firstPivot) {
                // Calculate what the first entry's account_balance should be
                // based on the new opening balance
                $entryAmount = $firstPivot->amount;
                $entryNature = $firstPivot->nature;

                // Apply the entry effect to the opening balance
                if ($entryNature == $this->nature) {
                    // Same nature increases balance
                    $newFirstEntryBalance = $balance + $entryAmount;
                } else {
                    // Opposite nature decreases balance
                    $newFirstEntryBalance = $balance - $entryAmount;
                }

                // Update the first entry's account_balance in pivot table
                DB::table('entry_accounts')
                    ->where('journal_entry_id', $firstEntry->id)
                    ->where('account_id', $this->id)
                    ->update(['account_balance' => $newFirstEntryBalance]);

                // Handle foreign balance if provided
                if ($foreignBalance !== null && $firstPivot->currency && $firstPivot->currency != JournalEntry::CURRENCY_EGP && $firstPivot->currency == $this->default_currency) {
                    $entryForeignAmount = $firstPivot->currency_amount ?? 0;

                    if ($entryNature == $this->nature) {
                        $newFirstEntryForeignBalance = $foreignBalance + $entryForeignAmount;
                    } else {
                        $newFirstEntryForeignBalance = $foreignBalance - $entryForeignAmount;
                    }

                    DB::table('entry_accounts')
                        ->where('journal_entry_id', $firstEntry->id)
                        ->where('account_id', $this->id)
                        ->update(['account_foreign_balance' => $newFirstEntryForeignBalance]);
                }
            }
        } else {
            // No entries exist, just update the account balance directly
            $this->balance = $balance;
            if ($foreignBalance !== null) {
                $this->foreign_balance = $foreignBalance;
            }
            $this->save();
        }
    }

    public function editInfo($code, $name, $nature, $main_account_id, $parent_account_id = null, $desc = null, $default_currency = JournalEntry::CURRENCY_EGP, $is_show_dashboard = false): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) {
            return false;
        }

        try {
            $this->update([
                'code' => $code,
                'name' => $name,
                'nature' => $nature,
                'main_account_id' => $main_account_id,
                'parent_account_id' => $parent_account_id,
                'desc' => $desc,
                'default_currency' => $default_currency,
                'is_show_dashboard' => $is_show_dashboard,
            ]);
            AppLog::info('Updating account', loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit account", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///attributes
    public function getFullCodeAttribute()
    {
        $this->loadMissing('parent_account');
        $this->loadMissing('main_account');
        $full_code = '';
        if (!$this->parent_account) {
            $full_code = $this->main_account->code . '-' . $this->code;
        } else {
            $full_code = $this->parent_account->full_code . '-' . $this->code;
        }
        if ($this->saved_full_code != $full_code) {
            $this->update(['saved_full_code' => $full_code]);
        }
        return $full_code;
    }

    public function getTotalBalanceAttribute()
    {
        $this->loadMissing('children_accounts');
        $blnce = 0;
        foreach ($this->children_accounts as $ac) {
            $blnce += $ac->total_balance;
        }
        return $blnce + $this->balance;
    }

    public function getTotalLastEntryBalance(Carbon $date)
    {
        $children = $this->children_accounts()->includeLastEntryBalance($date)->get();
        $blnce = 0;
        foreach ($children as $ac) {
            $blnce += $ac->getTotalLastEntryBalance($date);
        }
        return $blnce + $this->last_entry_balance;
    }

    public function getTotalCurrencyBalanceAttribute()
    {
        $this->load('children_accounts');
        $blnce = 0;
        foreach ($this->children_accounts as $ac) {
            $blnce += $ac->foreign_balance;
        }
        return $blnce + $this->foreign_balance;
    }

    public function getTotalLastEntryCurrencyBalance(Carbon $date)
    {
        $children = $this->children_accounts()->includeLastEntryBalance($date)->get();
        $blnce = 0;
        foreach ($children as $ac) {
            $blnce += $ac->getTotalLastEntryCurrencyBalance($date);
        }
        return $blnce + $this->last_entry_currency_balance;
    }

    /**
     * Get the EGP balance for this account at a given moment using the
     * running balance snapshot stored on the latest journal entry up to
     * (and including) the supplied date.
     *
     * The caller controls the time component of $date — pass
     * `Carbon::parse(...)->endOfDay()` to include the whole day.
     *
     * @param Carbon $date            Cutoff timestamp.
     * @param bool   $includeChildren Recursively add the balances of all
     *                                descendant accounts.
     * @return float
     */
    public function getBalanceAtDate(Carbon $date, bool $includeChildren = true): float
    {
        $latest = DB::table('entry_accounts')
            ->join('journal_entries', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
            ->where('entry_accounts.account_id', $this->id)
            ->where('journal_entries.created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->orderByDesc('journal_entries.created_at')
            ->orderByDesc('journal_entries.id')
            ->select('entry_accounts.account_balance')
            ->first();

        $balance = $latest ? (float) $latest->account_balance : 0.0;

        if ($includeChildren) {
            $this->loadMissing('children_accounts');
            foreach ($this->children_accounts as $child) {
                $balance += $child->getBalanceAtDate($date, true);
            }
        }

        return $balance;
    }

    /**
     * Foreign-currency counterpart of getBalanceAtDate(). Reads the
     * `account_foreign_balance` snapshot of the latest entry up to $date.
     */
    public function getForeignBalanceAtDate(Carbon $date, bool $includeChildren = true): float
    {
        $latest = DB::table('entry_accounts')
            ->join('journal_entries', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
            ->where('entry_accounts.account_id', $this->id)
            ->where('journal_entries.created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->orderByDesc('journal_entries.created_at')
            ->orderByDesc('journal_entries.id')
            ->select('entry_accounts.account_foreign_balance')
            ->first();

        $balance = $latest ? (float) $latest->account_foreign_balance : 0.0;

        if ($includeChildren) {
            $this->loadMissing('children_accounts');
            foreach ($this->children_accounts as $child) {
                $balance += $child->getForeignBalanceAtDate($date, true);
            }
        }

        return $balance;
    }

    /**
     * Alternative balance-at-date method: ignores the per-entry snapshot
     * and recomputes the balance from scratch by summing every debit and
     * credit posted up to $date and applying the account's nature.
     *
     * Useful as a verification path when the running snapshots may have
     * drifted, or for accounts whose snapshots are unreliable.
     */
    public function getBalanceAtDateFromEntries(Carbon $date, bool $includeChildren = true): float
    {
        $totals = DB::table('entry_accounts')
            ->join('journal_entries', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
            ->where('entry_accounts.account_id', $this->id)
            ->where('journal_entries.created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->selectRaw('COALESCE(SUM(CASE WHEN entry_accounts.nature = "debit"  THEN entry_accounts.amount ELSE 0 END), 0) AS total_debit')
            ->selectRaw('COALESCE(SUM(CASE WHEN entry_accounts.nature = "credit" THEN entry_accounts.amount ELSE 0 END), 0) AS total_credit')
            ->first();

        $debit  = (float) ($totals->total_debit  ?? 0);
        $credit = (float) ($totals->total_credit ?? 0);

        $balance = $this->nature === self::NATURE_DEBIT
            ? $debit - $credit
            : $credit - $debit;

        if ($includeChildren) {
            $this->loadMissing('children_accounts');
            foreach ($this->children_accounts as $child) {
                $balance += $child->getBalanceAtDateFromEntries($date, true);
            }
        }

        return $balance;
    }

    /**
     * Foreign-currency counterpart of getBalanceAtDateFromEntries().
     */
    public function getForeignBalanceAtDateFromEntries(Carbon $date, bool $includeChildren = true): float
    {
        $totals = DB::table('entry_accounts')
            ->join('journal_entries', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
            ->where('entry_accounts.account_id', $this->id)
            ->where('journal_entries.created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->selectRaw('COALESCE(SUM(CASE WHEN entry_accounts.nature = "debit"  THEN entry_accounts.currency_amount ELSE 0 END), 0) AS total_debit')
            ->selectRaw('COALESCE(SUM(CASE WHEN entry_accounts.nature = "credit" THEN entry_accounts.currency_amount ELSE 0 END), 0) AS total_credit')
            ->first();

        $debit  = (float) ($totals->total_debit  ?? 0);
        $credit = (float) ($totals->total_credit ?? 0);

        $balance = $this->nature === self::NATURE_DEBIT
            ? $debit - $credit
            : $credit - $debit;

        if ($includeChildren) {
            $this->loadMissing('children_accounts');
            foreach ($this->children_accounts as $child) {
                $balance += $child->getForeignBalanceAtDateFromEntries($date, true);
            }
        }

        return $balance;
    }

    public function getIsForeignAttribute()
    {
        return $this->default_currency !== JournalEntry::CURRENCY_EGP;
    }

    public function getHasChildrenAttribute()
    {
        $this->loadCount('children_accounts');
        return $this->children_accounts_count > 0;
    }

    public function getIsTopParentAttribute()
    {
        return is_null($this->parent_account_id);
    }

    ///scopes
    public function scopeByNature($query, $nature)
    {
        return $query->where('nature', $nature);
    }

    public function scopeByName($query, $text)
    {
        return $query->where('accounts.name', '=', "$text");
    }

    public function scopeSearchBy($query, $text)
    {
        return $query->where('accounts.name', 'LIKE', "%$text%");
    }

    public function scopeByMainAccount($query, $main_account_id)
    {
        return $query->where('main_account_id ', $main_account_id);
    }

    public function scopeOrderByCode($query)
    {
        return $query->select('accounts.*')->join('main_accounts', 'main_accounts.id', '=', 'accounts.main_account_id')->orderBy('main_accounts.code')->orderBy('accounts.code');
    }

    public function scopeParentAccounts($query)
    {
        return $query->whereNull('parent_account_id');
    }

    /**
     * Accounts that have children, and so don't take entries themselves - a parent
     * account carries its children's totals, posting to it directly double counts.
     */
    public function scopeBlocksEntries($query)
    {
        return $query->whereExists(
            fn($q) => $q->select(DB::raw(1))
                ->from('accounts as child_accounts')
                ->whereColumn('child_accounts.parent_account_id', 'accounts.id')
        );
    }

    public function scopeIncludeLastEntryBalance($query, Carbon $date)
    {
        return $query->select('accounts.*')
            ->selectRaw('(SELECT entry_accounts.account_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at <= ? ORDER BY journal_entries.created_at DESC, journal_entries.id DESC LIMIT 1 ) as last_entry_balance', [$date->format('Y-m-d H:i')])
            ->selectRaw('(SELECT entry_accounts.account_foreign_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at <= ? ORDER BY journal_entries.created_at DESC, journal_entries.id DESC LIMIT 1 ) as last_entry_currency_balance', [$date->format('Y-m-d H:i')]);
    }

    /**
     * Running balance snapshot of the latest entry strictly BEFORE $date - the balance
     * an account starts a period with. Complements totalEntries(), which counts the
     * entries from $date onwards.
     */
    public function scopeIncludeBalanceBefore($query, Carbon $date)
    {
        return $query->select('accounts.*')
            ->selectRaw('(SELECT entry_accounts.account_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at < ? ORDER BY journal_entries.created_at DESC, journal_entries.id DESC LIMIT 1 ) as start_entry_balance', [$date->format('Y-m-d H:i')])
            ->selectRaw('(SELECT entry_accounts.account_foreign_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at < ? ORDER BY journal_entries.created_at DESC, journal_entries.id DESC LIMIT 1 ) as start_entry_currency_balance', [$date->format('Y-m-d H:i')]);
    }

    public function scopeTotalEntries($query, Carbon $from, Carbon $to)
    {
        return $query
            ->select('accounts.*')
            ->selectRaw('(SELECT SUM(IF(entry_accounts.nature = "debit" , entry_accounts.amount , 0 )) FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at BETWEEN ? AND ?) as debit_amount', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->selectRaw('(SELECT SUM(IF(entry_accounts.nature = "credit" , entry_accounts.amount , 0 )) FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at BETWEEN ? AND ?) as credit_amount', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->selectRaw('(SELECT SUM(IF(entry_accounts.nature = "debit" , entry_accounts.currency_amount , 0 )) FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at BETWEEN ? AND ?) as debit_foreign_amount', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->selectRaw('(SELECT SUM(IF(entry_accounts.nature = "credit" , entry_accounts.currency_amount , 0 )) FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at BETWEEN ? AND ?) as credit_foreign_amount', [$from->format('Y-m-d H:i'), $to->format('Y-m-d H:i')])
            ->groupBy('accounts.id');
    }

    ////relations
    public function credit_entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function debit_entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function main_account()
    {
        return $this->belongsTo(MainAccount::class);
    }

    public function parent_account()
    {
        return $this->belongsTo(self::class, 'parent_account_id');
    }

    public function children_accounts()
    {
        return $this->hasMany(self::class, 'parent_account_id');
    }

    /**
     * Build the per-key balance map for the income statement.
     *
     * For every key in AccountSetting::ACCOUNT_KEYS this returns the EGP
     * balance at the start date, the EGP balance at the end date, and the
     * activity (end - start) for the period. Each configured account's
     * balance already includes the balances of all of its descendant
     * accounts, and the calc_type (add/subtract) on the setting is applied
     * when several accounts contribute to the same key.
     *
     * @return array<string, array{start: float, end: float, change: float}>
     */
    public static function buildIncomeStatementBalances(Carbon $startDate, Carbon $endDate): array
    {
        $settings = AccountSetting::getAllSettingsWithCalcType();

        $accountIds = collect($settings)
            ->flatten(1)
            ->pluck('account_id')
            ->filter()
            ->unique()
            ->all();

        // Eager-load every involved account with its full descendant tree
        // so each getBalanceAtDate() call avoids re-fetching children.
        $accounts = self::with('children_accounts.children_accounts.children_accounts')
            ->whereIn('id', $accountIds)
            ->get()
            ->keyBy('id');

        $balances = [];
        foreach (AccountSetting::ACCOUNT_KEYS as $key => $label) {
            $startBalance = 0.0;
            $endBalance   = 0.0;

            foreach ($settings[$key] ?? [] as $row) {
                $account = $accounts->get($row['account_id']);
                if (!$account) {
                    continue;
                }

                $accStart = $account->getBalanceAtDate($startDate, true);
                $accEnd   = $account->getBalanceAtDate($endDate, true);

                if (($row['calc_type'] ?? AccountSetting::CALC_TYPE_ADD) === AccountSetting::CALC_TYPE_SUBTRACT) {
                    $startBalance -= $accStart;
                    $endBalance   -= $accEnd;
                } else {
                    $startBalance += $accStart;
                    $endBalance   += $accEnd;
                }
            }

            $balances[$key] = [
                'start'  => $startBalance,
                'end'    => $endBalance,
                'change' => $endBalance - $startBalance,
            ];
        }

        return $balances;
    }

    /**
     * Generate the income statement (قائمة الدخل) Excel report.
     *
     * The report shows three numeric columns for every line item:
     *  - Balance at end date (cumulative through end date)
     *  - Balance at start date (cumulative through start date)
     *  - Period activity (end - start)
     *
     * Each configured account contributes its own balance plus the
     * recursive balance of every descendant account. Accounts attached to
     * the same setting key are aggregated using their calc_type (add or
     * subtract) so contras (e.g. sales returns) reduce the corresponding
     * total.
     *
     * @param Carbon $startDate Start of the comparison period.
     * @param Carbon $endDate   End of the comparison period.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|false
     */
    public static function generateIncomeStatementReport(Carbon $startDate, Carbon $endDate)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('viewAny', self::class)) {
            return false;
        }

        try {
            // Use end-of-day boundaries so each date includes the full
            // day's activity in its cumulative balance.
            $startDate = $startDate->copy()->endOfDay();
            $endDate   = $endDate->copy()->endOfDay();

            $balances = self::buildIncomeStatementBalances($startDate, $endDate);

            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->setRightToLeft(true);

            // Title
            $activeSheet->setCellValue('A1', 'قائمة الدخل');
            $activeSheet->mergeCells('A1:E1');
            $activeSheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $activeSheet->getStyle('A1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Period subtitle
            $activeSheet->setCellValue('A2', 'الفترة من ' . $startDate->format('Y/m/d') . ' إلى ' . $endDate->format('Y/m/d'));
            $activeSheet->mergeCells('A2:E2');
            $activeSheet->getStyle('A2')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Column headers
            $row = 4;
            $activeSheet->setCellValue('A' . $row, 'البيان');
            $activeSheet->setCellValue('B' . $row, 'رقم إيضاح');
            $activeSheet->setCellValue('C' . $row, 'رصيد ' . $endDate->format('Y/m/d') . ' (ج.م)');
            $activeSheet->setCellValue('D' . $row, 'رصيد ' . $startDate->format('Y/m/d') . ' (ج.م)');
            $activeSheet->setCellValue('E' . $row, 'حركة الفترة (ج.م)');

            $activeSheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
            $activeSheet->getStyle('A' . $row . ':E' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE0E0E0');

            $headerRow = $row;
            $row++;

            // Helper: write a value line for a configured key.
            $writeLine = function (string $key, string $label, ?string $note = null) use ($activeSheet, &$row, $balances) {
                $activeSheet->setCellValue('A' . $row, $label);
                if ($note) {
                    $activeSheet->setCellValue('B' . $row, $note);
                }
                $activeSheet->setCellValue('C' . $row, $balances[$key]['end']);
                $activeSheet->setCellValue('D' . $row, $balances[$key]['start']);
                $activeSheet->setCellValue('E' . $row, $balances[$key]['change']);
                $row++;
            };

            // Helper: write a computed/total line that uses Excel formulas
            // for C, D, and E (E always equals C-D).
            $writeFormulaLine = function (string $label, string $cFormula, string $dFormula, bool $highlight = false) use ($activeSheet, &$row) {
                $activeSheet->setCellValue('A' . $row, $label);
                $activeSheet->setCellValue('C' . $row, $cFormula);
                $activeSheet->setCellValue('D' . $row, $dFormula);
                $activeSheet->setCellValue('E' . $row, '=C' . $row . '-D' . $row);
                $activeSheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
                if ($highlight) {
                    $activeSheet->getStyle('A' . $row . ':E' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFFCCCC');
                }
                $thisRow = $row;
                $row++;
                return $thisRow;
            };

            // SECTION 1 — Revenue & cost of revenue
            $writeLine('net_revenues', 'صافي الإيرادات', '(8)');
            $netRevenuesRow = $row - 1;

            $writeLine('cost_of_revenues', 'تكلفة الحصول علي الايرادات', '(7)');
            $costRow = $row - 1;

            $grossProfitRow = $writeFormulaLine(
                'مجمل ربح',
                '=C' . $netRevenuesRow . '+C' . $costRow,
                '=D' . $netRevenuesRow . '+D' . $costRow,
            );
            $row++; // spacer

            // SECTION 2 — Operating expenses (subtracted from gross profit)
            $activeSheet->setCellValue('A' . $row, 'يخصم:');
            $activeSheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            $expensesStartRow = $row;
            $writeLine('fixed_assets_depreciation', 'اهلاك الأصول الثابته');
            $writeLine('general_administrative_expenses', 'مصروفات عمومية وإدارية', '(9)');
            $writeLine('solidarity_contribution', 'مساهمة تكافلية');
            $writeLine('establishment_expenses', 'مصروفات تأسيس');
            $expensesEndRow = $row - 1;

            $totalExpensesRow = $writeFormulaLine(
                'إجمالي المصروفات',
                '=SUM(C' . $expensesStartRow . ':C' . $expensesEndRow . ')',
                '=SUM(D' . $expensesStartRow . ':D' . $expensesEndRow . ')',
            );

            $netOperatingProfitRow = $writeFormulaLine(
                'صافي أرباح النشاط',
                '=C' . $grossProfitRow . '-C' . $totalExpensesRow,
                '=D' . $grossProfitRow . '-D' . $totalExpensesRow,
            );
            $row++; // spacer

            // SECTION 3 — Other income / expenses (added to operating profit)
            $activeSheet->setCellValue('A' . $row, 'يضاف / يخصم:');
            $activeSheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            $otherStartRow = $row;
            $writeLine('other_revenues', 'إيرادات أخرى');
            $writeLine('interest_income', 'فوائد دائنة');
            $writeLine('foreign_exchange', 'أرباح (خسائر) ترجمة العملات الاجنبية');
            $writeLine('provisions', 'مخصصات');
            $otherEndRow = $row - 1;
            $row++; // spacer

            $profitBeforeTaxRow = $writeFormulaLine(
                'صافي أرباح العام قبل الضرائب',
                '=C' . $netOperatingProfitRow . '+SUM(C' . $otherStartRow . ':C' . $otherEndRow . ')',
                '=D' . $netOperatingProfitRow . '+SUM(D' . $otherStartRow . ':D' . $otherEndRow . ')',
            );

            // Taxes
            $writeLine('deferred_income_tax', 'ضريبة الدخل المؤجله');
            $deferredTaxRow = $row - 1;

            $writeLine('income_tax', 'ضريبة الدخل');
            $incomeTaxRow = $row - 1;
            $row++; // spacer

            $writeFormulaLine(
                'صافي ارباح /خسائر العام بعد الضرائب',
                '=C' . $profitBeforeTaxRow . '+C' . $deferredTaxRow . '+C' . $incomeTaxRow,
                '=D' . $profitBeforeTaxRow . '+D' . $deferredTaxRow . '+D' . $incomeTaxRow,
                highlight: true,
            );

            // Number formatting on the three numeric columns
            $activeSheet->getStyle('C' . ($headerRow + 1) . ':E' . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00;[Red](#,##0.00)');

            // Column widths
            $activeSheet->getColumnDimension('A')->setWidth(45);
            $activeSheet->getColumnDimension('B')->setWidth(12);
            $activeSheet->getColumnDimension('C')->setWidth(22);
            $activeSheet->getColumnDimension('D')->setWidth(22);
            $activeSheet->getColumnDimension('E')->setWidth(22);

            // Borders around the data area
            $activeSheet->getStyle('A' . $headerRow . ':E' . ($row - 1))
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

            // Save & return as download
            $filename = 'income_statement_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';
            $directory = storage_path('app/public/reports');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            $filePath = $directory . DIRECTORY_SEPARATOR . $filename;

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            Log::error('Failed to generate income statement: ' . $e->getMessage());
            report($e);
            return false;
        }
    }
}
