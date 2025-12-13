<?php

namespace App\Console\Commands;

use App\Models\Accounting\Account;
use Illuminate\Console\Command;

class RefreshAccountCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:refresh_codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all account codes based on created_at ordering';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting account code refresh for all accounts...');
        $this->newLine();

        $result = Account::refreshAllCodes();

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
            $this->info('Account code refresh completed successfully!');
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
