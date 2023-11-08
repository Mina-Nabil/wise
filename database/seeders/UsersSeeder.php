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
        User::newUser("remon", "Remon", "Saleh", User::TYPE_ADMIN, "remon@wise"); //1
        User::newUser("angel.magdy", "Angel", "Magdy", User::TYPE_OPERATIONS, "angel.magdy"); //2 -
        User::newUser("hani.attia", "Hany", "Attia", User::TYPE_ADMIN, "hani.attia"); //3
        User::newUser("mariam.magdy", "Mariam", "Magdy", User::TYPE_ADMIN, "mariam.magdy"); //4 -
        User::newUser("mina.adel", "Mina", "Adel", User::TYPE_OPERATIONS, "mina.adel"); //5
        User::newUser("sandy.bassem", "Sandy", "Bassem", User::TYPE_SALES, "sandy.bassem", null, null, 4); //6
        User::newUser("shaimaa.youssef", "Shaimaa", "Youssef", User::TYPE_SALES, "shaimaa.youssef", null, null, 4); //7 - 
        User::newUser("shrouk.alashry", "Shrouk", "Al Ashry", User::TYPE_SALES, "shrouk.alashry", null, null, 4); //8
        User::newUser("youmna.soliman", "Youmna", "Soliman", User::TYPE_OPERATIONS, "youmna.soliman"); //9
        User::newUser("minabil", "Mina", "Nabil", User::TYPE_ADMIN, "mina@wise", "mina9492@hotmail.com", "01225212014"); //10
        User::newUser("michael", "Michael", "Hani", User::TYPE_ADMIN, "michael@wise"); //11
    }
}
