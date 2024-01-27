<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppLogController extends Controller
{
    public function index()
    {
        return view('users.app-log-index');
    }

    public function slaRecordsIndex()
    {
        return view('users.sla-record-index');
    }
}
