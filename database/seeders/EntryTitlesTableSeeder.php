<?php

namespace Database\Seeders;

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EntryTitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) { // Generates 10 random entries
            EntryTitle::create([
                'name' => $faker->company . ' ' . $faker->bs, // Random company name with a random business phrase
                'desc' => $faker->sentence, // Random sentence as description
            ]);
        }
    }
}
