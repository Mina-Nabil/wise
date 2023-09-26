<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cars\Car;
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

    public function mount(){

        $this->prices = collect();
    }

    

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => ''],
    ];

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
                    case 'car':
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
            ->paginate(30);

        $brands = Brand::all();


        return view('livewire.car-index', [
            'cars' => $cars,
            'brands' => $brands,
        ]);
    }

    
}
