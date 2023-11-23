<?php

namespace Database\Seeders;

use App\Models\Base\Country;
use App\Models\Cars\Car;
use App\Models\Customers\Address;
use App\Models\Customers\Car as CustomersCar;
use App\Models\Customers\Customer;
use App\Models\Customers\Profession;
use App\Models\Customers\Relative;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();
        $is_id_null = rand(0, 1);
        $countries = Country::all();
        $professions = Profession::all();
        $cars = Car::all();

        for ($i = 0; $i < 50; $i++) {
            $newCust = Customer::newLead(
                $faker->name,
                $faker->phoneNumber,
                rand(0, 1) == 0 ? $faker->email : null, // 1/3 is null
                rand(0, 3) == 0 ? $faker->phoneNumber : null, // 1/10 is null
                rand(0, 1) == 0 ? $faker->name : null, //half is null
                rand(0, 2) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null,
                $faker->randomElement(Customer::GENDERS),
                rand(0, 3) == 0 ? $faker->randomElement(Customer::MARITALSTATUSES) : null,
                $is_id_null == 0 ? $faker->randomElement(Customer::IDTYPES) : null,
                $is_id_null == 0 ? $faker->randomNumber(12) : null,
                rand(0, 2) == 0 ? $countries->random()->id : null,
                rand(0, 3) == 0 ? $professions->random()->id : null,
                rand(0, 2) == 0 ? $faker->randomElement(Customer::SALARY_RANGES) : null,
                rand(0, 1) == 0 ? $faker->randomElement(Customer::INCOME_SOURCES) : null
            );

            $carsCount = $faker->biasedNumberBetween(0, 3,  'Faker\Provider\Biased::linearLow');
            $relativesCount = $faker->biasedNumberBetween(0, 1,  'Faker\Provider\Biased::linearLow');
            $addressCount = $faker->biasedNumberBetween(0, 1,  'Faker\Provider\Biased::linearLow');

            for ($i = 0; $i < $carsCount; $i++) {
                $newCust->addCar(
                    $cars->random()->id,
                    rand(0, 9) == 0 ? $faker->numberBetween(10000, 5000000) : null,
                    rand(0, 9) == 0 ? $faker->numberBetween(5000, 50000) : null,
                    rand(0, 9) == 0 ? $faker->numberBetween(100, 3000) : null,
                    rand(0, 9) == 0 ? $faker->randomElement(CustomersCar::PAYMENT_FREQS) : null,
                );
            }
            for ($i = 0; $i < $relativesCount; $i++) {
                $newCust->addRelative(
                    $faker->name,
                    $faker->randomElement(Relative::RELATIONS),
                    rand(0, 9) == 0 ? $faker->randomElement(Customer::GENDERS) : null,
                    rand(0, 9) == 0 ? $faker->randomNumber(11) : null,
                    rand(0, 1) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null
                );
            }
            for ($i = 0; $i < $addressCount; $i++) {
                $newCust->addAddress(
                    $faker->randomElement(Address::TYPES),
                    $faker->address,
                    rand(0, 1) == 0 ? $faker->address : null,
                    rand(0, 1) == 0 ? $faker->country : null,
                    rand(0, 1) == 0 ? $faker->city : null,
                    rand(0, 1) == 0 ? $faker->numberBetween(1, 999) : null,
                    rand(0, 1) == 0 ? $faker->numberBetween(1, 50) : null,
                );
            }
        }
    }
}
