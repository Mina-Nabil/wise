<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Users\User::factory(10)->create();

        // \App\Models\Users\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UsersSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(CarsSeeder::class);
        $this->call(ProfessionsSeeder::class);
        if (App::environment('local')) {
            $this->call(InsuranceSeeder::class);
            // $this->call(TaskSeeder::class);
            // $this->call(CustomersSeeder::class);
            // $this->call(CorporatesSeeder::class);
            $this->call(SoldPolicyFileSeeder::class);

        } else {
            $this->call(ProdInsuranceSeeder::class);
        }
    }
}
