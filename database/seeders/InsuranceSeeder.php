<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance\Company;
use App\Models\Insurance\CompanyEmail;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyCondition;
use Faker\Factory as FakerFactory;

class InsuranceSeeder extends Seeder
{
    public function run()
    {
        $faker = FakerFactory::create();

        // Seed data for insurance_companies table
        for ($i = 0; $i < 20; $i++) {
            Company::newCompany(
                $faker->company,
                $faker->text,
            );
        }

        $companies = Company::all();

        // Seed data for CompanyEmail
        for ($i = 0; $i < 50; $i++) {
            $companies->random()->addEmail(
                $faker->randomElement(CompanyEmail::TYPES),
                $faker->email,
                false,
                $faker->firstName,
                $faker->lastName,
                $faker->text,
            );
        }

        // Seed data for Policy
        for ($i = 0; $i < 20; $i++) {
            Policy::newPolicy(
                $companies->random()->id,
                $faker->word,
                $faker->randomElement(Policy::LINES_OF_BUSINESS),
                $faker->text,
            );
        }

        $policies = Policy::all();
        // Seed data for PolicyCondition
        for ($i = 0; $i < 100; $i++) {

            $policies->random()->addCondition(
                $faker->randomElement(PolicyCondition::SCOPES),
                $faker->randomElement(PolicyCondition::OPERATORS),
                $faker->randomFloat(2, 1, 100),
                $faker->randomNumber(2),
                $faker->randomFloat(2, 0.1, 10),
                $faker->text
            );
        }
    }
}
