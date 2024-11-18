<?php

namespace App\Http\Controllers;

use App\Models\Payments\ClientPayment;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function clientPaymentsFinance()
    {
        /** @var User */
        $loggedInUser = Auth::user();

        // if($loggedInUser->can('viewAny', ClientPayment::class))

        return view('reports.client-payment-finance');
    }

    public function soldPolicyIndex()
    {
        return view('reports.sold-policy-index');
    }

    public function followupsIndex(){
        return view('reports.followups-index');
    }

    public function offersIndex()
    {
        // if (Auth::user()->is_admin) {
        return view('reports.reports-index');
    }

    public function tasksIndex()
    {
        // if (Auth::user()->is_admin) {
        return view('reports.tasks-index');
    }
}
