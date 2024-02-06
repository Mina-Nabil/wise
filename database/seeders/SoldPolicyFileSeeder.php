<?php

namespace Database\Seeders;

use App\Models\Business\SoldPolicy;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoldPolicyFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            SoldPolicy::importData(resource_path('import/sold_policies_sheet.xlsx'));
            $soldPoliciesCount = SoldPolicy::count();
            $this->command->info($soldPoliciesCount . " policies loaded");
        } catch (FileNotFoundException $e) {
            report($e);
            $this->command->warn("Unable to read file");
        } catch (Exception $e) {
            report($e);
            $this->command->error($e->getMessage());
        }
    }
}
