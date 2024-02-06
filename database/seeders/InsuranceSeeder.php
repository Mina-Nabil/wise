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
        Company::newCompany("Allianz"); //1
        Company::newCompany("GIG"); //2
        Company::newCompany("Wethaq"); //3
        Company::newCompany("Mohandes"); //4
        Company::newCompany("Egyptian"); //5
        Company::newCompany("Royal insurance"); //6
        Company::newCompany("Alwataniya"); //7

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

        ///// Seed data for Policy
        //Allianz
        Policy::newPolicy(1, "Motor One", Policy::BUSINESS_PERSONAL_MOTOR);
        Policy::newPolicy(1, "Motor Plus", Policy::BUSINESS_PERSONAL_MOTOR);
        Policy::newPolicy(1, "Motor Express", Policy::BUSINESS_PERSONAL_MOTOR);
        Policy::newPolicy(1, "Motor One", Policy::BUSINESS_CORPORATE_MOTOR);
        Policy::newPolicy(1, "Motor Plus", Policy::BUSINESS_CORPORATE_MOTOR);
        Policy::newPolicy(1, "Motor Express", Policy::BUSINESS_CORPORATE_MOTOR);
        Policy::newPolicy(1, "Medical", Policy::BUSINESS_PERSONAL_MEDICAL);
        Policy::newPolicy(1, "Medical", Policy::BUSINESS_CORPORATE_MEDICAL);
        Policy::newPolicy(1, "Business", Policy::BUSINESS_BUSINESS);

        //GiG
        Policy::newPolicy(2, "Classic", Policy::BUSINESS_PERSONAL_MOTOR);
        Policy::newPolicy(2, "Golden", Policy::BUSINESS_PERSONAL_MOTOR);
        
        //Egyptian
        Policy::newPolicy(2, "Egyptian", Policy::BUSINESS_PERSONAL_MOTOR);
        
        //Wethaq
        Policy::newPolicy(3, "Wethaq", Policy::BUSINESS_PERSONAL_MOTOR);
        
        //Mohandes
        Policy::newPolicy(4, "Mohandes", Policy::BUSINESS_PERSONAL_MOTOR);
        
        //Egyptian
        Policy::newPolicy(5, "Egyptian", Policy::BUSINESS_PERSONAL_MOTOR);
        
        //Royal
        Policy::newPolicy(6, "Medical", Policy::BUSINESS_PERSONAL_MEDICAL);
        
        //Alwataniya
        Policy::newPolicy(7, "Alwataniya", Policy::BUSINESS_PERSONAL_MOTOR);
        
        Policy::newPolicy(1, "Liability", Policy::BUSINESS_LIABILITY);
        Policy::newPolicy(2, "Liability", Policy::BUSINESS_LIABILITY);
        Policy::newPolicy(3, "Liability", Policy::BUSINESS_LIABILITY);
        Policy::newPolicy(4, "Liability", Policy::BUSINESS_LIABILITY);
        Policy::newPolicy(5, "Liability", Policy::BUSINESS_LIABILITY);
        Policy::newPolicy(6, "Liability", Policy::BUSINESS_LIABILITY);


        $policies = Policy::all();
        // Seed data for PolicyCondition
        for ($i = 0; $i < 100; $i++) {

            $policies->random()->addCondition(
                $faker->randomElement(PolicyCondition::SCOPES),
                $faker->randomElement(PolicyCondition::OPERATORS),
                $faker->randomFloat(2, 1, 100),
                $faker->randomFloat(2, 0.1, 10),
                $faker->text
            );
        }
    }
}
