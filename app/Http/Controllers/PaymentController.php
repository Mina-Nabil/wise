<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function clientPaymentsIndex(){
        return view('payments.client-payment-index');
    }
}
