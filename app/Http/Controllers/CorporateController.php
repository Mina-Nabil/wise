<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CorporateController extends Controller
{
    public function index(){
        return view('corporates.index');
    }

    public function show($corporateId)
    {
        return view('corporates.show', compact('corporateId'));
    }
}
