<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use App\Models\Insurance\Company;
use App\Models\Offers\Offer;
use App\Helpers\Helpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CompanyCommPayment extends Model
{
    const MORPH_TYPE = 'comp_comm_payment';

    use HasFactory, SoftDeletes;

    const FILES_DIRECTORY = 'sold_policies/company_comm_docs/';

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];

    protected $table = 'company_comm_payments';
    protected $fillable = [
        'status',
        'type',
        'amount',
        'note',
        'payment_date',
        'doc_url',
        'receiver_id',
        'invoice_id',
        'pymnt_perm'
    ];

    ///model functions
    public function setInfo($type, $note = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::error("Setting Company Comm info", loggable: $this);
            return $this->update([
                "type"  =>  $type,
                "note"  =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setDocument($doc_url)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            if ($this->doc_url)
                Storage::delete($this->doc_url);
            AppLog::info("Setting Comm Payment document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Comm Payment document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function deleteDocument()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            if ($this->doc_url) {
                Storage::delete($this->doc_url);
                $this->doc_url = null;
                $this->save();
            }
            AppLog::info("Deleting Comm Payment document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Comm Payment document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function setAsPaid(?Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::error("Setting Company Comm as paid", loggable: $this);
            if ($this->update([
                "receiver_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_PAID,
            ])) {
                $this->load('sold_policy');

                $this->sold_policy->calculateTotalCompanyPayments();
                $this->sold_policy->updateSalesCommsPaymentInfo();
            }
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setAsCancelled(Carbon $date = null, $skip_check = false)
    {
        if ($skip_check) {
            /** @var User */
            $user = Auth::user();
            if (!$user->can('update', $this)) return false;
        }

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            AppLog::error("Setting Company Comm as cancelled", loggable: $this);
            return $this->update([
                "receiver_id"   =>  Auth::id(),
                "payment_date"  => $date->format('Y-m-d H:i'),
                "status"  =>  self::PYMT_STATE_CANCELLED,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Company Comm info failed", desc: $e->getMessage(), loggable: $this);
        }
    }
    public function delete()
    {
        return $this->setAsCancelled();
    }
    ///attributes
    public function getIsNewAttribute()
    {
        return $this->status == self::PYMT_STATE_NEW;
    }

    ///scopes
    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }
    public function scopeNotPaid(Builder $query)
    {
        $query->whereNot('status', self::PYMT_STATE_PAID);
    }

    ///relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    ///static functions
    public static function exportReport(
        ?bool $is_renewal = null,
        ?Carbon $start_from = null,
        ?Carbon $start_to = null,
        ?Carbon $expiry_from = null,
        ?Carbon $expiry_to = null,
        ?Carbon $issued_from = null,
        ?Carbon $issued_to = null,
        ?Company $selectedCompany = null,
        ?string $searchText = null,
        ?array $filteredStatus = null,
        ?string $sortColumn = null,
        ?string $sortDirection = 'asc',
        ?array $types = [],
        ?Carbon $payment_date_from = null,
        ?Carbon $payment_date_to = null
    ) {
        $payments = self::report(
            $is_renewal,
            $start_from,
            $start_to,
            $expiry_from,
            $expiry_to,
            $issued_from,
            $issued_to,
            $selectedCompany,
            $searchText,
            $filteredStatus,
            $sortColumn,
            $sortDirection,
            $types,
            $payment_date_from,
            $payment_date_to
        )->get();

        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        // Set worksheet title
        $activeSheet->setTitle('Company Commission Payments');

        // Set headers
        $headers = [
            'A1' => 'Policy#',
            'B1' => 'Client',
            'C1' => 'Issue Date',
            'D1' => 'Payment Date',
            'E1' => 'Amount',
            'F1' => 'Tax Amount',
            'G1' => 'Status',
            'H1' => 'Type',
            'I1' => 'Insurance Company',
            'J1' => 'Invoice#'
        ];

        foreach ($headers as $cell => $header) {
            $activeSheet->setCellValue($cell, $header);
        }

        // Style headers
        $activeSheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeSheet->getStyle('A1:J1')->getFill()->setFillType(Fill::FILL_SOLID);
        $activeSheet->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FFD3D3D3');

        // Auto-size columns
        foreach (range('A', 'I') as $columnID) {
            $activeSheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $i = 2;
        /** @var CompanyCommPayment $payment */
        foreach ($payments as $payment) {
            $activeSheet->getCell('A' . $i)->setValue($payment->sold_policy ? $payment->sold_policy->policy_number : '');
            $activeSheet->getCell('B' . $i)->setValue($payment->sold_policy && $payment->sold_policy->client ? ($payment->sold_policy->client->full_name ?? $payment->sold_policy->client->name ?? 'N/A') : 'N/A');
            $activeSheet->getCell('C' . $i)->setValue($payment->sold_policy && $payment->sold_policy->created_at ? Carbon::parse($payment->sold_policy->created_at)->format('d/m/Y') : '');
            $activeSheet->getCell('D' . $i)->setValue($payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A');
            $activeSheet->getCell('E' . $i)->setValue(number_format($payment->amount - $payment->tax_amount, 2, '.', ','));
            $activeSheet->getCell('F' . $i)->setValue(number_format($payment->tax_amount, 2, '.', ','));
            $activeSheet->getCell('F' . $i)->setValue(ucfirst($payment->status));
            $activeSheet->getCell('G' . $i)->setValue(ucwords(str_replace('_', ' ', $payment->type)));
            $activeSheet->getCell('H' . $i)->setValue($payment->sold_policy && $payment->sold_policy->policy && $payment->sold_policy->policy->company ? $payment->sold_policy->policy->company->name : 'N/A');
            $activeSheet->getCell('I' . $i)->setValue($payment->invoice && $payment->invoice->serial ? $payment->invoice->serial : 'N/A');
            $i++;
        }

        // Add borders to data table
        if ($i > 2) {
            $activeSheet->getStyle('A1:J' . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $writer = new Xlsx($spreadsheet);
        $file_path = 'company_comm_payments_export.xlsx';
        $public_file_path = storage_path('app/' . $file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function scopeReport(
        $query,
        ?bool $is_renewal = null,
        ?Carbon $start_from = null,
        ?Carbon $start_to = null,
        ?Carbon $expiry_from = null,
        ?Carbon $expiry_to = null,
        ?Carbon $issued_from = null,
        ?Carbon $issued_to = null,
        ?Company $selectedCompany = null,
        ?string $searchText = null,
        ?array $filteredStatus = null,
        ?string $sortColumn = null,
        ?string $sortDirection = 'asc',
        ?array $types = [],
        ?Carbon $payment_date_from = null,
        ?Carbon $payment_date_to = null
    ) {
        $query->select('company_comm_payments.*')
            ->leftJoin('sold_policies', 'sold_policies.id', '=', 'company_comm_payments.sold_policy_id')
            ->leftJoin('policies', 'policies.id', '=', 'sold_policies.policy_id')
            ->leftJoin('offers', 'offers.id', '=', 'sold_policies.offer_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'company_comm_payments.invoice_id')
            ->leftJoin('corporates', function ($j) {
                $j->on('sold_policies.client_id', '=', 'corporates.id')
                    ->where('sold_policies.client_type', '=', \App\Models\Corporates\Corporate::MORPH_TYPE);
            })
            ->leftJoin('customers', function ($j) {
                $j->on('sold_policies.client_id', '=', 'customers.id')
                    ->where('sold_policies.client_type', '=', \App\Models\Customers\Customer::MORPH_TYPE);
            })
            ->leftJoin('insurance_companies', 'insurance_companies.id', '=', 'policies.company_id')
            ->whereNot(function ($q) {
                $q->where('company_comm_payments.status', '=', self::PYMT_STATE_CANCELLED)
                    ->where('invoices.serial', '=', null);
            })
            ->with('sold_policy.client', 'sold_policy.policy.company', 'sold_policy.creator', 'receiver')
            ->when($is_renewal, function ($q, $v) {
                $q->where('offers.is_renewal', $v);
            })
            ->when($start_from, function ($q, $v) {
                $q->where('sold_policies.start', '>=', $v->format('Y-m-d 00:00:00'));
            })
            ->when($start_to, function ($q, $v) {
                $q->where('sold_policies.start', '<=', $v->format('Y-m-d 23:59:59'));
            })
            ->when($issued_from, function ($q, $v) {
                $q->where('sold_policies.created_at', '>=', $v->format('Y-m-d 00:00:00'));
            })
            ->when($issued_to, function ($q, $v) {
                $q->where('sold_policies.created_at', '<=', $v->format('Y-m-d 23:59:59'));
            })
            ->when($expiry_from, function ($q, $v) {
                $q->where('sold_policies.expiry', '>=', $v->format('Y-m-d 00:00:00'));
            })
            ->when($expiry_to, function ($q, $v) {
                $q->where('sold_policies.expiry', '<=', $v->format('Y-m-d 23:59:59'));
            })
            ->when($payment_date_from, function ($q, $v) {
                $q->where('company_comm_payments.payment_date', '>=', $v->format('Y-m-d 00:00:00'));
            })
            ->when($payment_date_to, function ($q, $v) {
                $q->where('company_comm_payments.payment_date', '<=', $v->format('Y-m-d 23:59:59'));
            })
            ->when($selectedCompany, fn($q) => $q->where('insurance_companies.id', $selectedCompany->id))
            ->when($searchText, function ($q, $s) {
                $q->where(function ($q) use ($s) {
                    $q->where('sold_policies.policy_number', 'LIKE', "%$s%")
                        ->orWhereHas('invoice', function ($q) use ($s) {
                            $q->where('serial', 'LIKE', "%$s%");
                        });
                });
            })
            ->when($filteredStatus && count($filteredStatus), function ($q) use ($filteredStatus) {
                $q->whereIn('company_comm_payments.status', $filteredStatus);
            })
            ->when($types && count($types), function ($q) use ($types) {
                $q->whereIn('company_comm_payments.type', $types);
            })
            ->when($sortColumn === 'payment_date', fn($q) => $q->orderBy('company_comm_payments.payment_date', $sortDirection))
            ->when($sortColumn === 'created_at', fn($q) => $q->orderBy('sold_policies.created_at', $sortDirection))
            ->when($sortColumn === 'amount', fn($q) => $q->orderBy('company_comm_payments.amount', $sortDirection))
            ->groupBy('company_comm_payments.id');

        return $query;
    }
}
