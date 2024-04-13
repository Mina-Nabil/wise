<?php

namespace App\Http\Controllers;

use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use App\Models\Payments\CommProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    public function index()
    {
        return view('offers.index');
    }

    public function commissionsIndex()
    {
        return view('offers.comm-prof-index');
    }

    public function commissionsShow($id)
    {
        CommProfile::findorFail($id);
        return view('offers.comm-prof-show', compact('id'));
    }

    public function show($offerId)
    {
        $offer = Offer::find($offerId);
        return view('offers.show', compact('offerId'));
    }
}
