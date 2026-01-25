<?php

namespace App\Models\Payments;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\InvoiceExtra;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Invoice extends Model
{
    const FILES_DIRECTORY = 'comm_payments/';
    const MORPH_TYPE = 'invoice';

    const TAX_RATE = 0.0;

    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by',
        'serial',
        'gross_total',
        'tax_total',
        'net_total',
        'created_journal_entry_id',
        'paid_journal_entry_id'
    ];

    ///static functions
    /** @param  array $sold_policies_entries should contain an array of associated arrays [ 'id' => ? , 'amount' => ?, 'pymnt_perm' => ? ]  */
    public static function newInvoice($company_id, $serial, $gross_total, $sold_policies_entries = [], $extras_ids = [])
    {
        $newInvoice = new self([
            "company_id"    =>  $company_id,
            "serial"        =>  $serial,
            "created_by"    =>  Auth::id(),
            "gross_total"   =>  $gross_total,
            "tax_total"     => ($gross_total * self::TAX_RATE),
            "net_total"     => ($gross_total * (1 - self::TAX_RATE)),
        ]);
        try {

            DB::transaction(function () use ($newInvoice, $sold_policies_entries, $serial, $extras_ids) {
                $newInvoice->save();
                foreach ($sold_policies_entries as $sp) {
                    /** @var SoldPolicy */
                    $soldPolicy = SoldPolicy::find($sp['id']);
                    $soldPolicy->addCompanyPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $sp['amount'] / (1 - self::TAX_RATE) , "added automatically from invoice#$serial", $newInvoice->id, $sp['pymnt_perm'], true, ($sp['amount'] / (1 - self::TAX_RATE)) * self::TAX_RATE);
                }
                InvoiceExtra::whereIn('id', $extras_ids)->update(['invoice_id' => $newInvoice->id]);
            });
            AppLog::info("Invoice created", loggable: $newInvoice);
            return $newInvoice;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create invoice", $e->getMessage());
            return false;
        }
    }

    public static function getNextSerial()
    {
        return (self::orderByDesc('serial')->limit(1)->first()->serial ?? 0) + 1;
    }


    ///model functions\
    public function createCreatedJournalEntry()
    {
        if ($this->created_journal_entry_id) {
            throw new Exception('Created journal entry already exists');
        }

        try {
            DB::transaction(function () {
                $company = Company::find($this->company_id);
                if ($company->account_id) {
                    $journalEntry = JournalEntry::newJournalEntry(
                        entry_title_id: JournalEntry::INVOICE_CREATED_ID,
                        comment: "فاتوره رقم $this->serial",
                        skip_auth: true,
                        accounts: [
                            $company->account_id =>  [
                                'nature' => 'debit',
                                'amount' => $this->net_total,
                                'currency' => JournalEntry::CURRENCY_EGP
                            ],
                            Account::TAX_ACCOUNT_ID =>  [
                                'nature' => 'debit',
                                'amount' => $this->tax_total,
                                'currency' => JournalEntry::CURRENCY_EGP
                            ],
                            Account::SALES_EGP_ACCOUNT_ID =>  [
                                'nature' => 'credit',
                                'amount' => $this->gross_total,
                                'currency' => JournalEntry::CURRENCY_EGP
                            ],
                        ],
                    );
                }

                if (!$company->account_id) {
                    throw new Exception('Company has no account', 12);
                }
                if (!is_a($journalEntry, JournalEntry::class)) {
                    throw new Exception('Failed to create journal entry', 12);
                }
                $this->created_journal_entry_id = $journalEntry->id;
                $this->save();
            });
        } catch (Exception $e) {
            if ($e->getCode() == 12) {
                throw $e;
            }
            report($e);
            return false;
        }
        return true;
    }

    public function createPaidJournalEntry($bank_account_id, $trans_fees = 0)
    {
        if ($this->paid_journal_entry_id) {
            throw new Exception('Paid journal entry already exists');
        }

        try {
            DB::transaction(function () use ($bank_account_id, $trans_fees) {
                $company = Company::find($this->company_id);
                $accounts = [];
                if ($trans_fees) {
                    $accounts[Account::TRANS_FEES_ACCOUNT_ID] = [
                        'nature' => 'debit',
                        'amount' => $trans_fees,
                        'currency' => JournalEntry::CURRENCY_EGP
                    ];
                }
                $accounts[$bank_account_id] = [
                    'nature' => 'debit',
                    'amount' => $this->net_total - $trans_fees,
                    'currency' => JournalEntry::CURRENCY_EGP
                ];

                $accounts[$company->account_id] = [
                    'nature' => 'credit',
                    'amount' => $this->net_total,
                    'currency' => JournalEntry::CURRENCY_EGP
                ];

                if ($company->account_id) {
                    $journalEntry = JournalEntry::newJournalEntry(
                        entry_title_id: JournalEntry::INVOICE_PAID_ID,
                        comment: "فاتوره رقم $this->serial",
                        skip_auth: true,
                        accounts: $accounts
                    );
                }

                if (!is_a($journalEntry, JournalEntry::class)) {
                    throw new Exception('Failed to create journal entry', 12);
                }
                $this->paid_journal_entry_id = $journalEntry->id;
                $this->save();
            });
        } catch (Exception $e) {
            if ($e->getCode() == 12) {
                throw $e;
            }
            report($e);
            return false;
        }
        return true;
    }


    public function confirmInvoice(Carbon $date = null)
    {
        try {

            DB::transaction(function () use ($date) {
                /** @var CompanyCommPayment */
                foreach ($this->commissions()->get() as $comm) {
                    $comm->setAsPaid($date);
                }
            });
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function getIsConfirmedAttribute()
    {
        return !$this->commissions()->whereNull('payment_date')->exists();
    }


    public function printInvoice()
    {
        $this->load('commissions', 'commissions.sold_policy', 'commissions.sold_policy.client');
        $template = IOFactory::load(resource_path('import/company_invoice.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 3;
        foreach ($this->commissions as $comm) {
            $activeSheet->getCell('A' . $i)->setValue($this->serial);
            $activeSheet->getCell('B' . $i)->setValue((new Carbon($this->created_at))->format('d-M-y'));
            $activeSheet->getCell('D' . $i)->setValue($comm->sold_policy->policy_number);
            $activeSheet->getCell('E' . $i)->setValue($comm->sold_policy->client?->name);
            $activeSheet->getCell('F' . $i)->setValue((new Carbon($comm->sold_policy->start))->format('d-M-y'));
            $activeSheet->getCell('G' . $i)->setValue($comm->pymnt_perm);
            $activeSheet->getCell('O' . $i)->setValue('اذن صرف عمولة ' . $comm->pymnt_perm);
            $activeSheet->getCell('I' . $i)->setValue($comm->amount + $comm->tax_amount);
            $activeSheet->getCell('J' . $i)->setValue($comm->tax_amount);
            $activeSheet->getCell('K' . $i)->setValue($comm->amount);
            $activeSheet->insertNewRowBefore($i);
        }
        $activeSheet->removeRow(2);
        $activeSheet->removeRow(2);

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "invoice{$this->serial}.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public static function exportReport(?Carbon $created_from = null, ?Carbon $created_to = null, array $company_ids = [], ?string $searchText = null, ?bool $is_paid = null)
    {
        $invoicesQuery = self::report($created_from, $created_to, $company_ids, $searchText, $is_paid);

        $invoices = $invoicesQuery->get();

        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $activeSheet->setCellValue('A1', 'System ID');
        $activeSheet->setCellValue('B1', 'Serial');
        $activeSheet->setCellValue('C1', 'Creation Date');
        $activeSheet->setCellValue('D1', 'Creator');
        $activeSheet->setCellValue('E1', 'Company');
        $activeSheet->setCellValue('F1', 'Gross Total');
        $activeSheet->setCellValue('G1', 'Tax Total');
        $activeSheet->setCellValue('H1', 'Net Total');
        $activeSheet->setCellValue('I1', 'Payment Date');

        // Style headers
        $activeSheet->getStyle('A1:I1')->getFont()->setBold(true);
        $activeSheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $activeSheet->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FFCCCCCC');

        $i = 2;
        foreach ($invoices as $invoice) {
            $activeSheet->setCellValue('A' . $i, $invoice->id);
            $activeSheet->setCellValue('B' . $i, $invoice->serial);
            $activeSheet->setCellValue('C' . $i, $invoice->created_at ? $invoice->created_at->format('Y-m-d') : 'N/A');
            $activeSheet->setCellValue('D' . $i, $invoice->creator->username ?? 'N/A');
            $activeSheet->setCellValue('E' . $i, $invoice->company->name ?? 'N/A');
            $activeSheet->setCellValue('F' . $i, $invoice->gross_total);
            $activeSheet->setCellValue('G' . $i, $invoice->tax_total);
            $activeSheet->setCellValue('H' . $i, $invoice->net_total);
            $activeSheet->setCellValue('I' . $i, $invoice->payment_date ?? 'Not Paid');
            $i++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $activeSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $file_path = self::FILES_DIRECTORY . "invoices_export.xlsx";
        $public_file_path = storage_path($file_path);
        
        // Make sure directory exists
        if (!file_exists(dirname($public_file_path))) {
            mkdir(dirname($public_file_path), 0755, true);
        }
        
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function deleteInvoice()
    {
        // Check if invoice has any journal entries
        if ($this->created_journal_entry_id || $this->paid_journal_entry_id) {
            return [
                'success' => false,
                'message' => 'Cannot delete invoice. There are journal entries linked.'
            ];
        }

        try {
            DB::transaction(function () {
                /** @var CompanyCommPayment */
                foreach ($this->commissions()->get() as $comm) {
                    // Check if commission payment has any journal entries
                    // Note: CompanyCommPayment doesn't have direct journal_entry_id
                    // but we can add additional checks here if needed in the future
                    $comm->delete();
                }
                $this->delete();
            });
            AppLog::info("Invoice deleted", loggable: $this);
            return [
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ];
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't delete invoice", $e->getMessage(), $this);
            return [
                'success' => false,
                'message' => 'Failed to delete invoice: ' . $e->getMessage()
            ];
        }
    }

    public function getPaymentDateAttribute()
    {
        if ($this->commissions()->exists()) {
            return $this->commissions()->first()->payment_date ? Carbon::parse($this->commissions()->first()->payment_date)->format('d-M-y') : null;
        }
        return null;
    }

    ////scopes
    public function scopeReport(Builder $query, ?Carbon $created_from = null, ?Carbon $created_to = null, array $company_ids = [], ?string $searchText = null, ?bool $is_paid = null)
    {
        return $query->with(['creator', 'commissions', 'company'])
        ->when($created_from, function ($query) use ($created_from) {
            $query->whereDate('created_at', '>=', $created_from);
        })
        ->when($created_to, function ($query) use ($created_to) {
            $query->whereDate('created_at', '<=', $created_to);
        })
        ->when($company_ids, function ($query) use ($company_ids) {
            $query->whereIn('company_id', $company_ids);
        })
        ->when($is_paid, function ($query) use ($is_paid) {
            $query->whereHas('commissions', function ($q) use ($is_paid) {
                $q->where('status', CompanyCommPayment::PYMT_STATE_PAID);
            });
        })
        ->when($is_paid === false, function ($query) use ($is_paid) {
            $query->whereHas('commissions', function ($q) use ($is_paid) {
                $q->whereNot('status', CompanyCommPayment::PYMT_STATE_PAID);
            });
        })
        ->when($searchText, function ($query) use ($searchText) {
            $query->where(function ($q) use ($searchText) {
                $q->where('serial', 'like', "%{$searchText}%")
                    ->orWhereHas('creator', function ($q) use ($searchText) {
                        $q->where('first_name', 'like', "%{$searchText}%")
                            ->orWhere('last_name', 'like', "%{$searchText}%");
                    });
            });
        })
        ->latest();
    }

    ////relations
    public function commissions(): HasMany
    {
        return $this->hasMany(CompanyCommPayment::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function extras(): HasMany
    {
        return $this->hasMany(InvoiceExtra::class);
    }

    ////attributes

    public function getTransFeesAttribute()
    {
        $journalEntry = JournalEntry::with('accounts')->find($this->paid_journal_entry_id);
        if (!$journalEntry) {
            return 0;
        }
        $account = $journalEntry->accounts->wherePivot('account_id', Account::TRANS_FEES_ACCOUNT_ID)->first();
        return $account->pivot->amount ?? 5;
    }
}
