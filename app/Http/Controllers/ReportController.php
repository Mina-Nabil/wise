<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function soldPolicyIndex()
    {
        return view('reports.sold-policy-index');
    }

    public function offersIndex()
    {
        return view('reports.reports-index');
    }
}
