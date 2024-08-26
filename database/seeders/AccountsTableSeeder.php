<?php

namespace Database\Seeders;

use App\Models\Accounting\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Account::create([
                'name' => $faker->company,
                'desc' => $faker->sentence,
                'nature' => $faker->randomElement(Account::NATURES),
                'type' => $faker->randomElement(Account::TYPES),
                'balance' => $faker->randomFloat(2, 0, 10000),
                'limit' => $faker->randomFloat(2, 0, 100000),
            ]);
        }  
    }
}
