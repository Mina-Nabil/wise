<?php

namespace App\Http\Controllers;

use App\Models\Payments\ClientPayment;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function clientPayments()
    {
        /** @var User */
        $loggedInUser = Auth::user();

        // if($loggedInUser->can('viewAny', ClientPayment::class))

        return view('reports.client-payments');
    }

    public function companyCommPayments()
    {
        /** @var User */
        $loggedInUser = Auth::user();

        // Add authorization check if needed
        // if($loggedInUser->can('viewAny', CompanyCommPayment::class))

        return view('reports.company-comm-payments');
    }

    public function clientInterests()
    {
        return view('reports.client-interests');
    }

    public function corporateInterests()
    {
        return view('reports.corporate-interests');
    }

    public function invoicesReport()
    {
        return view('reports.invoices-report');
    }

    public function salesCommissions()
    {
        return view('reports.sales-commissions-report');
    }

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

    public function followupsIndex()
    {
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

    public function campaignsIndex()
    {
        return view('reports.campaigns-index');
    }

    public function reviewsIndex()
    {
        return view('reports.reviews-index');
    }
}
