<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Insurance\Policy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use Illuminate\Routing\Route;

class OfferIndex extends Component
{
    use WithPagination,AlertFrontEnd;

    public $addOfferSection = false;
    public $owner;
    public $type;
    public $item_value;
    public $item_title;
    public $item_desc;
    public $note;
    public $due;
    public $item;



    public function openAddOfferSection(){
        $this->addOfferSection = true;
    }

    public function closeAddOfferSection(){
        $this->addOfferSection = false;
    }

    public function newOffer(){
        $this->validate([
            'type' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            'item_value' => 'nullable|double',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
            'note' => 'nullable|string',
            'due' => 'nullable|date'
        ]);

        $offer = new Offer();
        $res = $offer->newOffer(
            $this->owner,
            $this->type,
            $this->item_value,
            $this->item_title,
            $this->item_desc,
            $this->note,
            $this->due,
            $this->item
        );
        if($res){
            return redirect(route('customers.show',  $res->id));
        }else{
            $this->alert('failed' , 'Server error');
        }
    }

    public function render()
    {
        $offers = Offer::paginate(10);
        return view('livewire.offer-index',[
            'offers' => $offers,
        ]);
    }
}
