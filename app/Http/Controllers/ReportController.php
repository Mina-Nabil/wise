<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function soldPolicyIndex()
    {
            return view('reports.sold-policy-index');
    }

    public function offersIndex()
    {
        // if (Auth::user()->is_admin) {
            return view('reports.reports-index');
        
        
    }
}
