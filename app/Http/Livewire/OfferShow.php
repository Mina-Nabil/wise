<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Cars\Car;
use App\Models\Customers\Car as CustomerCar;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class OfferShow extends Component
{
    use AlertFrontEnd, ToggleSectionLivewire;

    public $offer;
    public $preview;
    public $clientCars;

    public $editItemSection = false;
    public $item_value;
    public $itemId;
    public $item_title;
    public $item_desc;
    public $carId;

    public $newComment;

    public function toggleEditItem(){
        $this->toggle($this->editItemSection);
    }

    public function editItem(){
        $this->validate([
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
        ]);

        $item = Car::find($this->carId);

        $res = $this->offer->setItemDetails(
            $this->item_value,
            $item,
            $this->item_title,
            $this->item_desc
        );

        if($res){
            $this->alert('success' , 'Item updated');
            $this->toggleEditItem();
        }else{
            $this->alert('failed' , 'server error');
        }
    }

    public function addComment(){
        $this->validate([
            'newComment' => 'required|string|max:255'
        ]);
        $res = $this->offer->addComment($this->newComment);
        if($res){
            $this->alert('success' , $res);
            $this->mount($this->offer->id);
            $this->newComment = null;
        }else{
            $this->alert('failed' , 'server error');
        }
    }

    public function mount($offerId)
    {
        $this->offer = Offer::find($offerId);
        $this->item_value = $this->offer->item_value;
            $this->item_title = $this->offer->item_title;
            $this->item_desc  = $this->offer->item_desc;
    }

    public function setStatus($s){
        $res = $this->offer->setStatus($s);
        if($res){
            $this->alert('success' , $res);
        }else{
            $this->alert('failed' , 'server error');
        }

    }

    public function render()
    {
        $STATUSES = Offer::STATUSES;
        return view('livewire.offer-show',[
            'STATUSES' => $STATUSES
        ]);
    }
}
