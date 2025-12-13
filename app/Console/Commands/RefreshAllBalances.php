<?php

namespace App\Console\Commands;

use App\Models\Accounting\JournalEntry;
use Illuminate\Console\Command;

class RefreshAllBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:refresh_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all account balances by recalculating from scratch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting balance refresh for all accounts...');
        $this->newLine();

        $result = JournalEntry::refreshAllBalances();

        if ($result['success']) {
            $this->info('✓ ' . $result['message']);
            $this->info('Accounts processed: ' . $result['accounts_processed']);

            if (!empty($result['errors'])) {
                $this->newLine();
                $this->warn('Errors encountered:');
                foreach ($result['errors'] as $error) {
                    $this->error('  - ' . $error);
                }
            }

            $this->newLine();
            $this->info('Balance refresh completed successfully!');
            return Command::SUCCESS;
        } else {
            $this->error('✗ ' . $result['message']);
            if (!empty($result['errors'])) {
                foreach ($result['errors'] as $error) {
                    $this->error('  - ' . $error);
                }
            }
            return Command::FAILURE;
        }
    }
}
