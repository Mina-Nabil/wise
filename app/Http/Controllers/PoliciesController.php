<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance\Policy;
use App\Models\Insurance\Company;

class PoliciesController extends Controller
{
    public function index()
    {
        // Pass the policies to a view for rendering
        return view('policies.index');
    }

    public function create()
    {
        $linesOfBusiness = Policy::LINES_OF_BUSINESS;
        $companies = Company::all();
        // Pass the policies to a view for rendering
        return view('policies.create_policy', compact('linesOfBusiness', 'companies'));
    }

    

    public function show($policyId)
    {
        Policy::findOrFail($policyId);
        return view('policies.policy_show', compact('policyId'));
    }

    public function soldPolicyShow($id)
    {
        return view('policies.sold-policy_show', compact('id'));
    }

    public function soldPolicyIndex(){
        return view('policies.sold-policy-index');
    }

    public function expSoldPolicyIndex() {
        return view('policies.exp-sold-policy-index');
    }

    public function outstandingSoldPolicyIndex() {
        return view('policies.outstanding-sold-policy-index');
    }
}
