<?php

namespace Database\Seeders;

use App\Models\Base\Country;
use App\Models\Cars\Car;
use App\Models\Customers\Address;
use App\Models\Customers\Car as CustomersCar;
use App\Models\Customers\Customer;
use App\Models\Customers\Phone;
use App\Models\Customers\Profession;
use App\Models\Customers\Relative;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class CustomersSeeder extends Seeder
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

        //create 50 normal customers
        for ($i = 0; $i < 50; $i++) {
            $newCust = Customer::newCustomer(
                owner_id: $faker->randomElement([10, 11, 6]), //mina (admin) , michael(admin), sandy(sales),
                first_name: $faker->firstName,
                last_name: $faker->lastName,
                gender: $faker->randomElement(Customer::GENDERS),
                email: rand(0, 2) == 0 ? $faker->email : null, // 1/3 is null
                middle_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_middle_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_first_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_last_name: rand(0, 1) == 0 ? $faker->lastName : null, //half is null
                birth_date: rand(0, 3) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null,
                marital_status: rand(0, 9) == 0 ? $faker->randomElement(Customer::MARITALSTATUSES) : null,
                id_type: $is_id_null == 0 ? $faker->randomElement(Customer::IDTYPES) : null,
                id_number: $is_id_null == 0 ? $faker->randomNumber(8) : null,
                nationality_id: rand(0, 9) == 0 ? $countries->random()->id : null,
                profession_id: rand(0, 9) == 0 ? $professions->random()->id : null,
                salary_range: rand(0, 9) == 0 ? $faker->randomElement(Customer::SALARY_RANGES) : null,
                income_source: rand(0, 9) == 0 ? $faker->randomElement(Customer::INCOME_SOURCES) : null
            );

            $carsCount = $faker->biasedNumberBetween(0, 10,  'Faker\Provider\Biased::linearLow');
            $relativesCount = $faker->biasedNumberBetween(0, 5,  'Faker\Provider\Biased::linearLow');
            $addressCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearLow');
            $phonesCount = $faker->biasedNumberBetween(0, 3,  'Faker\Provider\Biased::linearHigh');

            for ($k = 0; $k < $carsCount; $k++) {
                $newCust->addCar(
                    car_id: $cars->random()->id,
                    model_year: $faker->numberBetween(2000, 2023),
                    sum_insured: rand(0, 9) == 0 ? $faker->numberBetween(10000, 5000000) : null,
                    insurance_payment: rand(0, 9) == 0 ? $faker->numberBetween(5000, 50000) : null,
                    payment_frequency: rand(0, 9) == 0 ? $faker->randomElement(CustomersCar::PAYMENT_FREQS) : null,
                );
            }
            for ($j = 0; $j < $relativesCount; $j++) {
                $newCust->addRelative(
                    name: $faker->name,
                    relation: rand(0, 9) == 0 ? $faker->randomElement(Relative::RELATIONS) : null,
                    gender: rand(0, 1) == 0 ? $faker->randomElement(Customer::GENDERS) : null,
                    phone: rand(0, 2) == 0 ? $faker->randomNumber(8) : null,
                    birth_date: rand(0, 1) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null
                );
            }
            for ($m = 0; $m < $addressCount; $m++) {
                $newCust->addAddress(
                    type: $faker->randomElement(Address::TYPES),
                    line_1: $faker->address,
                    line_2: rand(0, 1) == 0 ? $faker->address : null,
                    country: rand(0, 1) == 0 ? $faker->country : null,
                    city: rand(0, 1) == 0 ? $faker->city : null,
                    building: rand(0, 1) == 0 ? $faker->numberBetween(1, 999) : null,
                    flat: rand(0, 1) == 0 ? $faker->numberBetween(1, 50) : null,
                    is_default: $faker->boolean(25)
                );
            }
            for ($l = 0; $l < $phonesCount; $l++) {
                $newCust->addPhone(
                    type: $faker->randomElement(Phone::TYPES),
                    number: $faker->phoneNumber,
                    is_default: $faker->boolean(25)
                );
            }
        }

        //create 50 leads
        for ($i = 0; $i < 50; $i++) {
            $newCust = Customer::newLead(
                first_name: $faker->firstName,
                last_name: $faker->lastName,
                phone: $faker->phoneNumber,
                middle_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_middle_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_first_name: rand(0, 1) == 0 ? $faker->firstName : null, //half is null
                arabic_last_name: rand(0, 1) == 0 ? $faker->lastName : null, //half is null
                birth_date: rand(0, 3) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null,
                email: rand(0, 2) == 0 ? $faker->email : null, // 1/3 is null
                gender: $faker->randomElement(Customer::GENDERS),
                marital_status: rand(0, 9) == 0 ? $faker->randomElement(Customer::MARITALSTATUSES) : null,
                id_type: $is_id_null == 0 ? $faker->randomElement(Customer::IDTYPES) : null,
                id_number: $is_id_null == 0 ? $faker->randomNumber(8) : null,
                nationality_id: rand(0, 9) == 0 ? $countries->random()->id : null,
                profession_id: rand(0, 9) == 0 ? $professions->random()->id : null,
                salary_range: rand(0, 9) == 0 ? $faker->randomElement(Customer::SALARY_RANGES) : null,
                income_source: rand(0, 9) == 0 ? $faker->randomElement(Customer::INCOME_SOURCES) : null,
                owner_id: $faker->randomElement([10, 11, 6, null]), //mina - michael - sales_account or null -->potential owners
            );

            $carsCount = $faker->biasedNumberBetween(0, 3,  'Faker\Provider\Biased::linearLow');
            $relativesCount = $faker->biasedNumberBetween(0, 1,  'Faker\Provider\Biased::linearLow');
            $addressCount = $faker->biasedNumberBetween(0, 1,  'Faker\Provider\Biased::linearLow');
            $phonesCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearHigh');

            for ($k = 0; $k < $carsCount; $k++) {
                $newCust->addCar(
                    car_id: $cars->random()->id,
                    sum_insured: rand(0, 9) == 0 ? $faker->numberBetween(10000, 5000000) : null,
                    insurance_payment: rand(0, 9) == 0 ? $faker->numberBetween(5000, 50000) : null,
                    payment_frequency: rand(0, 9) == 0 ? $faker->randomElement(CustomersCar::PAYMENT_FREQS) : null,
                    model_year: $faker->numberBetween(2000,2024)
                );
            }
            for ($m = 0; $m < $relativesCount; $m++) {
                $newCust->addRelative(
                    name: $faker->name,
                    relation:  rand(0, 9) == 0 ?  $faker->randomElement(Relative::RELATIONS) : null,
                    gender: rand(0, 2) == 0 ? $faker->randomElement(Customer::GENDERS) : null,
                    phone: rand(0, 1) == 0 ? $faker->randomNumber(7) : null,
                    birth_date: rand(0, 1) == 0 ? $faker->date('Y-m-d', '2010-01-01') : null
                );
            }
            for ($h = 0; $h < $addressCount; $h++) {
                $newCust->addAddress(
                    type: $faker->randomElement(Address::TYPES),
                    line_1: $faker->address,
                    line_2: rand(0, 1) == 0 ? $faker->address : null,
                    country: rand(0, 1) == 0 ? $faker->country : null,
                    city: rand(0, 1) == 0 ? $faker->city : null,
                    building: rand(0, 1) == 0 ? $faker->numberBetween(1, 999) : null,
                    flat: rand(0, 1) == 0 ? $faker->numberBetween(1, 50) : null,
                    is_default: $faker->boolean()
                );
            }
            for ($s = 0; $s < $phonesCount; $s++) {
                $newCust->addPhone(
                    type: $faker->randomElement(Phone::TYPES),
                    number: $faker->phoneNumber,
                    is_default: $faker->boolean(25)
                );
            }
        }
    }
}
