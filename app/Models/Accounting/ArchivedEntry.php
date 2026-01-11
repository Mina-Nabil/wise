<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Users\AppLog;
use App\Models\Business\SoldPolicy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ArchivedEntry extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'archived_entry';

    protected $table = 'archived_entries';
    protected $fillable = [
        'journal_entry_id',
        'user_id',
        'entry_title_id',
        'approver_id',
        'approved_at',
        'is_reviewed',
        'day_serial',
        'receiver_name',
        'cash_entry_type',
        'comment',
        'revert_entry_id',
        'cash_serial',
        'extra_note',
        'archived_at',
        'archived_by',
    ];

    ////static functions
    public static function archiveEntry(JournalEntry $journalEntry, $archived_by = null, $useTransaction = true)
    {
        try {
            $archivedEntry = new self([
                'journal_entry_id' => $journalEntry->id,
                'user_id' => $journalEntry->user_id,
                'entry_title_id' => $journalEntry->entry_title_id,
                'approver_id' => $journalEntry->approver_id,
                'approved_at' => $journalEntry->approved_at,
                'is_reviewed' => $journalEntry->is_reviewed,
                'day_serial' => $journalEntry->day_serial,
                'receiver_name' => $journalEntry->receiver_name,
                'cash_entry_type' => $journalEntry->cash_entry_type,
                'comment' => $journalEntry->comment,
                // 'revert_entry_id' => $journalEntry->revert_entry_id,
                'cash_serial' => $journalEntry->cash_serial,
                'extra_note' => $journalEntry->extra_note,
                'archived_at' => Carbon::now(),
                'archived_by' => $archived_by ?? Auth::id(),
            ]);

            $journalEntry->load('accounts');
            $accounts_arr = [];
            foreach ($journalEntry->accounts as $ac) {
                $accounts_arr[$ac->id] = [
                    'nature' => $ac->pivot->nature,
                    'amount' => $ac->pivot->amount,
                    'account_balance' => $ac->pivot->account_balance,
                    'account_foreign_balance' => $ac->pivot->account_foreign_balance,
                    'currency' => $ac->pivot->currency,
                    'currency_amount' => $ac->pivot->currency_amount,
                    'currency_rate' => $ac->pivot->currency_rate,
                    'doc_url' => $ac->pivot->doc_url,
                ];
            }

            $saveFunction = function () use ($archivedEntry, $accounts_arr) {
                $archivedEntry->save();
                foreach ($accounts_arr as $account_id => $entry_arr) {
                    $archivedEntry->accounts()->attach($account_id, $entry_arr);
                }
            };

            if ($useTransaction) {
                DB::transaction($saveFunction);
            } else {
                $saveFunction();
            }

            AppLog::info("Archived journal entry", loggable: $archivedEntry);
            return $archivedEntry;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't archive entry", desc: $e->getMessage());
            return false;
        }
    }

    /**
     * Archive all journal entries created before or equal to the given date
     * This function uses a transaction - if any entry fails, all changes are rolled back
     * 
     * @param Carbon $date The cutoff date (entries <= this date will be archived)
     * @param int|null $archived_by User ID who is performing the archive (defaults to current user)
     * @return array ['success' => bool, 'message' => string, 'archived_count' => int]
     */
    public static function archiveEntriesByDate(Carbon $date, $archived_by = null)
    {
        try {
            $archived_by = $archived_by ?? Auth::id();
            $archivedCount = 0;

            DB::transaction(function () use ($date, $archived_by, &$archivedCount) {
                // Get all journal entries created before or equal to the given date
                $journalEntries = JournalEntry::whereDate('created_at', '<=', $date->format('Y-m-d'))
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($journalEntries as $journalEntry) {
                    // Archive the entry (without nested transaction)
                    // If this fails, exception will propagate and rollback entire transaction
                    $archivedEntry = self::archiveEntry($journalEntry, $archived_by, false);
                    
                    if (!$archivedEntry) {
                        throw new Exception("Failed to archive entry ID: {$journalEntry->id}");
                    }

                    // Delete the original journal entry and its related pivot records
                    // If this fails, exception will propagate and rollback entire transaction
                    $journalEntry->accounts()->sync([]);
                    if (!$journalEntry->delete()) {
                        throw new Exception("Failed to delete entry ID: {$journalEntry->id} after archiving");
                    }
                    
                    $archivedCount++;
                }
            });

            $message = "Successfully archived {$archivedCount} journal entries.";

            AppLog::info("Bulk archived journal entries", desc: "Archived {$archivedCount} entries up to {$date->format('Y-m-d')}");

            return [
                'success' => true,
                'message' => $message,
                'archived_count' => $archivedCount,
                'errors' => []
            ];
        } catch (Exception $e) {
            report($e);
            AppLog::error("Failed to bulk archive entries", desc: $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to archive entries: ' . $e->getMessage() . '. All changes have been rolled back.',
                'archived_count' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    ///model functions
    public function deleteRecord()
    {
        $this->accounts()->sync([]);
        $this->delete();
        return true;
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'archived_entry_accounts')->withPivot([
            'nature',
            'amount',
            'account_balance',
            'account_foreign_balance',
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
        return $this->belongsTo(\App\Models\Users\User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'approver_id');
    }

    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'archived_by');
    }

    public function journal_entry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Download archived entries with account hierarchy
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @param int|null $account_id Optional account ID to filter by
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function downloadArchivedEntries(Carbon $from, Carbon $to, $account_id = null)
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
        $activeSheet->setCellValue('Q1', 'Archived At');
        $activeSheet->setCellValue('R1', 'Archived By');

        // Style headers
        $activeSheet->getStyle('A1:R1')->getFont()->setBold(true);
        $activeSheet->getStyle('A1:R1')->getFill()->setFillType(Fill::FILL_SOLID);
        $activeSheet->getStyle('A1:R1')->getFill()->getStartColor()->setARGB('FFFFFF00'); // Yellow background

        // Get archived entries with accounts
        $entries = self::between($from, $to)
            ->when($account_id, function ($query) use ($account_id) {
                $query->whereHas('accounts', function ($query) use ($account_id) {
                    $query->where('account_id', $account_id);
                });
            })
            ->with(['accounts.parent_account', 'accounts.parent_account.parent_account', 'accounts.parent_account.parent_account.parent_account', 'creator', 'entry_title', 'archivedBy'])
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
                $activeSheet->setCellValue('B' . $row, Carbon::parse($entry->archived_at)->format('d/m/Y'));
                $activeSheet->setCellValue('C' . $row, Carbon::parse($entry->archived_at)->format('h:i:s A'));
                $activeSheet->setCellValue('N' . $row, $entry->comment);
                $activeSheet->setCellValue('O' . $row, $entry->creator->username ?? '');
                $activeSheet->setCellValue('P' . $row, ucfirst($account->nature));
                $activeSheet->setCellValue('Q' . $row, Carbon::parse($entry->archived_at)->format('d/m/Y H:i:s'));
                $activeSheet->setCellValue('R' . $row, $entry->archivedBy->username ?? '');

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
        $file_path = SoldPolicy::FILES_DIRECTORY . "archived_entries.xlsx";
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

    public function scopeBetween($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween('archived_at', [
            $from->format('Y-m-d 00:00:00'),
            $to->format('Y-m-d 23:59:59')
        ]);
    }
}

