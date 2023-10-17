<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.index');
    }
}
