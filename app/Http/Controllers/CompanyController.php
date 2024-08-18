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

    public function show($company_id)
    {
        $name = Company::find($company_id)->name;
        return view('companies.show', compact('company_id' , 'name'));
    }
}
