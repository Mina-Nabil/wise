<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppLogController extends Controller
{
    public function index()
    {
        return view('users.app-log-index');
    }
}
