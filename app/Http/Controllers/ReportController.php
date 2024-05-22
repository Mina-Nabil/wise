<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function soldPolicyIndex()
    {
        if (Auth::user()->is_admin || Auth::user()->id == 12) {
            return view('reports.sold-policy-index');
        }else{
            return (redirect('/'));
        }
    }

    public function offersIndex()
    {
        if (Auth::user()->is_admin || Auth::user()->id == 12) {
            return view('reports.reports-index');
        }else{
            return (redirect('/'));
        }
        
    }
}
