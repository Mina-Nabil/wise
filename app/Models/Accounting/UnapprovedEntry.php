<?php

namespace App\Models\Accounting;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnapprovedEntry extends Model
{
    use HasFactory;
    protected $table = 'journal_entries';
    protected $fillable = [
        'user_id',
        'entry_title_id',
        'credit_id',
        'debit_id',
        'credit_doc_url',
        'debit_doc_url',
        'currency',
        'currency_amount',
        'currency_rate',
        'entry_title_id',
        'comment',
        'receiver_name',
        'cash_type',
    ];

    ////static functions
    public static function newEntry(
        $entry_title_id,
        $credit_id,
        $debit_id,
        $amount,
        $credit_doc_url = null,
        $debit_doc_url = null,
        $currency = null,
        $currency_amount = null,
        $currency_rate = null,
        $comment = null,
        $receiver_name = null,
        $cash_type = null,
    ) {
        try {
            $newEntry = new self([
                'user_id'           => Auth::id(),
                'entry_title_id'    => $entry_title_id,
                'amount'            => $amount,
                'credit_id'     => $credit_id,
                'debit_id'      => $debit_id,
                'credit_doc_url'    => $credit_doc_url,
                'debit_doc_url'     => $debit_doc_url,
                'currency'      => $currency,
                'currency_amount'   => $currency_amount,
                'currency_rate'     => $currency_rate,
                'entry_title_id'    => $entry_title_id,
                'comment'       => $comment,
                'receiver_name' => $receiver_name,
                'cash_type'     => $cash_type,
            ]);
            return $newEntry;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }


    ///model functions
    public function approveRecord()
    {
        /** @var Account */
        $creditAccount = Account::findOrFail($this->credit_id);
        /** @var User */
        $loggedInUser = Auth::user();
        if ($creditAccount->needsApproval($this->amount) && !$loggedInUser->can('approve', JournalEntry::class)) return false;
        $newEntry = null;
        DB::transaction(function () use ($creditAccount, &$newEntry) {
            $newEntry = JournalEntry::newJournalEntry(
                title: EntryTitle::findOrFail($this->entry_title_id)->name,
                amount: $this->amount,
                credit_id: $this->credit_id,
                debit_id: $this->debit_id,
                currency: $this->currency,
                currency_amount: $this->currency_amount,
                currency_rate: $this->currency_rate,
                credit_doc_url: $this->credit_doc_url,
                debit_doc_url: $this->debit_doc_url,
                comment: $this->comment,
                receiver_name: $this->receiver_name,
                cash_entry_type: $this->cash_entry_type,
                approver_id: $creditAccount->needsApproval($this->amount) ? Auth::id() : null,
                approved_at: $creditAccount->needsApproval($this->amount) ? Carbon::now() : null,
                user_id: $creditAccount->needsApproval($this->amount) ? $this->user_id : Auth::id()
            );
            if ($newEntry) {
                $this->delete();
            }
        });
        return $newEntry;
    }

    public function editRecord(
        $entry_title_id,
        $amount,
        $credit_id,
        $debit_id,
        $credit_doc_url = null,
        $debit_doc_url = null,
        $currency = null,
        $currency_amount = null,
        $currency_rate = null,
        $comment = null,
        $receiver_name = null,
        $cash_type = null,
    ) {
        try {
            $this->update([
                'user_id'           => Auth::id(),
                'entry_title_id'    => $entry_title_id,
                'amount'            => $amount,
                'credit_id'     => $credit_id,
                'debit_id'      => $debit_id,
                'credit_doc_url'    => $credit_doc_url,
                'debit_doc_url'     => $debit_doc_url,
                'currency'      => $currency,
                'currency_amount'   => $currency_amount,
                'currency_rate'     => $currency_rate,
                'entry_title_id'    => $entry_title_id,
                'comment'       => $comment,
                'receiver_name' => $receiver_name,
                'cash_type'     => $cash_type,
            ]);
            /** @var Account */
            $creditAccount = Account::findOrFail($credit_id);
            if (!$creditAccount->needsApproval($amount)) {
                $this->approveRecord();
            }
            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function deleteRecord()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('approve', JournalEntry::class)) return false;

        $this->delete();
        return true;
    }
}