<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cars\Car;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use App\Models\Cars\Country;

class AddNewCar extends Component
{
    public $brandId = null;
    public $selectedCarModel = null;
    public $brands;
    public $carModels;
    public $addNewBrand = false;
    public $addNewModel = false;
    public $country;
    public $brandName;
    public $modelName;
    public $categoryName;

    public function rules()
    {
        return [
            'brandName' => 'required|unique:brands,name',
            'modelName' => 'required|unique:car_models,name',
            'categoryName' => 'required|unique:cars,category',
        ];
    }

    public function updatedBrandName()
    {
        $this->validateOnly('brandName');
    }

    public function updatedModelName()
    {
        $this->validateOnly('modelName');
    }

    public function updatedCategoryName()
    {
        $this->validateOnly('categoryName');
    }



    public function saveCar()
    {
        if ($this->addNewBrand) {

            $validatedData = $this->validate([
                'brandName' => 'required|unique:brands,name',
                'modelName' => 'required|unique:car_models,name',
                'categoryName' => 'required|unique:cars,category',
                'country' => 'required|exists:countries,id',
            ]);

            $newBrand = Brand::newBrand($this->brandName, $this->country);
            $newCarModel  = CarModel::newCarModel($this->modelName, $newBrand->id);
            $c = Car::newCar($newCarModel->id, $this->categoryName, ' ');

            if ($c) {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Added Successfuly',
                    'type' => 'success',
                ]);
            } else {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Server Error',
                    'type' => 'failed',
                ]);
            }
        } elseif ($this->addNewModel) {

            $validatedData = $this->validate([
                'modelName' => 'required|unique:car_models,name',
                'categoryName' => 'required|unique:cars,category',
                'brandId' => 'required|exists:brand,id',
            ]);

            $brandId = $this->brandId;
            $newCarModel = CarModel::newCarModel($this->modelName, $brandId);
            $c = Car::newCar($newCarModel->id, $this->categoryName, ' ');

            if ($c) {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Added Successfuly',
                    'type' => 'success',
                ]);
            } else {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Server Error',
                    'type' => 'failed',
                ]);
            }
        } else {

            $validatedData = $this->validate([
                'categoryName' => 'required|unique:cars,category',
                'selectedCarModel' => 'required|exists:car_models,id',

            ]);

            $modelId = $this->selectedCarModel;
            $c = Car::newCar($modelId, $this->categoryName, ' ');

            if ($c) {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Added Successfuly',
                    'type' => 'success',
                ]);
            } else {
                $this->dispatchBrowserEvent('toastalert', [
                    'message' => 'Server Error',
                    'type' => 'failed',
                ]);
            }
        }
    }


    public function updatingAddNewBrand()
    {
        if ($this->addNewBrand = true) {
            $this->brandId = null;
            $this->selectedCarModel = null;
            $this->resetValidation();
        }
    }

    public function updatingAddNewModel()
    {
        if ($this->addNewModel = true) {
            $this->selectedCarModel = null;
            $this->resetValidation();
        }
    }

    public function mount()
    {
        $this->brands = Brand::all(); // Load all brands
        $this->carModels = collect(); // Initialize carModels as an empty collection
    }

    public function updatingBrandId($value)
    {
        $this->brandId = $value;
        $this->selectedCarModel = null;

        // Load car models based on the selected brand if a brand is selected
        if ($this->brandId) {
            $this->carModels = CarModel::getBrandModels($this->brandId)->get();
        } else {
            $this->carModels = collect(); // Reset carModels when no brand is selected
        }

        // dd($this->brandId);
    }


    public function render()
    {
        $countries = Country::all();
        return view('livewire.add-new-car', [
            'countries' => $countries
        ]);
    }
}
