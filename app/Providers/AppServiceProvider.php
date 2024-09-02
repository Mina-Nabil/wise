<?php

namespace App\Providers;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\MainAccount;
use App\Models\Accounting\UnapprovedEntry;
use App\Models\Base\Area;
use App\Models\Base\City;
use App\Models\Cars\Brand;
use App\Models\Cars\Car;
use App\Models\Cars\CarPrice;
use App\Models\Cars\CarModel;
use App\Models\Base\Country;
use App\Models\Business\SoldPolicy;
use App\Models\Corporates\Address;
use App\Models\Corporates\BankAccount;
use App\Models\Corporates\Contact;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Phone;
use App\Models\Customers\Address as CustomersAddress;
use App\Models\Customers\Car as CustomersCar;
use App\Models\Customers\Customer;
use App\Models\Customers\Followup;
use App\Models\Customers\Phone as CustomersPhone;
use App\Models\Customers\Profession;
use App\Models\Customers\Relative;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Payments\ClientPayment;
use App\Models\Payments\CommProfile;
use App\Models\Payments\CommProfileConf;
use App\Models\Payments\CompanyCommPayment;
use App\Models\Payments\PolicyComm;
use App\Models\Payments\PolicyCommConf;
use App\Models\Payments\SalesComm;
use App\Models\Payments\Target;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskComment;
use App\Models\Tasks\TaskFile;
use App\Models\Tasks\TaskWatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            City::MORPH_TYPE => City::class,
            Area::MORPH_TYPE => Area::class,
            Country::MORPH_TYPE => Country::class,
            Brand::MORPH_TYPE => Brand::class,
            Car::MORPH_TYPE => Car::class,
            CarModel::MORPH_TYPE => CarModel::class,
            CarPrice::MORPH_TYPE => CarPrice::class,
            Company::MORPH_TYPE => Company::class,
            CompanyEmail::MORPH_TYPE => CompanyEmail::class,
            Policy::MORPH_TYPE => Policy::class,
            PolicyCondition::MORPH_TYPE => PolicyCondition::class,
            Task::MORPH_TYPE => Task::class,
            Customer::MORPH_TYPE => Customer::class,
            Corporate::MORPH_TYPE => Corporate::class,
            Address::MORPH_TYPE => Address::class,
            BankAccount::MORPH_TYPE => BankAccount::class,
            Contact::MORPH_TYPE => Contact::class,
            Phone::MORPH_TYPE => Phone::class,
            CustomersAddress::MORPH_TYPE => CustomersAddress::class,
            CustomersCar::MORPH_TYPE => CustomersCar::class,
            CustomersPhone::MORPH_TYPE => CustomersPhone::class,
            Profession::MORPH_TYPE => Profession::class,
            Relative::MORPH_TYPE => Relative::class,
            Followup::MORPH_TYPE => Followup::class,
            TaskComment::MORPH_TYPE => TaskComment::class,
            TaskFile::MORPH_TYPE => TaskFile::class,
            TaskWatcher::MORPH_TYPE => TaskWatcher::class,
            Offer::MORPH_TYPE => Offer::class,
            OfferOption::MORPH_TYPE => OfferOption::class,
            SoldPolicy::MORPH_TYPE => SoldPolicy::class,
            
            PolicyCommConf::MORPH_TYPE => PolicyCommConf::class,
            PolicyComm::MORPH_TYPE => PolicyComm::class,
            CompanyCommPayment::MORPH_TYPE => CompanyCommPayment::class,
            CommProfileConf::MORPH_TYPE => CommProfileConf::class,
            CommProfile::MORPH_TYPE => CommProfile::class,
            ClientPayment::MORPH_TYPE => ClientPayment::class,
            SalesComm::MORPH_TYPE => SalesComm::class,
            Target::MORPH_TYPE => Target::class,
            
            JournalEntry::MORPH_TYPE => JournalEntry::class,
            MainAccount::MORPH_TYPE => MainAccount::class,
            Account::MORPH_TYPE => Account::class,
            UnapprovedEntry::MORPH_TYPE => UnapprovedEntry::class,


        ]);
    }
}
