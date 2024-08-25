<?php

namespace App\Models\Accounting;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    public function approveRecord() {}

    public function editRecord() {}

    public function cancelRecord() {}

    public function deleteRecord() {}
}
