<?php

namespace App\Http\Livewire;

use App\Models\Customers\Customer;
use App\Models\Corporates\Corporate;
use App\Models\Cars\Car;
use App\Models\Customers\Car as CustomerCar;
use App\Models\Customers\Relative;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use Livewire\Component;
use App\Models\Offers\Offer;
use App\Models\Cars\CarPrice;
use App\Models\Insurance\Policy;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class OfferIndex extends Component
{
    use WithPagination, AlertFrontEnd;

    public $addOfferSection = false;
    public $owner; //client
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
    public $isRenewal = false;
    public $inFavorTo;

    public $isRenewalCB = 'isRenewal';

    public $bdate;
    public $gender;

    public $carBrand;
    public $models;
    public $carModel;
    public $CarCategory;
    public $cars;
    public $CarPrices;
    public $carPrice;
    public $selectedCarPriceArray;

    public $relatives = [];

    public function removeRelative($index)
    {
        unset($this->relatives[$index]);
        $this->relatives = array_values($this->relatives);
    }

    public function addAnotherField()
    {
        $this->relatives[] = ['name' => '', 'relation' => '', 'gender' => '', 'phone' => '', 'birth_date' => ''];
    }

    public function addNewCar()
    {
        $this->clientCars = null;
        $this->item = null;
    }

    public function redirectToShowPage($id)
    {
        return redirect(route('offers.show', $id));
    }

    public function selectClient($id)
    {
        if ($this->clientType == 'Customer') {
            $res = Customer::find($id);

            $this->bdate = $res->birth_date ? $res->birth_date->toDateString() : null;
            // dd($this->bdate);
            $this->gender = $res->gender;

            $this->clientCars = $res->cars;
            if ($this->clientCars->isEmpty()) {
                $this->clientCars = null;
            }
        } elseif ($this->clientType == 'Corporate') {
            $res = Corporate::find($id);
        }

        $this->owner = $res;
        $this->selectedClientName = $res->first_name . ' ' . $res->middle_name . ' ' . $res->last_name;
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
            $this->clientNames = Customer::userData(searchText: $this->searchClient)
                ->get()
                ->take(5);
        } elseif ($this->clientType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::where('name', 'like', '%' . $this->searchClient . '%')
                ->get()
                ->take(5);
        }

        // dd($this->clientNames);
    }

    public function openAddOfferSection()
    {
        $this->initiateOfferSection();
        $this->addOfferSection = true;
    }

    public function closeAddOfferSection()
    {
        $this->addOfferSection = false;
    }

    public function initiateOfferSection()
    {
        $this->item = null;
        $this->type = Policy::BUSINESS_PERSONAL_MOTOR;
        $this->owner = null;
    }

    public function updatedCarBrand($value)
    {
        $this->models = CarModel::where('brand_id', $value)->get();
        if ($value === '') {
            $this->carModel = null;
            $this->CarCategory = null;
        }
        $this->CarCategory = null;
    }

    public function updatedCarModel($value)
    {
        $this->cars = Car::where('car_model_id', $value)->get();
        $this->CarCategory = null;
    }

    public function updatedCarCategory()
    {
        if ($this->CarCategory) {
            $this->CarPrices = CarPrice::where('car_id', $this->CarCategory)->get();
        }
    }
    public function updatedCarPrice()
    {
        if ($this->carPrice) {
            $this->selectedCarPriceArray = (array) json_decode($this->carPrice);
            $this->item_value = $this->selectedCarPriceArray['price'];
        }
    }

    public function updatedItem()
    {
        if ($this->item) {
            $this->CarPrices = CarPrice::where('car_id', $this->item)->get();
        }
    }

    public function newOffer()
    {
        // dd($this->CarCategory);
        $this->validate([
            'type' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            'item_value' => 'nullable|numeric',
            'item_title' => 'nullable|string|max:255',
            'item_desc' => 'nullable|string',
            'note' => 'nullable|string',
            'isRenewal' => 'boolean',
            'inFavorTo' => 'nullable|string|max:255',
        ]);

        $dueDate = $this->dueDate ? Carbon::parse($this->dueDate) : Carbon::tomorrow();
        $dueTime = $this->dueTime ? Carbon::parse($this->dueTime) : null;
        $combinedDateTime = Carbon::parse($dueTime ? $dueDate->setTime($dueTime->hour, $dueTime->minute, $dueTime->second) : $dueDate);

        // dd($this->item);
        if ($this->type === 'personal_medical' && $this->clientType === 'Customer') {
            if (!empty($this->relatives)) {
                $this->validate([
                    'relatives.*.name' => 'required|string|max:255',
                    'relatives.*.relation' => 'required|in:' . implode(',', Relative::RELATIONS),
                    'relatives.*.gender' => 'nullable|in:' . implode(',', Customer::GENDERS),
                    'relatives.*.phone' => 'nullable|string|max:255',
                    'relatives.*.birth_date' => 'nullable|date',
                ]);
                $this->owner->setRelatives($this->relatives);
            }
            $this->owner->editCustomer(first_name: $this->owner->first_name, last_name: $this->owner->last_name, birth_date: $this->bdate, gender: $this->gender);
            $item = null;
        } elseif ($this->type === 'personal_motor' && $this->clientType === 'Customer' && is_Null($this->item)) {
            if (!$this->CarCategory || !$this->selectedCarPriceArray) {
                return $this->alert('failed', 'Please select a car');
            }
            $item = $this->owner->addCar(car_id: $this->CarCategory, model_year: $this->selectedCarPriceArray['model_year']);
            $this->item_title = null;
        } elseif ($this->type === 'personal_motor' && $this->clientType === 'Customer') {
            $this->item_title = null;
            $item = CustomerCar::find($this->item);
        } else {
            $item = null;
        }

        $res = Offer::newOffer($this->owner, $this->type, $this->item_value, $this->item_title, $this->item_desc, $this->note, $combinedDateTime, $item, $this->isRenewal, $this->inFavorTo);
        if ($res) {
            return redirect(route('offers.show', $res->id));
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function render()
    {
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $PERSONAL_TYPES = Policy::PERSONAL_TYPES;
        $CORPORATE_TYPES = Policy::CORPORATE_TYPES;
        $GENDERS = Customer::GENDERS;
        $RELATIONS = Relative::RELATIONS;
        $brands = Brand::all();

        if($this->isRenewalCB === 'isRenewal'){
            $offers = Offer::userData($this->search)->isRenewal()->paginate(10);
        }elseif(($this->isRenewalCB === 'notRenewal')){
            $offers = Offer::userData($this->search)->notRenewal()->paginate(10);
        }
        
        return view('livewire.offer-index', [
            'offers' => $offers,
            'clientNames' => $this->clientNames,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'GENDERS' => $GENDERS,
            'RELATIONS' => $RELATIONS,
            'brands' => $brands,
            'PERSONAL_TYPES' => $PERSONAL_TYPES,
            'CORPORATE_TYPES' => $CORPORATE_TYPES,
        ]);
    }
}
