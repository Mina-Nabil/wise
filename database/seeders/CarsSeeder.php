<?php

namespace Database\Seeders;

use App\Models\Cars\Car;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Car::importData(resource_path('import/cars_sheet.xlsx'));
            $cars = Car::count();
            $this->command->info($cars . " cars loaded");
        } catch (FileNotFoundException $e) {
            report($e);
            $this->command->warn("Unable to read file");
        } catch (Exception $e) {
            report($e);
            $this->command->error($e->getMessage());
        }
    }
}
