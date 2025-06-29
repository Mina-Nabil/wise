<?php

namespace App\Models\Payments;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CommProfilePayment extends Model
{
    use HasFactory;

    const MORPH_TYPE = 'comm_profile_payment';

    const FILES_DIRECTORY = 'sold_policies/comm_prof_pymt_docs/';

    const PYMT_TYPE_CASH = 'cash';
    const PYMT_TYPE_CHEQUE = 'cheque';
    const PYMT_TYPE_BANK_TRNSFR = 'bank_transfer';

    const PYMT_TYPES = [
        self::PYMT_TYPE_CASH,
        self::PYMT_TYPE_CHEQUE,
        self::PYMT_TYPE_BANK_TRNSFR,
    ];

    const PYMT_STATE_NEW = 'new';
    const PYMT_STATE_PAID = 'paid';
    const PYMT_STATE_APPROVED = 'approved';
    const PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_APPROVED,
        self::PYMT_STATE_CANCELLED,
    ];

    protected $fillable = [
        'status',
        'type',
        'amount',
        'note',
        'payment_date',
        'doc_url',
        'needs_approval',
        'creator_id',
        'approver_id'
    ];

    ///model functions
    public function createMainJournalEntry($entry_title_id)
    {
        $this->load('comm_profile');
        if (!$this->comm_profile->account_id) throw new Exception("Comm Profile has no account");
        if ($this->journal_entry_id) throw new Exception("Journal Entry already created");
        try {

            DB::transaction(function () use ($entry_title_id) {
                $this->load('sales_commissions', 'sales_commissions.sold_policy');
                $total_gross = $this->sales_commissions->sum('sold_policy.gross_premium');
                $total_comm = $this->amount;
                $diff = $total_gross - $total_comm;

                $journalEntry = JournalEntry::newJournalEntry($entry_title_id, skip_auth: true, accounts: [
                    $this->comm_profile->account_id =>  [
                        'nature' => Account::NATURE_DEBIT,
                        'amount' => $total_comm,
                        'currency' => JournalEntry::CURRENCY_EGP,
                    ],
                    Account::OHDA_ACCOUNT_ID => [
                        'nature' => Account::NATURE_DEBIT,
                        'amount' => $diff,
                        'currency' => JournalEntry::CURRENCY_EGP,
                    ],
                    Account::OTHER_DEBIT_ACCOUNT_ID => [
                        'nature' => Account::NATURE_CREDIT,
                        'amount' => $total_gross,
                        'currency' => JournalEntry::CURRENCY_EGP,
                    ],
                ]);
                $this->journal_entry_id = $journalEntry->id;
                $this->save();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Creating Journal Entry failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function createSalesJournalEntry($entry_title_id)
    {
        $this->load('comm_profile');
        if (!$this->comm_profile->account_id) throw new Exception("Comm Profile has no account");
        if ($this->journal_entry_id) throw new Exception("Journal Entry already created");
        try {

            DB::transaction(function () use ($entry_title_id) {

                $journalEntry = JournalEntry::newJournalEntry($entry_title_id, skip_auth: true, accounts: [
                    $this->comm_profile->account_id =>  [
                        'nature' => Account::NATURE_DEBIT,
                        'amount' => $this->amount,
                        'currency' => JournalEntry::CURRENCY_EGP,
                    ],
                    Account::OHDA_ACCOUNT_ID => [
                        'nature' => Account::NATURE_CREDIT,
                        'amount' => $this->amount,
                        'currency' => JournalEntry::CURRENCY_EGP,
                    ]
                ]);
                $this->journal_entry_id = $journalEntry->id;
                $this->save();
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Creating Journal Entry failed", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }


    public function setInfo($amount, $type, $note = null)
    {
        assert($this->status == self::PYMT_STATE_NEW, "Payment is not new, can't be updated");
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        try {
            AppLog::info("Setting Profile Payment info", loggable: $this);
            return $this->update([
                "amount"  =>  $amount,
                "type"  =>  $type,
                "note"  =>  $note,
            ]);
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Setting Profile Payment document", loggable: $this);
            $this->update([
                'doc_url'   =>  $doc_url
            ]);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Setting Profile Payment document failed", desc: $e->getMessage(), loggable: $this);
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
            AppLog::info("Deleting Profile Payment document", loggable: $this);
            return true;
        } catch (Exception $e) {
            AppLog::warning("Deleting Profile Payment document failed", desc: $e->getMessage(), loggable: $this);
            report($e);
            return false;
        }
    }

    public function approve()
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('approve', $this)) return false;
        try {
            $this->update([
                "approval_date" =>  Carbon::now()->format('Y-m-d H:i:s'),
                "approver_id"   =>  Auth::id(),
                "status"        =>  self::PYMT_STATE_APPROVED
            ]);
            AppLog::info("Comm Payment approved by " . Auth::user()->username, loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Comm Payment approve failed by " . Auth::user()->username, loggable: $this, desc: $e);
            return false;
        }
    }

    public function setAsPaid(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;


        if ($this->needs_approval && !$this->is_approved) {
            if ($user->can('approve', $this)) {
                $this->approve();
            } else {
                throw new Exception("Payment not approved", 12);
            }
        }

        if (!($this->is_new || $this->is_approved)) return false;

        try {
            DB::transaction(function () use ($date) {

                $this->sales_commissions()->update([
                    // "closed_by_id"   =>  Auth::id(),
                    "payment_date"  => $date->format('Y-m-d H:i'),
                    "status"  =>  SalesComm::PYMT_STATE_PAID,
                ]);


                $date = $date ?? new Carbon();
                AppLog::info("Setting Profile Payment as paid", loggable: $this);
                $this->update([
                    "payment_date"  => $date->format('Y-m-d H:i'),
                    "status"  =>  self::PYMT_STATE_PAID,
                ]);
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
    }

    public function setAsCancelled(Carbon $date = null)
    {
        /** @var User */
        $user = Auth::user();
        if (!$user->can('update', $this)) return false;

        if (!$this->is_new) return false;
        try {
            $date = $date ?? new Carbon();
            DB::transaction(function () use ($date) {
                AppLog::info("Setting Profile Payment as cancelled", loggable: $this);
                $this->sales_commissions()->syncWithPivotValues(
                    $this->sales_commissions->pluck('ids')->toArray(),
                    ['paid_percentage' => 0, 'amount' => 0]
                );
                $this->comm_profile->updateBalance($this->amount);
                $this->update([
                    // "closed_by_id"   =>  Auth::id(),
                    "payment_date"  => $date->format('Y-m-d H:i'),
                    "status"  =>  self::PYMT_STATE_CANCELLED,
                ]);
            });
        } catch (Exception $e) {
            report($e);
            AppLog::error("Setting Profile Payment info failed", desc: $e->getMessage(), loggable: $this);
        }
        return true;
    }

    public function downloadPaymentDetails()
    {
        $comms = $this->sales_commissions()
            ->with(
                'sold_policy',
                'sold_policy.client',
                'sold_policy.policy',
                'sold_policy.policy.company',
            )
            ->get();

        $template = IOFactory::load(resource_path('import/profile_payment_details.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getSheet(0);

        $i = 3;
        foreach ($comms as $c) {
            $activeSheet->getCell('A' . $i)->setValue($c->sold_policy->policy_number);
            $activeSheet->getCell('B' . $i)->setValue($c->sold_policy?->created_at ?
                Carbon::parse($c->sold_policy?->created_at)->format('D d/m/Y') :
                'Not set.');
            $activeSheet->getCell('C' . $i)->setValue($c->sold_policy?->policy?->company?->name . '-' . $c->sold_policy?->policy?->name);
            $activeSheet->getCell('D' . $i)->setValue($c->sold_policy->client->full_name);
            $activeSheet->getCell('E' . $i)->setValue(number_format($c->pivot->amount));
            $activeSheet->getCell('F' . $i)->setValue(number_format($c->comm_percentage, 2));
            $activeSheet->getCell('G' . $i)->setValue(number_format($c->sold_policy->sales_out_comm));
            $activeSheet->getCell('H' . $i)->setValue(number_format($c->sold_policy->insured_value));

            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "commission_payment_details.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
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
    public function getIsApprovedAttribute()
    {
        return $this->status == self::PYMT_STATE_APPROVED;
    }

    ///scopes
    public function scopePaid(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_PAID);
    }
    public function scopeNotCancelled(Builder $query)
    {
        $query->whereNot('status', self::PYMT_STATE_CANCELLED);
    }
    public function scopeNew(Builder $query)
    {
        $query->where('status', self::PYMT_STATE_NEW);
    }

    ///relations
    public function sales_commissions(): BelongsToMany
    {
        return $this->belongsToMany(SalesComm::class, 'comm_payments_details')->withPivot('paid_percentage', 'amount');
    }

    public function comm_profile(): BelongsTo
    {
        return $this->belongsTo(CommProfile::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
