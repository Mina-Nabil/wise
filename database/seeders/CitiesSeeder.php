<?php

namespace Database\Seeders;

use App\Models\Base\Area;
use App\Models\Base\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::newCity('Cairo');
        City::newCity('Giza');
        City::newCity('Alexandria');
        City::newCity('Aswan');
        City::newCity('Assiut');
        City::newCity('Beheira');
        City::newCity('Beni Suef');
        City::newCity('Dakahlia');
        City::newCity('Damietta');
        City::newCity('Fayoum');
        City::newCity('Gharbia');
        City::newCity('Ismailia');
        City::newCity('Kafr el-Sheikh');
        City::newCity('Matrouh');
        City::newCity('Minya');
        City::newCity('Menofia');
        City::newCity('New Valley');
        City::newCity('North Sinai');
        City::newCity('Port Said');
        City::newCity('Qualyubia');
        City::newCity('Qena');
        City::newCity('Red Sea');
        City::newCity('Al-Sharqia');
        City::newCity('Sohag');
        City::newCity('South Sinai');
        City::newCity('Suez');
        City::newCity('Luxor');

        Area::newArea('Abbasia');
        Area::newArea('Ain Shams');
        Area::newArea('Al Daher');
        Area::newArea('Azbakeya');
        Area::newArea('Bab el Louq');
        Area::newArea('Boulaq');
        Area::newArea('Downtown');
        Area::newArea('El Manial');
        Area::newArea('El Marg');
        Area::newArea('El Mataryea');
        Area::newArea('El Qobbah');
        Area::newArea('Ezbet el Nakhl');
        Area::newArea('Faggala');
        Area::newArea('Fustat');
        Area::newArea('Garden City');
        Area::newArea('Heliopolis');
        Area::newArea('Helwan');
        Area::newArea('Maadi');
        Area::newArea('Masr El Gdeeda');
        Area::newArea('Madinet Nasr');
        Area::newArea('New Cairo');
        Area::newArea('Shubra');
        Area::newArea('Zamalek');
        Area::newArea('Zaiton');

        Area::newArea('Agouza');
        Area::newArea('Dokki');
        Area::newArea('Imbaba');
        Area::newArea('El Mohandeseen');
        Area::newArea('Haram');
        Area::newArea('Feisal');
        Area::newArea('Meet Okba');
        Area::newArea('Kitkat');
        Area::newArea('Moneeb');
        Area::newArea('Sheikh Zayed');
        Area::newArea('6th of October');
    }
}
