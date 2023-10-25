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
        User::newUser("angel.magdy", "Angel", "Magdy", User::TYPE_ADMIN, "angel.magdy");
        User::newUser("hani.attia", "Hany", "Attia", User::TYPE_ADMIN, "hani.attia");
        User::newUser("mariam.magdy", "Mariam", "Magdy", User::TYPE_ADMIN, "mariam.magdy");
        User::newUser("mina.adel", "Mina", "Adel", User::TYPE_ADMIN, "mina.adel");
        User::newUser("sandy.bassem", "Sandy", "Bassem", User::TYPE_ADMIN, "sandy.bassem");
        User::newUser("shaimaa.youssef", "Shaimaa", "Youssef", User::TYPE_ADMIN, "shaimaa.youssef");
        User::newUser("shrouk.alashry", "Shrouk", "Al Ashry", User::TYPE_ADMIN, "shrouk.alashry");
        User::newUser("youmna.soliman", "Youmna", "Soliman", User::TYPE_ADMIN, "youmna.soliman");
        User::newUser("minabil", "Mina", "Nabil", User::TYPE_ADMIN, "mina@wise", "mina9492@hotmail.com", "01225212014");
        User::newUser("michael", "Michael", "Hani", User::TYPE_ADMIN, "michael@wise");
    }
}
