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
    }

    public function updateThisPrice($id)
    {
        $this->showPrices($this->carPriceListId);
        $this->updateThisPriceId = $id;
        $price = CarPrice::find($this->updateThisPriceId);
        $this->updatedPrice = $price->price;
        $this->updatedYear = $price->model_year;
    }

    public function declineUpdatePrice()
    {
        $this->updateThisPriceId = null;
    }

    public function updatePrice($id)
    {
        $this->validate([
            'updatedYear'  => 'required',
            'updatedPrice' => 'required',
        ]);

        $record = CarPrice::find($id);
        $record->update([
            'car_id' => $this->carPriceListId,
            'model_year' => $this->updatedYear,
            'price' => $this->updatedPrice,
            'desc' => 'Updated From Livewire',
        ]);
        $this->closeUpdatePrice();
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
        try {
            $price = CarPrice::findOrFail($this->deleteThisPriceId);
            $price->delete();

            session()->flash('price_success', 'Price deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('price_failed', 'Failed delete price.');
        }
        $this->showPrices($this->carPriceListId);
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
        $this->carPriceListId = null;
        $this->deleteThisCar = false;
    }

    public function saveCarName()
    {
        /** @var Car */
        $car = Car::find($this->carPriceListId);

        if ($car) {
            $car->editInfo($car->car_model->id, $this->editCarName, 'Edited');
            $car->save();

            $this->editCarField = false;
            session()->flash('price_success', 'Car Updated successfully.');
            $this->showPrices($this->carPriceListId);
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
        $this->editModelField = true;
        $this->editModelName = $model->name;
    }

    // to save the edited model name
    public function saveModelName()
    {
        /** @var CarModel */
        $this->validate([
            'editModelName' => 'required',
        ]);
        $model = CarModel::find($this->modelId);

        if ($model) {
            $model->editInfo($this->editModelName, $model->brand->id);
            $model->save();

            $this->editModelField = false;
            session()->flash('model_success', 'Brand Updated successfully.');
            $this->openModel($this->modelId);
        }
    }

    // to open the field of Edit brand name
    public function editBrand()
    {
        /** @var Car */
        $brand = Brand::find($this->brandId);
        $this->editBrandField = true;
        $this->editBrandName = $brand->name;
    }

    // to save the edited brand name
    public function saveBrandName()
    {
        /** @var Brand */
        $this->validate([
            'editBrandName' => 'required',
        ]);
        $brand = Brand::find($this->brandId);

        if ($brand) {
            $brand->editInfo($this->editBrandName, $brand->country->id);
            $brand->save();

            $this->editBrandField = false;
            session()->flash('brand_success', 'Brand Updated successfully.');
            $this->openBrand($this->brandId);
        }
    }

    public function editCar()
    {
        /** @var Car */
        $car = Car::find($this->carPriceListId);
        $this->editCarField = true;
        $this->editCarName = $car->category;
    }

    public function submit()
    {
        $this->validate();

        /** @var Car */
        $car = Car::findOrFail($this->carPriceListId);
        $car->addPrice($this->newPriceYear, $this->newPrice, 'Added from dashboard');
        session()->flash('price_success', 'Price Added successfully.');
        $this->newPriceYear = null;
        $this->newPrice = null;
        $this->showPrices($this->carPriceListId);
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
        // dd($this->deleteThisBrand);
        CarModel::deleteModel($this->modelId);
        session()->flash('cars_success', 'Model Deleted successfully.');
        return redirect('/cars');
    }

    public function deleteBrand()
    {
        // dd($this->deleteThisBrand);
        Brand::deleteBrand($this->brandId);
        session()->flash('cars_success', 'Brand Deleted successfully.');
        return redirect('/cars');
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
        $this->prices = Car::withPrices()
            ->with('car_model')
            ->find($this->carPriceListId);
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
