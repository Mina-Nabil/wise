<?php

namespace Database\Seeders;

use App\Models\Base\Country;
use App\Models\Corporates\Address;
use App\Models\Corporates\BankAccount;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class CorporatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();

        //creating 50 normal companies
        for ($i = 0; $i < 50; $i++) {
            $newCorp = Corporate::newCorporate(
                owner_id: $faker->randomElement([10, 11, 6]), //mina (admin) , michael(admin), sandy(sales)
                name: $faker->company,
                arabic_name: rand(0, 3) != 0 ? $faker->company : null, //3/4 is null
                email: rand(0, 2) == 0 ? $faker->email : null, // 1/3 is null
                commercial_record: rand(0, 2) == 0 ? $faker->randomNumber(7) : null, // 1/3 is null
                commercial_record_doc: rand(0, 2) == 0 ? $faker->url : null,
                tax_id: rand(0, 2) == 0 ? $faker->randomNumber(8) : null,
                tax_id_doc: rand(0, 2) == 0 ? $faker->url : null,
                kyc: rand(0, 2) == 0 ? $faker->randomNumber(8) : null,
                kyc_doc: rand(0, 2) == 0 ? $faker->url : null,
                contract_doc: rand(0, 2) == 0 ? $faker->url : null,
                main_bank_evidence: rand(0, 2) == 0 ? $faker->url : null,
            );

            $contactsCount = $faker->biasedNumberBetween(0, 7,  'Faker\Provider\Biased::linearLow');
            $accountsCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearLow');
            $addressCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearLow');
            $phonesCount = $faker->biasedNumberBetween(0, 3,  'Faker\Provider\Biased::linearHigh');

            for ($k = 0; $k < $contactsCount; $k++) {
                $newCorp->addContact(
                    name: $faker->name,
                    job_title: rand(0, 3) == 0 ? $faker->jobTitle : null,
                    email: rand(0, 3) == 0 ? $faker->email : null,
                    phone: rand(0, 3) == 0 ? $faker->phoneNumber : null,
                    is_default: $faker->boolean(25)
                );
            }
            for ($j = 0; $j < $accountsCount; $j++) {
                $newCorp->addBankAccount(
                    type: $faker->randomElement(BankAccount::TYPES),
                    bank_name: $faker->company,
                    account_number: $faker->randomNumber(8),
                    owner_name: $faker->name,
                    evidence_doc: rand(0, 3) == 0 ?  $faker->url : null,
                    iban: rand(0, 3) == 0 ?  ($faker->randomNumber(8) . $faker->randomNumber(8) . $faker->randomNumber(8)) : null,
                    bank_branch: rand(0, 3) == 0 ?  $faker->company : null,
                    is_default: $faker->boolean(25)
                );
            }
            for ($s = 0; $s < $addressCount; $s++) {
                $newCorp->addAddress(
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
            for ($a = 0; $a < $phonesCount; $a++) {
                $newCorp->addPhone(
                    type: $faker->randomElement(Phone::TYPES),
                    number: $faker->phoneNumber,
                    is_default: $faker->boolean(25)
                );
            }
        }

        //creating 50 leads
        for ($i = 0; $i < 50; $i++) {
            $newCorp = Corporate::newLead(
                owner_id: $faker->randomElement([10, 11, 6, null]), //mina (admin) , michael(admin), sandy(sales)
                name: $faker->company,
                arabic_name: rand(0, 3) != 0 ? $faker->company : null, //3/4 is null
                email: rand(0, 2) == 0 ? $faker->email : null, // 1/3 is null
                commercial_record: rand(0, 2) == 0 ? $faker->randomNumber(7) : null, // 1/3 is null
                commercial_record_doc: rand(0, 2) == 0 ? $faker->url : null,
                tax_id: rand(0, 2) == 0 ? $faker->randomNumber(8) : null,
                tax_id_doc: rand(0, 2) == 0 ? $faker->url : null,
                kyc: rand(0, 2) == 0 ? $faker->randomNumber(8) : null,
                kyc_doc: rand(0, 2) == 0 ? $faker->url : null,
                contract_doc: rand(0, 2) == 0 ? $faker->url : null,
                main_bank_evidence: rand(0, 2) == 0 ? $faker->url : null,
            );

            $contactsCount = $faker->biasedNumberBetween(0, 7,  'Faker\Provider\Biased::linearLow');
            $accountsCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearLow');
            $addressCount = $faker->biasedNumberBetween(0, 2,  'Faker\Provider\Biased::linearLow');
            $phonesCount = $faker->biasedNumberBetween(0, 3,  'Faker\Provider\Biased::linearHigh');

            for ($k = 0; $k < $contactsCount; $k++) {
                $newCorp->addContact(
                    name: $faker->name,
                    job_title: rand(0, 3) == 0 ? $faker->jobTitle : null,
                    email: rand(0, 3) == 0 ? $faker->email : null,
                    phone: rand(0, 3) == 0 ? $faker->phoneNumber : null,
                    is_default: $faker->boolean(25)
                );
            }
            for ($s = 0; $s < $accountsCount; $s++) {
                $newCorp->addBankAccount(
                    type: $faker->randomElement(BankAccount::TYPES),
                    bank_name: $faker->company,
                    account_number: $faker->randomNumber(8),
                    owner_name: $faker->name,
                    evidence_doc: rand(0, 3) == 0 ?  $faker->url : null,
                    iban: rand(0, 3) == 0 ?  ($faker->randomNumber(8) . $faker->randomNumber(8) . $faker->randomNumber(8)) : null,
                    bank_branch: rand(0, 3) == 0 ?  $faker->company : null,
                    is_default: $faker->boolean(25)
                );
            }
            for ($h = 0; $h < $addressCount; $h++) {
                $newCorp->addAddress(
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
            for ($o = 0; $o < $phonesCount; $o++) {
                $newCorp->addPhone(
                    type: $faker->randomElement(Phone::TYPES),
                    number: $faker->phoneNumber,
                    is_default: $faker->boolean(25)
                );
            }
        }
    }
}
