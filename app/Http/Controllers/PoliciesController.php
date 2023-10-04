<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance\Policy;

class PoliciesController extends Controller
{
    public function index()
    {
        // Pass the policies to a view for rendering
        return view('policies.index');
    }
}
