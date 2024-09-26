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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AppLogController;
use App\Models\Customers\Customer;
use App\Models\Users\ContactInfo;
use Illuminate\Support\Facades\Route;



use App\Http\Livewire\Accounting\MainAccountIndex;
use App\Http\Livewire\Accounting\AccountIndex;
use App\Http\Livewire\Accounting\AccountShow;
use App\Http\Livewire\Accounting\CreateJournalEntry;
use App\Http\Livewire\Accounting\UnapprovedEntryIndex;
use App\Http\Livewire\Accounting\UpdateUnapprovedEntry;
use App\Http\Livewire\JournalEntryIndex;
use App\Models\Accounting\Account;

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
    Route::get('/calendar', [HomeController::class, 'calendar']);

    //Users routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/contacts', [UserController::class, 'contactIndex']);
    Route::get('/profile', [UserController::class, 'show']);

    Route::get('/notifications', [UserController::class, 'notfIndex']);
    Route::post('/notifications/seen/{id}', [UserController::class, 'setNotfAsSeen']);


    Route::get('/policies', [PoliciesController::class, 'index'])->name('policies.index');
    Route::get('/policies/new', [PoliciesController::class, 'create'])->name('policies.create');
    Route::get('/policies/{id}', [PoliciesController::class, 'show'])->name('policies.show');

    Route::get('/sold-policies/{id}', [PoliciesController::class, 'soldPolicyShow'])->name('sold.policy.show');
    Route::get('/sold-policies', [PoliciesController::class, 'soldPolicyIndex'])->name('sold.policy.index');
    Route::get('/exp-sold-policies', [PoliciesController::class, 'expSoldPolicyIndex'])->name('exp-sold.policy.index');
    Route::get('/outstanding-sold-policies', [PoliciesController::class, 'outstandingSoldPolicyIndex'])->name('outstanding-sold.policy.index');

    Route::get('/payments', [PaymentController::class, 'clientPaymentsIndex'])->name('client.payments.index');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/claims', [TaskController::class, 'claimsIndex'])->name('claims.index');
    Route::get('/endorsement', [TaskController::class, 'endorsementIndex'])->name('endorsement.index');
    Route::get('/tasks/temp', [TaskController::class, 'tempTasksIndex'])->name('/temptasks.index');
    Route::get('/tasks/my', [TaskController::class, 'my'])->name('tasks.show');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/followups', [CustomerController::class, 'followupsIndex'])->name('followups.index');

    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{id}', [OfferController::class, 'show'])->name('offers.show');
    Route::get('/commissions', [OfferController::class, 'commissionsIndex'])->name('comm.profile.index');
    Route::get('/commissions/{id}', [OfferController::class, 'commissionsShow'])->name('comm.profile.show');

    Route::get('/corporates', [CorporateController::class, 'index'])->name('corporates.index');
    Route::get('/corporates/{id}', [CorporateController::class, 'show'])->name('corporates.show');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/logs', [AppLogController::class, 'index'])->name('logs.index');
    Route::get('/slarecords', [AppLogController::class, 'slaRecordsIndex'])->name('slarecords.index');

    Route::get('/reports/sold-policy', [ReportController::class, 'soldPolicyIndex'])->name('reports.soldpolicy');
    Route::get('/reports/offers', [ReportController::class, 'offersIndex'])->name('reports.offers');
    Route::get('/reports/client-payment-finance', [ReportController::class, 'clientPaymentsFinance'])->name('reports.offers');

    //Cars routes
    Route::get('/cars', [CarsController::class, 'index']);
    Route::post('/cars', [CarsController::class, 'setCar']);
    Route::get('/cars/delete/{id}', [CarsController::class, 'deleteCar']);
    Route::post('/cars/model', [CarsController::class, 'setCarModel']);
    Route::get('/cars/model/delete/{id}', [CarsController::class, 'deleteCarModel']);
    Route::post('/cars/brand', [CarsController::class, 'setBrand']);
    Route::get('/cars/brands/delete/{id}', [CarsController::class, 'deleteBrand']);
    // Route::get('/fix/pw', [UserController::class, 'fixPasswords']);



    //accounting
    Route::get( '/accounts/main', MainAccountIndex::class );
    Route::get( '/accounts', AccountIndex::class );
    Route::get( '/account/{id}', AccountShow::class );
    Route::get('/entries',JournalEntryIndex::class);
    Route::get('/entries/new',CreateJournalEntry::class);
    Route::get('/entries/unapproved',UnapprovedEntryIndex::class);
    Route::get('/entries/unapproved/{id}',UpdateUnapprovedEntry::class)->name('entries.unapproved');
    Route::get( '/accounts/gettree/{id}', function($id){
        return response()->json(Account::findOrFail($id)->getTree());
    });

});

Route::get('/welcome', [HomeController::class, 'welcome']);
Route::post('/login', [HomeController::class, 'authenticate']);
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

Route::get('/contact/{id}', function ($id) {
    $contact = ContactInfo::findOrFail($id);
    return $contact->downloadvCard();
});

Route::get('/contact/generate/{id}', function ($id) {
    $contact = ContactInfo::findOrFail($id);
    return $contact->generateQRCode();
});
