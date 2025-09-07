<?php

namespace App\Models\Accounting;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

    ////model functions
    public function downloadAccountDetails(Carbon $from, Carbon $to)
    {
        $debit_entries = $this->debit_entries()->with('entry_title')->between($from, $to)->get();
        $credit_entries = $this->credit_entries()->with('entry_title')->between($from, $to)->get();
        $all_entries = $debit_entries->merge($credit_entries);
        $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getSheet(0);

        $activeSheet->getCell('C3')->setValue('تحليلى	' . $this->name);

        $i = 8;
        foreach ($all_entries as $e) {
            $activeSheet->getCell('C' . $i)->setValue($e->id);
            $activeSheet->getCell('D' . $i)->setValue(Carbon::parse($e->created_at)->format('d / M / Y'));

            $activeSheet->getCell('E' . $i)->setValue($e->entry_title->name);

            if ($e->debit_id) {
                $activeSheet->getCell('E' . $i)->setValue($e->amount);
                $activeSheet->getCell('H' . $i)->setValue($e->debit_balance);
            } else {
                $activeSheet->getCell('F' . $i)->setValue($e->amount);
                $activeSheet->getCell('H' . $i)->setValue($e->credit_balance);
            }
            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . 'account_balance.xlsx';
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
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

            // Set headers
            $activeSheet->setCellValue('A1', 'Account Code');
            $activeSheet->setCellValue('B1', 'Account Name');
            $activeSheet->setCellValue('C1', 'Nature');
            $activeSheet->setCellValue('D1', 'Debit');
            $activeSheet->setCellValue('E1', 'Credit');
            $activeSheet->setCellValue('F1', 'Debit Foreign');
            $activeSheet->setCellValue('G1', 'Credit Foreign');

            // Style headers
            $activeSheet->getStyle('A1:G1')->getFont()->setBold(true);
            $activeSheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $activeSheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FFCCCCCC');

            // Get all accounts with their relationships
            Log::info($to);
            $accounts = self::orderByCode()->includeLastEntryBalance($to)
                ->when($main_accounts_only && $mode == 'balance', fn($q) => $q->parentAccounts())
                ->when($mode == 'entries' && $from && $to, fn($q) => $q->totalEntries($from, $to));
            Log::info($accounts->toSql());
            $accounts = $accounts->get();



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
                    $included_levels
                );
            }

            // Auto-size columns
            foreach (range('A', 'E') as $col) {
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
            $activeSheet->setCellValue('D' . $row, number_format($balance, 2));
            $activeSheet->setCellValue('E' . $row, number_format($foreignBalance, 2));

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

    private static function addAccountToExport($activeSheet, $account, $row, &$processedAccounts, $allAccounts, $indentLevel = 0, $mode = 'balance', $show_zero = true, $main_accounts_only = false, Carbon $from = null, Carbon $to = null, $included_levels)
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
        $totalBalance = $account->total_last_entry_balance;
        $totalCurrencyBalance = $account->total_last_entry_currency_balance;
        $debitAmount = '';
        $creditAmount = '';
        $debitForeignAmount = '';
        $creditForeignAmount = '';
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
            $debitAmount = number_format($account->sumChildrenEntries('debit', $from, $to), 2);
            $creditAmount = number_format($account->sumChildrenEntries('credit', $from, $to), 2);
            $debitForeignAmount = number_format($account->sumChildrenEntries('foreign_debit', $from, $to), 2);
            $creditForeignAmount = number_format($account->sumChildrenEntries('foreign_credit', $from, $to), 2);
        }

        // Add account to spreadsheet
        if (($show_zero || $debitAmount || $creditAmount || $debitForeignAmount || $creditForeignAmount) && (!$main_accounts_only || $indentLevel == 0)) {
            $activeSheet->setCellValue('A' . $row, $account->full_code);
            $activeSheet->setCellValue('B' . $row, $accountName);
            $activeSheet->setCellValue('C' . $row, ucfirst($account->nature));
            $activeSheet->setCellValue('D' . $row, $debitAmount);
            $activeSheet->setCellValue('E' . $row, $creditAmount);
            $activeSheet->setCellValue('F' . $row, $debitForeignAmount);
            $activeSheet->setCellValue('G' . $row, $creditForeignAmount);

            // Style parent accounts differently
            if ($indentLevel == 0) {
                $activeSheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
            }

            $row++;
        }

        // Process children recursively
        $children = $allAccounts->where('parent_account_id', $account->id);
        foreach ($children as $child) {
            $row = self::addAccountToExport($activeSheet, $child, $row, $processedAccounts, $allAccounts, $indentLevel + 1, $mode, $show_zero, $main_accounts_only, $from, $to, $included_levels);
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

    public function getTotalLastEntryBalanceAttribute()
    {
        $this->load('children_accounts');
        $blnce = 0;
        foreach ($this->children_accounts as $ac) {
            $blnce += $ac->total_last_entry_balance;
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

    public function getTotalLastEntryCurrencyBalanceAttribute()
    {
        $this->loadMissing('children_accounts');
        $blnce = 0;
        foreach ($this->children_accounts as $ac) {
            $blnce += $ac->total_last_entry_currency_balance;
        }
        return $blnce + $this->last_entry_currency_balance;
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

    public function scopeIncludeLastEntryBalance($query, Carbon $date)
    {
        return $query->select('accounts.*')
            ->selectRaw('(SELECT entry_accounts.account_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at <= ? LIMIT 1 ORDER BY journal_entries.created_at DESC, journal_entries.id DESC) as last_entry_balance', [$date->format('Y-m-d H:i')])
            ->selectRaw('(SELECT entry_accounts.account_foreign_balance FROM entry_accounts JOIN journal_entries ON journal_entries.id = entry_accounts.journal_entry_id WHERE entry_accounts.account_id = accounts.id AND journal_entries.created_at <= ? LIMIT 1 ORDER BY journal_entries.created_at DESC, journal_entries.id DESC) as last_entry_currency_balance', [$date->format('Y-m-d H:i')]);
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
}
