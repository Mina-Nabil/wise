<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//login routes
Route::middleware('auth', 'active')->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    //Cars routes
    Route::get('/cars', [CarsController::class, 'index']);
    Route::post('/cars', [CarsController::class, 'setCar']);
    Route::get('/cars/delete/{id}', [CarsController::class, 'deleteCar']);
    Route::post('/cars/model', [CarsController::class, 'setCarModel']);
    Route::get('/cars/model/delete/{id}', [CarsController::class, 'deleteCarModel']);
    Route::post('/cars/brand', [CarsController::class, 'setBrand']);
    Route::get('/cars/brands/delete/{id}', [CarsController::class, 'deleteBrand']);
    Route::get('/test/notf', [UserController::class, 'testNotf']);
});

Route::post('/login', [HomeController::class, 'authenticate']);
Route::get('/login', [HomeController::class, 'login'])->name('login');

