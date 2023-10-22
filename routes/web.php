<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AppLogController;
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

    //Users routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/profile', [UserController::class, 'show']);

    Route::get('/notifications', function () {
        return view('users.notifications');
    });


    Route::get('/policies', [PoliciesController::class, 'index'])->name('policies.index');
    Route::get('/policies/new', [PoliciesController::class, 'create'])->name('policies.create');
    Route::get('/policies/{id}', [PoliciesController::class, 'show'])->name('policies.show');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/my', [TaskController::class, 'my'])->name('tasks.show');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/logs', [AppLogController::class, 'index'])->name('logs.index');

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
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
