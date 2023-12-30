<?php

namespace App\Http\Livewire;

use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
use App\Models\Cars\Car;
use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Insurance\Policy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Routing\Route;

class OfferIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $addOfferSection = false;
    public $owner;
    public $clientType = 'Customer';
    public $type;
    public $item_value;
    public $item_title;
    public $item_desc;
    public $note;
    public $due;
    public $item;
    public $search;
    public $searchClient;
    public $clientNames;
    public $selectedClientId;
    public $selectedClientName;
    public $clientCars;
    public $dueDate;
    public $dueTime;

    public function redirectToShowPage($id)
    {
        return redirect(route('offers.show', $id));
    }

    public function selectClient($id)
    {
        $this->item = $id;

        if ($this->clientType == 'Customer') {
            $res = Customer::find($this->item);
        } elseif ($this->clientType == 'Corporate') {
            $res = Corporate::find($this->item);
        }

        $this->clientCars = $res->cars;
        $this->owner = $res;
        $this->selectedClientName = $res->name;
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedClientType()
    {
        $this->clientNames = null;
        $this->searchClient = null;
    }

    public function updatedSearchClient()
    {

        if ($this->clientType == 'Customer' && !$this->searchClient == '') {
            $this->clientNames = Customer::where('name', 'like', '%' . $this->searchClient . '%')->get()->take(5);
        } elseif ($this->clientType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::where('name', 'like', '%' . $this->searchClient . '%')->get()->take(5);
        }

        // dd($this->clientNames);
    }

    public function openAddOfferSection()
    {
        $this->addOfferSection = true;
    }

    public function closeAddOfferSection()
    {
        $this->addOfferSection = false;
    }

    public function newOffer()
    {
        $this->validate([
            'type' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
            'note' => 'nullable|string',
            'due' => 'nullable|date'
        ]);

        $dueDate = $this->dueDate ? Carbon::parse($this->dueDate) : null;
        $dueTime = $this->dueTime ? Carbon::parse($this->dueTime) : null;
        $combinedDateTime = $dueTime ? $dueDate->setTime($dueTime->hour, $dueTime->minute, $dueTime->second) : $dueDate;
        $item = Car::find($this->item);

        $offer = new Offer();
        $res = $offer->newOffer(
            $this->owner,
            $this->type,
            $this->item_value,
            $this->item_title,
            $this->item_desc,
            $this->note,
            $combinedDateTime,
            $item
        );
        if ($res) {
            return redirect(route('customers.show',  $res->id));
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function render()
    {
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $offers = Offer::userData($this->search)->paginate(10);
        return view('livewire.offer-index', [
            'offers' => $offers,
            'clientNames' => $this->clientNames,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS
        ]);
    }
}
