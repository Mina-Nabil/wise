<?php

namespace App\Console\Commands;

use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use AWS\CRT\Log;
use Carbon\Carbon;
use Http\Client\Common\Plugin\Journal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as FacadesLog;

class FixForeignBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:fix_foreign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $for_accounts = Account::where('default_currency', '!=', 'EGP')->get();
        $this->info('Fixing foreign balance for ' . $for_accounts->count() . ' accounts');
        foreach ($for_accounts as $acc) {
            $entries = JournalEntry::where('journal_entries.created_at', '>=', Carbon::now()->subMonths(4))
                ->where('journal_entries.created_at', '<=', Carbon::now())
                ->join('entry_accounts', 'journal_entries.id', '=', 'entry_accounts.journal_entry_id')
                ->where('entry_accounts.account_id', $acc->id)
                ->select('journal_entries.*')
                ->groupBy('journal_entries.id')
                ->get();
            $balance = 0;
            $entries->each(function ($entry) use ($acc, &$balance) {
                $tmpAccEntry = $entry->accounts()->where('accounts.id', $acc->id)->first();
                if($tmpAccEntry){
                    FacadesLog::info($tmpAccEntry);
                    $balance += $tmpAccEntry->pivot->nature == $acc->nature ? $tmpAccEntry->pivot->currency_amount : -1 * $tmpAccEntry->pivot->currency_amount;
                    $entry->accounts()->updateExistingPivot($acc->id, ['account_foreign_balance' => $balance]);
                    
                }
          
            });
            $acc->foreign_balance = $balance;
            $acc->save();
            $this->info('Fixed foreign balance for ' . $acc->name . ' to ' . $acc->foreign_balance);
        }
        return Command::SUCCESS;
    }
}
