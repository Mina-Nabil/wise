<?php

namespace App\Providers;

use App\Models\Cars\Brand;
use App\Models\Cars\Car;
use App\Models\Cars\CarPrice;
use App\Models\Cars\CarModel;
use App\Models\Base\Country;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use App\Models\Tasks\Task;
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
            Brand::MORPH_TYPE => Brand::class,
            Car::MORPH_TYPE => Car::class,
            CarModel::MORPH_TYPE => CarModel::class,
            CarPrice::MORPH_TYPE => CarPrice::class,
            Country::MORPH_TYPE => Country::class,
            Company::MORPH_TYPE => Company::class,
            CompanyEmail::MORPH_TYPE => CompanyEmail::class,
            Policy::MORPH_TYPE => Policy::class,
            PolicyCondition::MORPH_TYPE => PolicyCondition::class,
            Task::MORPH_TYPE => Task::class
        ]);
    }
}
