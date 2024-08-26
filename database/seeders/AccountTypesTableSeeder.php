<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AccountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 30; $i++) {
            DB::table('account_types')->insert([
                'name' => $faker->unique()->words(3, true), // Generate a unique 3-word name
                'desc' => $faker->sentence,                 // Generate a random sentence for the description
            ]);
        }
    }
}
