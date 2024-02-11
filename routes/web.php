<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AppLogController;
use App\Models\Customers\Customer;
use App\Models\Users\ContactInfo;
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

    Route::get('/notifications', [UserController::class, 'notfIndex']);
    Route::post('/notifications/seen/{id}', [UserController::class, 'setNotfAsSeen']);


    Route::get('/policies', [PoliciesController::class, 'index'])->name('policies.index');
    Route::get('/policies/new', [PoliciesController::class, 'create'])->name('policies.create');
    Route::get('/policies/{id}', [PoliciesController::class, 'show'])->name('policies.show');

    Route::get('/sold-policies/{id}', [PoliciesController::class, 'soldPolicyShow'])->name('sold.policy.show');
    Route::get('/sold-policies', [PoliciesController::class, 'soldPolicyIndex'])->name('sold.policy.index');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/temp', [TaskController::class, 'tempTasksIndex'])->name('/temptasks.index');
    Route::get('/tasks/my', [TaskController::class, 'my'])->name('tasks.show');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/followups', [CustomerController::class, 'followupsIndex'])->name('followups.index');

    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{id}', [OfferController::class, 'show'])->name('offers.show');

    Route::get('/corporates', [CorporateController::class, 'index'])->name('corporates.index');
    Route::get('/corporates/{id}', [CorporateController::class, 'show'])->name('corporates.show');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/logs', [AppLogController::class, 'index'])->name('logs.index');
    Route::get('/slarecords', [AppLogController::class, 'slaRecordsIndex'])->name('slarecords.index');

    //Cars routes
    Route::get('/cars', [CarsController::class, 'index']);
    Route::post('/cars', [CarsController::class, 'setCar']);
    Route::get('/cars/delete/{id}', [CarsController::class, 'deleteCar']);
    Route::post('/cars/model', [CarsController::class, 'setCarModel']);
    Route::get('/cars/model/delete/{id}', [CarsController::class, 'deleteCarModel']);
    Route::post('/cars/brand', [CarsController::class, 'setBrand']);
    Route::get('/cars/brands/delete/{id}', [CarsController::class, 'deleteBrand']);
    // Route::get('/fix/pw', [UserController::class, 'fixPasswords']);
});

Route::post('/login', [HomeController::class, 'authenticate']);
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

Route::get('/contact/{id}', function($id){
    $contact = ContactInfo::findOrFail($id);
    return $contact->downloadvCard();
});

Route::get('/contact/generate/{id}', function($id){
    $contact = ContactInfo::findOrFail($id);
    return $contact->generateQRCode();
});
