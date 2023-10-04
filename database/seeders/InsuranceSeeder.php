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
        $insuranceCompanies = [];
        for ($i = 0; $i < 20; $i++) {
            $insuranceCompanies[] = [
                'name' => $faker->company,
                'note' => $faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Company::insert($insuranceCompanies);

        // Seed data for CompanyEmail
        for ($i = 0; $i < 50; $i++) {
            CompanyEmail::create([
                'company_id' => rand(1, 10), 
                'type' => $faker->randomElement([
                    CompanyEmail::TYPE_INFO,
                    CompanyEmail::TYPE_FINANCE,
                    CompanyEmail::TYPE_OPERATIONS,
                    CompanyEmail::TYPE_SALES,
                    CompanyEmail::TYPE_SUPPORT,
                ]),
                'email' => $faker->email,
                'is_primary' => $faker->boolean,
                'contact_first_name' => $faker->firstName,
                'contact_last_name' => $faker->lastName,
                'note' => $faker->text,
            ]);
        }

        // Seed data for Policy
        for ($i = 0; $i < 20; $i++) {
            Policy::create([
                'company_id' => rand(1, 10), 
                'business' => $faker->randomElement([
                    Policy::BUSINESS_MOTOR,
                    Policy::BUSINESS_HEALTH,
                    Policy::BUSINESS_LIFE,
                    Policy::BUSINESS_PROPERTY,
                    Policy::BUSINESS_CARGO,
                ]),
                'name' => $faker->word,
                'note' => $faker->text,
            ]);
        }

        // Seed data for PolicyCondition
        for ($i = 0; $i < 100; $i++) {
            PolicyCondition::create([
                'policy_id' => rand(1, 10),
                'scope' => $faker->randomElement(PolicyCondition::SCOPES),
                'operator' => $faker->randomElement(PolicyCondition::OPERATORS),
                'value' => $faker->randomFloat(2, 1, 100),
                'rate' => $faker->randomFloat(2, 0.1, 10),
                'order' => $faker->randomNumber(2),
                'note' => $faker->text,
            ]);
        }
    }
}
