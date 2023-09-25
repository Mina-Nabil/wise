<?php

namespace Database\Seeders;

use App\Models\Cars\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::newCountry('China');
        Country::newCountry('Germany');
        Country::newCountry('Spain');
        Country::newCountry('USA');
        Country::newCountry('S.Korea');
        Country::newCountry('Russia');
        Country::newCountry('Italy');
        Country::newCountry('UK');
        Country::newCountry('Egypt');
        Country::newCountry('France');
        Country::newCountry('Japan');
        Country::newCountry('Thailand');
        Country::newCountry('Indonesia');
        Country::newCountry('Turkey');
    }
}
