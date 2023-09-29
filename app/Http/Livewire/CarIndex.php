<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cars\Car;
use App\Models\Cars\CarPrice;
use App\Models\Cars\Brand;
use App\Models\Cars\CarModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CarController;

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

    public function openModel($id){
        $this->modelId = $id;
    }
    public function closeModel(){
        $this->modelId = null;
        $this->deleteThisModel = false;
    }
    
    public function declineDeleteModel()
    {
        $this->deleteThisModel = false;
    }

    public function openBrand($id){
        $this->brandId = $id;
    }
    public function closeBrand(){
        $this->brandId = null;
    }

    public function updateThisPrice($id){
        $this->showPrices($this->carPriceListId);
        $this->updateThisPriceId = $id;
        
    }

    public function declineUpdatePrice(){
        $this->updateThisPriceId = null;
    }
    public function updatePrice(){

    }


    public function deletethisPrice($id)
    {
        $this->deleteThisPriceId = $id;
    }

    public function declineDeletePrice(){
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

    public function editCar()
    {
        $this->editCarField = true;
    }

    public function mount()
    {
        $this->prices = collect();
    }

    public function submit()
    {
        $this->validate();

        CarPrice::create([
            'car_id' => $this->carPriceListId,
            'price' => $this->newPrice,
            'model_year' => $this->newPriceYear,
            'desc' => 'Added From livewire',
        ]);
        session()->flash('price_success', 'Price Added successfully.');

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

    public function confirmDeleteBand(){

        $this->deleteThisBrand = true;
    }

    public function confirmDeleteModel(){

        $this->deleteThisModel = true;
    }

    public function declineDeleteBand()
    {
        $this->deleteThisBrand = false;
    }

    public function deleteModel(){
        // dd($this->deleteThisBrand);
        CarModel::deleteModel($this->modelId);
        session()->flash('cars_success', 'Model Deleted successfully.');
        return redirect('/cars');
    }

    public function deleteBrand(){
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
            $carsCount = $brand->models->flatMap(function ($model) {
                return $model->cars;
            })->count();
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
