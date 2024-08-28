<?php

namespace Database\Seeders;

use App\Models\Accounting\Account;
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
        MainAccount::newMainAccount("Clients", MainAccount::TYPE_ASSET, "حساب عملاء", true);
        MainAccount::newAccountType("Costs", MainAccount::TYPE_EXPENSE, "مصاريف", true);
        MainAccount::newMainAccount("Cash", MainAccount::TYPE_ASSET, "خزنه الشركه", true);
        Account::newAccount("Allianz", Account::NATURE_CREDIT, 1, 50000, "حساب شركه اليانز", true);
        Account::newAccount("Shai w Cake", Account::NATURE_DEBIT, 2, 5000, "حساب مصاريف البوفيه", true);

        JournalEntry::newJournalEntry("فاتوره ٢٠٠٠", 21212.12, 3, 1, JournalEntry::CURRENCY_EGP, null, null, null, comment: "Testing entry", cash_entry_type: JournalEntry::CASH_ENTRY_RECEIVED, receiver_name: "Angel");
    }
}
