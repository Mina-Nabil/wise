<?php

namespace App\Http\Controllers;

use App\Models\Insurance\Policy;
use App\Models\Offers\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    public function index()
    {
        return view('offers.index');
    }

    public function show($offerId)
    {
        $offer = Offer::find($offerId);
        return view('offers.show', compact('offerId'));
    }
}
