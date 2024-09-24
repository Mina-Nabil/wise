<?php

namespace Database\Seeders;

use App\Models\Accounting\Account;
use App\Models\Accounting\EntryTitle;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\MainAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MainAccount::newMainAccount(1, "Clients", MainAccount::TYPE_ASSET, "حساب عملاء", true);
        MainAccount::newMainAccount(2, "Costs", MainAccount::TYPE_EXPENSE, "مصاريف", true);
        MainAccount::newMainAccount(30, "Cash", MainAccount::TYPE_ASSET, "خزنه الشركه", true);
        MainAccount::newMainAccount(12, "Salaries", MainAccount::TYPE_LIABILITY, null, true);


        Account::newAccount(12, "Allianz", Account::NATURE_CREDIT, 1, desc: "حساب شركه اليانز", is_seeding: true); //1
        Account::newAccount(400, "Buffet", Account::NATURE_CREDIT, 2, desc: "حساب مصاريف البوفيه", is_seeding: true); //2
        Account::newAccount(32, "HQ Salaries", Account::NATURE_DEBIT, 2, desc: "حساب مصاريف البوفيه", is_seeding: true); //3
        Account::newAccount(
            6,
            "Sugar",
            Account::NATURE_CREDIT,
            main_account_id: 2, //Costs
            parent_account_id: 2, //Buffet
            is_seeding: true
        ); //4
        Account::newAccount(
            16,
            "Tea",
            Account::NATURE_CREDIT,
            main_account_id: 2, //Costs
            parent_account_id: 2, //Buffet
            is_seeding: true
        ); //5
        Account::newAccount(400, "Cash", Account::NATURE_DEBIT, 3, desc: "حساب مصاريف البوفيه", is_seeding: true); //6
        Account::newAccount(
            61,
            "Main Cash",
            Account::NATURE_DEBIT,
            main_account_id: 3, //Cash
            parent_account_id: 6, //Cash
            is_seeding: true
        ); //7
        Account::newAccount(
            62,
            "Buffet Cash",
            Account::NATURE_DEBIT,
            main_account_id: 3, //Cash
            parent_account_id: 6, //Cash
            is_seeding: true
        ); //8

        EntryTitle::newEntry("دفع مرتبات", "Paying salaries each month", [
            7 => [ //Main Cash
                'nature'    =>  Account::NATURE_CREDIT,
            ],
            3 => [ //Salaries
                'nature'    =>  Account::NATURE_DEBIT,
                'limit'     =>  20000
            ]
        ]); //1

        EntryTitle::newEntry("مستلزمات مطبخ", "Paying kitchen expenses", [
            6 => [ //Cash or any of it's children
                'nature'    =>  Account::NATURE_CREDIT,
                'limit'     =>  5000
            ],
            2 => [ //Buffet or any of it's children
                'nature'    =>  Account::NATURE_DEBIT,
            ]
        ]); //2


        JournalEntry::newJournalEntry(
            entry_title_id: 1,
            cash_entry_type: JournalEntry::CASH_ENTRY_RECEIVED,
            receiver_name: "مينا نبيل",
            comment: "Testing",
            user_id: 10,
            is_seeding: true,
            // revert_entry_id: ,
            // approved_at: ,
            // approver_id: ,
            accounts: [
                8 => [
                    'nature'    =>  Account::NATURE_CREDIT,
                    'amount'    => 440,
                    'currency' => JournalEntry::CURRENCY_EGP,
                ],
                4 => [
                    'nature'    =>  Account::NATURE_DEBIT,
                    'amount'    => 240,
                    'currency' => JournalEntry::CURRENCY_EGP,
                ],
                5 => [
                    'nature'    =>  Account::NATURE_DEBIT,
                    'amount'    => 200,
                    'currency' => JournalEntry::CURRENCY_EGP,
                ],

            ],
        );
    }
}
