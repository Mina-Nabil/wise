<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cars\Car;
use App\Models\Cars\CarPrice;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use Livewire\WithPagination;

class CarIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $carPriceListId = null;
    public $paginationReset = false;
    public $sortBy = null;
    public $sortDirection;
    public $prices = null;
    public $newPriceYear = null;
    public $newPrice = null;
    public $editCarField;
    public $editCarName;
    public $deleteThisCar = false;
    public $deleteThisPriceId;
    public $updateThisPriceId;
    public $brandId;
    public $deleteThisBrand;
    public $modelId;
    public $deleteThisModel;
    public $updatedPrice;
    public $updatedYear = null;
    public $editBrandField;
    public $editBrandName;
    public $editModelField;
    public $editModelName;

    public function mount()
    {
        $this->prices = collect();
    }

    public function openModel($id)
    {
        $this->modelId = $id;
    }
    public function closeModel()
    {
        $this->modelId = null;
        $this->deleteThisModel = false;
        $this->editModelField = false;
    }

    public function closeUpdatePrice()
    {
        $this->updateThisPriceId = null;
        $this->showPrices($this->carPriceListId);
    }

    public function declineDeleteModel()
    {
        $this->deleteThisModel = false;
    }

    public function openBrand($id)
    {
        $this->brandId = $id;
    }
    public function closeBrand()
    {
        $this->brandId = null;
        $this->editBrandField = false;
        $this->resetValidation();
    }

    public function updateThisPrice($id)
    {
        $this->showPrices($this->carPriceListId);
        $this->updateThisPriceId = $id;
        $price = CarPrice::find($this->updateThisPriceId);
        $this->updatedPrice = $price->price;
        $this->updatedYear = $price->model_year;
        if (!$price) {
            $this->alert('failed', 'Failed price not found!');
        }
    }

    public function declineUpdatePrice()
    {
        $this->updateThisPriceId = null;
    }

    public function updatePrice($id)
    {
        $this->validate(
            [
                'updatedYear' => ['regex:/^\d{4}$/', 'unique:car_prices,model_year'],
                'updatedPrice' => 'required|numeric'
            ],
            [],
            [
                'updatedYear' => 'Year',
                'updatedPrice' => 'Price',
            ],
        );


        $record = CarPrice::find($id);
        $p = $record->update([
            'car_id' => $this->carPriceListId,
            'model_year' => $this->updatedYear,
            'price' => $this->updatedPrice,
            'desc' => 'Updated From Livewire',
        ]);

        if ($p) {
            $this->alert('success', 'Price Updated!');
            $this->closeUpdatePrice();
        } else {
            $this->alert('failed', 'Failed Updating Price!');
        }
    }

    public function deletethisPrice($id)
    {
        $this->deleteThisPriceId = $id;
    }

    public function declineDeletePrice()
    {
        $this->deleteThisPriceId = null;
    }

    public function deletePrice()
    {
        $price = CarPrice::findOrFail($this->deleteThisPriceId);
        $p = $price->delete();

        if ($p) {
            $this->alert('success', 'Price Deleted!');
            $this->showPrices($this->carPriceListId);
        } else {
            $this->alert('failed', 'Failed deleting Price!');
        }
    }

    public function confirmDeleteThisCar()
    {
        $this->deleteThisCar = true;
    }
    public function declineDeleteThisCar()
    {
        $this->deleteThisCar = false;
    }

    public function deleteCar()
    {
        /** @var Car */
        $car = Car::findOrFail($this->carPriceListId);

        $res = $car->delete();

        if ($res) {
            $this->alert('success', 'Car Deleted!');
            $this->carPriceListId = null;
            $this->deleteThisCar = false;
        } else {
            $this->alert('failed', 'Failed deleting Car');
        }
    }

    public function saveCarName()
    {
        $this->validate([
            'editCarName' => 'required|string|unique:cars,category'
        ]);
        /** @var Car */
        $car = Car::find($this->carPriceListId);

        if ($car) {
            $car->editInfo($car->car_model->id, $this->editCarName, 'Edited');
            $c = $car->save();

            if ($c) {
                $this->alert('success', 'Updated Successfuly');
                $this->editCarField = false;
                $this->showPrices($this->carPriceListId);
            } else {
                $this->alert('failed', 'Server Error');
            }
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    protected $rules = [
        'carPriceListId' => 'required',
        'newPrice' => 'required',
        'newPriceYear' => 'required',
    ];

    // to open the field of Edit model name
    public function editModel()
    {
        /** @var CarModel */
        $model = CarModel::find($this->modelId);

        if (!$model) {
            $this->alert('failed', 'Server Error');
        } else {
            $this->editModelField = true;
            $this->editModelName = $model->name;
        }
    }

    // to save the edited model name
    public function saveModelName()
    {
        /** @var CarModel */

        $this->validate(
            [
                'editModelName' => 'required|string|unique:car_models,name',
            ],
            [],
            [
                'editModelName' => 'Model Name',
            ],
        );

        $model = CarModel::find($this->modelId);

        if (!$model) {
            $this->alert('failed', 'Server Error');
        } else {
            $model->editInfo($this->editModelName, $model->brand->id);
            $model->save();

            $this->editModelField = false;
            $this->openModel($this->modelId);

            $this->alert('success', 'Updated Successfuly!');
        }
    }

    // to open the field of Edit brand name
    public function editBrand()
    {
        /** @var Car */
        $brand = Brand::find($this->brandId);

        if (!$brand) {
            $this->alert('failed', 'Server Error');
        } else {
            $this->editBrandField = true;
            $this->editBrandName = $brand->name;
        }
    }

    // to save the edited brand name
    public function saveBrandName()
    {
        /** @var Brand */

        $this->validate(
            [
                'editBrandName' => 'required|string|unique:brands,name',
            ],
            [],
            [
                'editBrandName' => 'Brand Name',
            ],
        );
        $brand = Brand::find($this->brandId);

        if ($brand) {
            $brand->editInfo($this->editBrandName, $brand->country->id);
            $brand->save();

            $this->editBrandField = false;
            $this->alert('success', 'Updated Successfuly!');
            $this->openBrand($this->brandId);
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function editCar()
    {
        /** @var Car */
        $car = Car::find($this->carPriceListId);
        if ($car) {
            $this->editCarField = true;
            $this->editCarName = $car->category;
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function submit()
    {
        $this->validate(
            [
                'newPriceYear' => ['regex:/^\d{4}$/'],
                'newPrice' => 'required|numeric'
            ],
            [
                'newPriceYear.regex' => 'The :attribute must be a valid 4-digit year.',
                'newPrice.numeric' => 'The :attribute must be a numeric value.',
            ],
            [
                'newPriceYear' => 'Year',
                'newPrice' => 'Price',
            ],
        );

        /** @var Car */
        $car = Car::findOrFail($this->carPriceListId);

        if (!$car) {
            $this->alert('failed', 'Server Error');
        } else {
            $c = $car->addPrice($this->newPriceYear, $this->newPrice, 'Added from dashboard');

            if ($c) {
                $this->alert('success', 'Price Added!');
                $this->newPriceYear = null;
                $this->newPrice = null;
                $this->showPrices($this->carPriceListId);
            } else {
                $this->alert('failed', 'Server Error');
            }
        }
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => ''],
    ];

    public function closePrices()
    {
        $this->carPriceListId = null;
        $this->editCarField = null;
        $this->deleteThisPriceId = null;
        $this->deleteThisCar = false;
        $this->resetValidation();
    }

    public function confirmDeleteBand()
    {
        $this->deleteThisBrand = true;
    }

    public function confirmDeleteModel()
    {
        $this->deleteThisModel = true;
    }

    public function declineDeleteBand()
    {
        $this->deleteThisBrand = false;
    }

    public function deleteModel()
    {
        $c = CarModel::deleteModel($this->modelId);

        if ($c) {
            $this->alert('success', 'Model Deleted!');
            return redirect('/cars');
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function deleteBrand()
    {
        // dd($this->deleteThisBrand);
        $b = Brand::deleteBrand($this->brandId);

        if ($b) {
            $this->alert('success', 'Brand Deleted!');
            return redirect('/cars');
        } else {
            $this->alert('failed', 'Server Error');
        }
    }

    public function sortByColumn($column)
    {
        // Toggle the sorting direction if the same column is clicked again
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc'; // Reset sorting direction when a new column is selected
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showPrices($carId)
    {
        $this->carPriceListId = $carId;
        $p = Car::withPrices()
            ->with('car_model')
            ->find($this->carPriceListId);

        if (!$p) {
            $this->alert('failed', 'Server Error');
        } else {
            $this->prices = $p;
        }
    }

    public function render()
    {
        $cars = Car::tableData()
            ->searchBy($this->search)
            ->when($this->sortBy, function ($q) {
                switch ($this->sortBy) {
                    case 'category':
                        $q->sortByCar($this->sortDirection);
                        break;
                    case 'model':
                        $q->sortByModel($this->sortDirection);
                        break;
                    case 'brand':
                        $q->sortByBrand($this->sortDirection);
                        break;
                        // Add more cases for other columns if needed
                }
            })
            ->whereNull('cars.deleted_at')
            ->whereNull('brands.deleted_at')
            ->whereNull('car_models.deleted_at')
            ->paginate(30);

        $brand = null;
        $modelCount = null;
        $carsCount = null;
        $model = null;
        $modelCarsCount = null;

        if ($this->brandId) {
            // If $this->brandId is not empty, attempt to find the Brand model
            $brand = Brand::findOrFail($this->brandId);
            $modelCount = $brand->models->count();
            $carsCount = $brand->models
                ->flatMap(function ($model) {
                    return $model->cars;
                })
                ->count();
        }

        if ($this->modelId) {
            // If $this->brandId is not empty, attempt to find the Brand model
            $model = CarModel::findOrFail($this->modelId);
            $modelCarsCount = $model->cars->count();
        }

        $brands = Brand::all();

        return view('livewire.car-index', [
            'cars' => $cars,
            'brands' => $brands,
            'brand' => $brand,
            'modelCount' => $modelCount,
            'carsCount' => $carsCount,
            'model' => $model,
            'modelCarsCount' => $modelCarsCount,
        ]);
    }
}
