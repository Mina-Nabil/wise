<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::newUser("remon", "Remon", "Saleh", User::TYPE_ADMIN, "remon@wise");
        User::newUser("minabil", "Mina", "Nabil", User::TYPE_ADMIN, "mina@wise", "mina9492@hotmail.com", "01225212014");
        User::newUser("michael", "Michael", "Hani", User::TYPE_ADMIN, "michael@wise");
    }
}
