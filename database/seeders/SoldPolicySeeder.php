<?php

namespace Database\Seeders;

use App\Models\Business\SoldPolicy;
use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Offers\Offer;
use App\Models\Offers\OfferOption;
use App\Models\Tasks\TaskAction;
use App\Models\Tasks\TaskField;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class SoldPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();
        $customers = Customer::all();
        $corporates = Corporate::all();
        $policies = Policy::all();

        //60 customer sold policies
        for ($i = 0; $i < 30; $i++) {
            $tmpCustomer = $customers->random();
            $tmpPolicy = $policies->random();
            $tmpCond = $tmpPolicy->conditions->random();
            $insuredValue = $faker->biasedNumberBetween(100000, 9999999, 'Faker\Provider\Biased::linearHigh');
            $tmpPaymentFreq = $faker->randomElement(OfferOption::PAYMENT_FREQS);
            $tmpInstallementCount = $faker->numberBetween(1, 12);

            $tmpStart = $faker->dateTimeThisDecade('-1 year');
            $tmpEnd = (new Carbon($tmpStart))->addYear();

            $newSoldPolciy = SoldPolicy::newSoldPolicy(
                client: $tmpCustomer,
                policy_id: $tmpPolicy->id,
                policy_number: $faker->numberBetween(100000, 999999),
                insured_value: $insuredValue,
                net_rate: $tmpCond->rate,
                net_premium: $tmpCond->rate * $insuredValue,
                gross_premium: $tmpPolicy->calculateGrossValue($tmpCond->rate * $insuredValue),
                installements_count: $tmpInstallementCount,
                payment_frequency: $tmpPaymentFreq,
                start: new Carbon($tmpStart),
                expiry: new Carbon($tmpEnd),
                customer_car_id: $tmpCustomer->cars->count() ? $tmpCustomer->cars->random()?->id : null,
                car_chassis: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean13 : null,
                car_engine: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean8 : null,
                car_plate_no: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean8 : null
            );
            if (!$newSoldPolciy) continue;
            $noOfClaims = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfEndorsements = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfBenefits = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfExclusions = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearLow');

            for ($k = 0; $k < $noOfClaims; $k++) {
                $noOfFields = $faker->biasedNumberBetween(0, 10, 'Faker\Provider\Biased::linearHigh');
                $tmpFields = [];
                for ($j = 0; $j < $noOfFields; $j++) {
                    array_push($tmpFields, [
                        "title" =>  $faker->randomElement(TaskField::TITLES),
                        "value" =>  $faker->name
                    ]);
                }

                $newSoldPolciy->addClaim(
                    due: rand(0, 5) != 0 ? new Carbon($faker->dateTimeThisMonth) : null,
                    desc: rand(0, 2) != 0 ? $faker->text : null,
                    fields: $tmpFields
                );
            }

            for ($k = 0; $k < $noOfEndorsements; $k++) {
                $noOfActions = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearLow');

                $tmpActions = [];
                for ($j = 0; $j < $noOfActions; $j++) {
                    $tmpColumn = $faker->randomElement(TaskAction::COLUMNS['sold_policies']);
                    switch ($tmpColumn) {
                        case 'expiry':
                            $tmpValue = (new Carbon($faker->dateTimeBetween("+1 year", "+4 years")))->format('Y-m-d H:i');
                            break;
                        case 'insured_value':
                            $tmpValue = $faker->biasedNumberBetween(100000, 9999999, 'Faker\Provider\Biased::linearHigh');
                            break;

                        default:
                            $tmpValue = $faker->ean8;
                            break;
                    }
                    array_push($tmpActions, [
                        "column_name" =>  $tmpColumn,
                        "value" =>  $tmpValue
                    ]);
                }

                $newSoldPolciy->addEndorsement(
                    due: rand(0, 5) != 0 ? new Carbon($faker->dateTimeThisMonth) : null,
                    desc: rand(0, 2) != 0 ? $faker->text : null,
                    actions: $tmpActions
                );
            }

            for ($k = 0; $k < $noOfBenefits; $k++) {
                $newSoldPolciy->addBenefit(
                    benefit: $faker->randomElement(PolicyBenefit::BENEFITS),
                    value: $faker->name
                );
            }

            for ($k = 0; $k < $noOfExclusions; $k++) {
                $newSoldPolciy->addExclusion(
                    title: $faker->name,
                    value: $faker->name
                );
            }
        }

        //40 customer sold policies
        for ($i = 0; $i < 20; $i++) {
            $tmpCorporate = $corporates->random();
            $tmpPolicy = $policies->random();
            $tmpCond = $tmpPolicy->conditions->random();
            $insuredValue = $faker->biasedNumberBetween(100000, 9999999, 'Faker\Provider\Biased::linearHigh');
            $tmpPaymentFreq = $faker->randomElement(OfferOption::PAYMENT_FREQS);
            $tmpInstallementCount = $faker->numberBetween(1, 12);

            $tmpStart = $faker->dateTimeThisDecade('-1 year');
            $tmpEnd = (new Carbon($tmpStart))->addYear();

            $newSoldPolciy = SoldPolicy::newSoldPolicy(
                client: $tmpCorporate,
                policy_id: $tmpPolicy->id,
                policy_number: $faker->numberBetween(100000, 999999),
                insured_value: $insuredValue,
                net_rate: $tmpCond->rate,
                net_premium: $tmpCond->rate * $insuredValue,
                gross_premium: $tmpPolicy->calculateGrossValue($tmpCond->rate * $insuredValue),
                installements_count: $tmpInstallementCount,
                payment_frequency: $tmpPaymentFreq,
                start: new Carbon($tmpStart),
                expiry: new Carbon($tmpEnd),
                car_chassis: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean13 : null,
                car_engine: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean8 : null,
                car_plate_no: (($tmpPolicy->type == Policy::BUSINESS_PERSONAL_MOTOR || $tmpPolicy->type == Policy::BUSINESS_CORPORATE_MOTOR) && rand(0, 5) !== 0) ? $faker->ean8 : null
            );

            if (!$newSoldPolciy) continue;
            $noOfClaims = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfEndorsements = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfBenefits = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearHigh');
            $noOfExclusions = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearLow');

            for ($k = 0; $k < $noOfClaims; $k++) {
                $noOfFields = $faker->biasedNumberBetween(0, 10, 'Faker\Provider\Biased::linearHigh');
                $tmpFields = [];
                for ($j = 0; $j < $noOfFields; $j++) {
                    array_push($tmpFields, [
                        "title" =>  $faker->randomElement(TaskField::TITLES),
                        "value" =>  $faker->name
                    ]);
                }

                $newSoldPolciy->addClaim(
                    due: rand(0, 5) != 0 ? new Carbon($faker->dateTimeThisMonth) : null,
                    desc: rand(0, 2) != 0 ? $faker->text : null,
                    fields: $tmpFields
                );
            }

            for ($k = 0; $k < $noOfEndorsements; $k++) {
                $noOfActions = $faker->biasedNumberBetween(0, 5, 'Faker\Provider\Biased::linearLow');

                $tmpActions = [];
                for ($j = 0; $j < $noOfActions; $j++) {
                    $tmpColumn = $faker->randomElement(TaskAction::COLUMNS['sold_policies']);
                    switch ($tmpColumn) {
                        case 'expiry':
                            $tmpValue = (new Carbon($faker->dateTimeBetween("+1 year", "+4 years")))->format('Y-m-d H:i');
                            break;
                        case 'insured_value':
                            $tmpValue = $faker->biasedNumberBetween(100000, 9999999, 'Faker\Provider\Biased::linearHigh');
                            break;

                        default:
                            $tmpValue = $faker->ean8;
                            break;
                    }
                    array_push($tmpActions, [
                        "column_name" =>  $tmpColumn,
                        "value" =>  $tmpValue
                    ]);
                }

                $newSoldPolciy->addEndorsement(
                    due: rand(0, 5) != 0 ? new Carbon($faker->dateTimeThisMonth) : null,
                    desc: rand(0, 2) != 0 ? $faker->text : null,
                    actions: $tmpActions
                );
            }

            for ($k = 0; $k < $noOfBenefits; $k++) {
                $newSoldPolciy->addBenefit(
                    benefit: $faker->randomElement(PolicyBenefit::BENEFITS),
                    value: $faker->name
                );
            }

            for ($k = 0; $k < $noOfExclusions; $k++) {
                $newSoldPolciy->addExclusion(
                    title: $faker->name,
                    value: $faker->name
                );
            }
        }
    }
}
