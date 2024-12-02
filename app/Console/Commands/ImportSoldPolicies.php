<?php

namespace App\Console\Commands;

use App\Models\Business\SoldPolicy;
use Illuminate\Console\Command;

class ImportSoldPolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sold-policies {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sold policies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch ($this->argument('id')) {
            case 1:
                # code...
                SoldPolicy::importData(resource_path('import/sold_policies_sheet.xlsx'));
                break;
            case 2:
                # code...
                SoldPolicy::importData2(resource_path('import/Book1.xlsx'));
                break;
        }
        return Command::SUCCESS;
    }
}
