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
    public $myOffers = false;
    public $inFavorTo;

    public $isRenewalCB = 'all';

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
    public $filteredStatus = ['active'];

    ///date filters
    public $dateRange;
    public $startDate;
    public $endDate;

    public $relatives = [];

    protected $listeners = ['dataReceived'];
    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];
    
    public function dataReceived($data)
    {
        $this->clientType = ucwords($data['clientTypeRecieved']);
        $this->selectClient($data['clientIdRecieved']);
    }

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
        $this->dispatchBrowserEvent('openNewTab', ['url' => route('offers.show', $id)]);
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
            $this->selectedClientName = $res->first_name . ' ' . $res->middle_name . ' ' . $res->last_name;
        } elseif ($this->clientType == 'Corporate') {
            $res = Corporate::find($id);
            $this->selectedClientName = $res->name;
        }

        $this->owner = $res;
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
            $this->clientNames = Customer::userData($this->searchClient, false)
                ->get()
                ->take(5);
        } elseif ($this->clientType == 'Corporate' && !$this->searchClient == '') {
            $this->clientNames = Corporate::userData($this->searchClient, false)
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

    public function updatedDateRange()
    {
        if (strpos($this->dateRange, 'to') !== false) {
            // The string contains 'to'
            [$this->startDate, $this->endDate] = explode(' to ', $this->dateRange);
            // dd($this->startDate, $this->endDate);
        }
    }


    public function newOffer()
    {
        if(!$this->owner) {
            $this->alert('warning', 'Please select the client');
            return;
        }
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

    public function filterByStatus($status)
    {
        $this->resetPage();
        $this->filteredStatus = [$status];
    }

    //reseting page while searching
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function mount()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dateRange = ($this->startDate && $this->endDate) ? $this->startDate . ' to ' . $this->endDate : "N/A";
    }

    public function render()
    {
        $LINES_OF_BUSINESS = Policy::LINES_OF_BUSINESS;
        $PERSONAL_TYPES = Policy::PERSONAL_TYPES;
        $CORPORATE_TYPES = Policy::CORPORATE_TYPES;
        $GENDERS = Customer::GENDERS;
        $RELATIONS = Relative::RELATIONS;
        $brands = Brand::all();
        $statuses = Offer::STATUSES;

        $offers = Offer::userData($this->search, $this->myOffers)
            ->when($this->isRenewalCB, function ($q, $v) {
                if ($v === 'isRenewal') return $q->byRenewal(1);
                elseif ($v === 'notRenewal') return $q->byRenewal(0);
            })->when($this->filteredStatus, function ($query) {
                return $query->byStates($this->filteredStatus);
            })->when($this->filteredStatus == null, function ($query) {
                return $query->byStates(['active']);
            })->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                return $query->fromTo($startDate, $endDate);
            })
            ->paginate(10);
    
        return view('livewire.offer-index', [
            'offers' => $offers,
            'clientNames' => $this->clientNames,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS,
            'GENDERS' => $GENDERS,
            'RELATIONS' => $RELATIONS,
            'brands' => $brands,
            'PERSONAL_TYPES' => $PERSONAL_TYPES,
            'CORPORATE_TYPES' => $CORPORATE_TYPES,
            'statuses' => $statuses,
            'filteredStatus' => $this->filteredStatus,
        ]);
    }
}
