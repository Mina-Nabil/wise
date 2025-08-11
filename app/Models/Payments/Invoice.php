<?php

namespace App\Models\Payments;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\UnapprovedEntry;
use App\Models\Business\SoldPolicy;
use App\Models\Insurance\Company;
use App\Models\Insurance\InvoiceExtra;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Http\Client\Common\Plugin\Journal;
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

    const TAX_RATE = 0.05;

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
                    $soldPolicy->addCompanyPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $sp['amount'], "added automatically from invoice#$serial", $newInvoice->id, $sp['pymnt_perm'], true, $sp['amount'] * self::TAX_RATE);
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
            $activeSheet->getCell('F' . $i)->setValue((new Carbon($comm->sold_policy->issuing_date))->format('d-M-y'));
            $activeSheet->getCell('G' . $i)->setValue($comm->pymnt_perm);
            $activeSheet->getCell('O' . $i)->setValue('اذن صرف عمولة ' . $comm->pymnt_perm);
            $activeSheet->getCell('I' . $i)->setValue($comm->amount - $comm->tax_amount);
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

    public function deleteInvoice()
    {
        try {
            DB::transaction(function () {
                /** @var PolicyComm */
                foreach ($this->commissions()->get() as $comm) {
                    $comm->delete();
                }
                $this->delete();
            });
            AppLog::info("Invoice delete");
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create invoice", $e->getMessage(), $this);
            return false;
        }
    }

    public function getPaymentDateAttribute()
    {
        if ($this->commissions()->exists()) {
            return $this->commissions()->first()->payment_date ? Carbon::parse($this->commissions()->first()->payment_date)->format('d-M-y') : null;
        }
        return null;
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
}
