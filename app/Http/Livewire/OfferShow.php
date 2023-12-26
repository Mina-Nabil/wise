<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;

class OfferShow extends Component
{
    public $offer;
    public $preview;

    public function mount($offerId)
    {
        $this->offer = Offer::find($offerId);
    }

    public function render()
    {
        return view('livewire.offer-show');
    }
}
