<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers\Customer;

class CustomerController extends Controller
{
    public function index(){
        return view('customers.index');
    }

    public function show($customerId)
    {
        return view('customers.show', compact('customerId'));
    }

    public function followupsIndex(){
        return view('customers.followups');
    }


}
