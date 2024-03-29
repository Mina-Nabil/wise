<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    use HasFactory;

    CONST FILES_DIRECTORY = 'sold_policies/client_pymt_docs/';

    CONST PYMT_TYPE_CASH = 'cash';
    CONST PYMT_TYPE_CHEQUE = 'cheque';
    CONST PYMT_TYPE_BANK_TRNSFR = 'bank_transfer';
    CONST PYMT_TYPE_VISA = 'visa';

    const PYMT_TYPES = [
        self::PYMT_TYPE_CASH,
        self::PYMT_TYPE_CHEQUE,
        self::PYMT_TYPE_BANK_TRNSFR,
        self::PYMT_TYPE_VISA,
    ];

    CONST PYMT_STATE_NEW = 'new';
    CONST PYMT_STATE_PAID = 'paid';
    CONST PYMT_STATE_CANCELLED = 'cancelled';
    const PYMT_STATES = [
        self::PYMT_STATE_NEW,
        self::PYMT_STATE_PAID,
        self::PYMT_STATE_CANCELLED,
    ];

}
