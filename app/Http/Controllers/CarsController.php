<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cars\SetBrandRequest;
use App\Http\Requests\Cars\SetCarRequest;
use App\Http\Requests\Cars\SetPriceRequest;
use App\Models\Cars\Brand;
use App\Models\Cars\Car;
use App\Models\Cars\CarModel;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    ///Main Cars Page
    public function index()
    {
        $cars = Car::with('car_model', 'car_model.brand')->get();
        return view('cars.index', ['cars'   =>  $cars]);
    }

    public function importCars(Request $request)
    {
        $request->validate([
            'cars_file'     =>  "required|file|mimetypes:application/vnd.ms-excel,text/csv"
        ]);
        Car::importData($request->cars_file);

    }
    ////Prices Functions
    public function setCarPrices(SetPriceRequest $request)
    {
        $data = $request->validated();
        /** @var Car */
        $car = Car::findOrFail($data['car_id']);
        $car->setPrices($data);
    }

    ////Cars Functions
    public function setCar(SetCarRequest $request)
    {
        $data = $request->validated();
        if (isset($data['id']) && is_numeric($data['id'])) {
            /** @var Car */
            $car = Car::findOrFail($data['id']);
            $res = $car->editInfo($data['car_model_id'], $data['category'], $data['desc']);
        } else {
            $res = Car::newCar($data['car_model_id'], $data['category'], $data['desc']);
        }

        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Car data saved successfully" : "Something went wrong. Please check logs"]
        );
    }

    public function deleteCar($id)
    {
        /** @var Car */
        $car = Car::findOrFail($id);
        $this->authorize('delete', $car);
        $res = $car->delete();
        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Car deleted" : "Unable to delete"]
        );
    }

    ///Models functions
    public function setCarModel(SetBrandRequest $request)
    {
        $data = $request->validated();
        if (isset($data['id']) && is_numeric($data['id'])) {
            /** @var CarModel */
            $model = CarModel::findOrFail($data['id']);
            $res = $model->editInfo($data['name'], $data['brand_id']);
        } else {
            $res = CarModel::newCarModel($data['name'], $data['brand_id']);
        }

        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Car model data saved successfully" : "Something went wrong. Please check logs"]
        );
    }

    public function deleteCarModel($id)
    {
        /** @var CarModel */
        $model = CarModel::findOrFail($id);
        $this->authorize('delete', $model);
        $res = $model->delete();
        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Car model deleted" : "Unable to delete"]
        );
    }


    ////Brands functions
    public function setBrand(SetBrandRequest $request)
    {
        $data = $request->validated();
        if (isset($data['id']) && is_numeric($data['id'])) {
            /** @var Brand */
            $brand = Brand::findOrFail($data['id']);
            $res = $brand->editInfo($data['name'], $data['country_id']);
        } else {
            $res = Brand::newBrand($data['name'], $data['country_id']);
        }

        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Brand data saved successfully" : "Something went wrong. Please check logs"]
        );
    }

    public function deleteBrand($id)
    {
        /** @var Brand */
        $brand = Brand::findOrFail($id);
        $this->authorize('delete', $brand);
        $res = $brand->delete();
        return redirect()->action([self::class, 'index'])->with(
            ['alert_msg'  => ($res) ? "Brand deleted" : "Unable to delete"]
        );
    }
}
