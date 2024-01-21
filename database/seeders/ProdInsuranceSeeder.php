<?php

namespace Database\Seeders;

use App\Models\Customers\Customer;
use App\Models\Insurance\Company;
use App\Models\Insurance\Policy;
use App\Models\Insurance\PolicyBenefit;
use App\Models\Insurance\PolicyCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdInsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allianz = Company::newCompany("Allianz");
        if (!$allianz) return;

        $plus = Policy::newPolicy($allianz->id, "Motor Plus", Policy::BUSINESS_PERSONAL_MOTOR);
        $one = Policy::newPolicy($allianz->id, "Motor One", Policy::BUSINESS_PERSONAL_MOTOR);
        if (!$plus || !$one) return;

        //ALFA Romio - by brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 1, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 1, 4.229);

        //Aston Martin - by brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 2, 2.35);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 2, 2.961);

        //Audi - by brand & models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 21, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 21, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 22, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 22, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 26, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 26, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 27, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 27, 4.464);
        ///Audi brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 5, 2.35);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 5, 2.961);

        ///BMW by brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 10, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 10, 3.195);

        //BRILLIANCE by brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 7, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 7, 7.002);

        //Chevorlet by brand & models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 156, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 156, 6.015);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 155, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 155, 6.015);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 152, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 152, 6.015);

        //Chevorlet brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 25, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 25, 3.759);

        //Citroen by brand and models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 106, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 106, 6.015);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 111, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 111, 6.015);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 18, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 18, 3.759);

        //Fiat by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 173, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 173, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 174, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 174, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 175, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 175, 3.759);
        ///brands
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 29, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 29, 7.002);

        //Ford by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 161, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 161, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 163, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 163, 3.195);
        ///brands
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 26, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 26, 6.015);

        //Honda brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 36, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 36, 3.195);

        //Hyundai by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 254, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 254, 3.195);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 251, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 251, 3.759);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 37, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 37, 6.015);

        //Jaguar brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 14, 2.35);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 14, 2.961);

        //Jeep by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 97, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 97, 3.759);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 15, 2.35);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 15, 2.961);



        //KIA by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 179, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 179, 3.195);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 182, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 182, 3.195);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 184, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 184, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 183, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 183, 4.464);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 30, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 30, 6.015);

        //LADA brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 31, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 31, 4.464);

        //Land Rover brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 19, 2.350);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 19, 2.961);

        //MAZDA by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 32, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 32, 4.464);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 19, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 19, 6.015);

        //Mercedes brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 33, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 33, 3.195);

        //MG by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 11, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 11, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 12, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 12, 3.759);
        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 3, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 3, 6.015);

        //Mini brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 38, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 38, 3.759);

        //Mitsubishi by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 229, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 229, 6.015);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 230, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 230, 6.015);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 34, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 34, 4.464);

        //NISSAN by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 233, 2.350);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 233, 2.961);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 234, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 234, 4.229);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 237, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 237, 4.229);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 236, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 236, 6.015);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 35, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 35, 4.464);

        //OPEL by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 14, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 14, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 19, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 19, 4.464);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 18, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 18, 6.015);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 4, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 4, 3.759);

        //Peugeot by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 78, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 78, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 79, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 79, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 81, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 81, 3.759);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 82, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 82, 4.464);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 12, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 12, 7.002);

        //Porche brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 9, 2.350);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 9, 2.961);

        //Proton brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 6, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 6, 7.002);

        //Renault by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 98, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 98, 4.229);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 102, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 102, 4.464);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 16, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 16, 6.015);

        //SANG YONG brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 17, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 17, 7.002);

        //SIAT by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 144, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 144, 6.015);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 138, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 138, 7.002);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 141, 4.229);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 141, 6.015);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 23, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 23, 3.759);

        //SKODA by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 121, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 121, 4.464);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 20, 2.444);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 20, 3.195);

        //Subaro brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 21, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 21, 4.229);

        //Suzuki brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 22, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 22, 7.002);

        //Toyota by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 89, 2.35);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 89, 2.961);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 84, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 84, 4.229);
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 88, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 88, 4.229);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 13, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 13, 4.464);


        //VOLKS by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 169, 2.961);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 169, 4.229);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 28, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 28, 4.464);


        //VOLKS by brand & models
        ///models
        $plus->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 169, 3.289);
        $one->addCondition(PolicyCondition::SCOPE_MODEL, PolicyCondition::OP_EQUAL, 169, 4.464);

        ///brand
        $plus->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 27, 2.82);
        $one->addCondition(PolicyCondition::SCOPE_BRAND, PolicyCondition::OP_EQUAL, 27, 3.759);


        ///COUNTRIES
        $plus->addCondition(PolicyCondition::SCOPE_COUNTRY, PolicyCondition::OP_EQUAL, 27, 4.652);
        $one->addCondition(PolicyCondition::SCOPE_COUNTRY, PolicyCondition::OP_EQUAL, 27, 7.002);

        ////BENEFITS
        $plus->addBenefit(PolicyBenefit::BENEFIT_RISKS, "الانقلاب العرضي - التصادم الخاجي - الحريق / الانفجار -الاشتعال الذاتي - السرقه / السطو");
        $one->addBenefit(PolicyBenefit::BENEFIT_RISKS, "جميع الأخطار بخلاف ماهو مستثنى صراحة بنص الوثيقة");

        $plus->addBenefit(PolicyBenefit::BENEFIT_WORKSHOPS, "الإصلاح لدى الوكيل و قائمة مراكز الخدمة المتعاقدة مع أليانز أو أي مركز خدمة مفضل");
        $one->addBenefit(PolicyBenefit::BENEFIT_WORKSHOPS, "الإصلاح لدى الوكيل و قائمة مراكز الخدمة المتعاقدة مع أليانز أو أي مركز خدمة مفضل");

        $plus->addBenefit(PolicyBenefit::BENEFIT_COVER_PERCENTAGE, "بدون تحمل اول 3 سنوات من بداية الترخيص");
        $one->addBenefit(PolicyBenefit::BENEFIT_COVER_PERCENTAGE, "لا تطبق");

        $plus->addBenefit(PolicyBenefit::BENEFIT_MANDATORY_COVER, "بدون تحمل اجباري");
        $one->addBenefit(PolicyBenefit::BENEFIT_MANDATORY_COVER, "بدون تحمل اجباري");

        $plus->addBenefit(PolicyBenefit::BENEFIT_APPLIED_DISCOUNT, "التجديد الاول 10% -التجديد الثاني 20% -التجديد الثالث 30%-التجديد الرابع 40%-التجديد الخامس 50%");
        $one->addBenefit(PolicyBenefit::BENEFIT_APPLIED_DISCOUNT, "التجديد الاول 10% -التجديد الثاني 20% -التجديد الثالث 30%");

        $plus->addBenefit(PolicyBenefit::BENEFIT_ONROAD_HELP, "مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_ONROAD_HELP, "مغطى");

        $plus->addBenefit(PolicyBenefit::BENEFIT_PERSONAL_ACCIDENT, "مغطى بحد اقصى  400,000جنيه مصري");
        $one->addBenefit(PolicyBenefit::BENEFIT_PERSONAL_ACCIDENT, "مغطى بحد اقصى 500,000جنيه مصري");

        $plus->addBenefit(PolicyBenefit::BENEFIT_OTHER_CARS_ASSISTANCE, "مغطى بحد اقصى 150,000جنيه مصري");
        $one->addBenefit(PolicyBenefit::BENEFIT_OTHER_CARS_ASSISTANCE, "مغطى بحد اقصى  250,000جنيه مصري");

        $plus->addBenefit(PolicyBenefit::BENEFIT_POLICE_FILE, "للحوادث التي تتعدى 150,000 ج(وفي حالات السرقة و الهلاك الكلي)");
        $one->addBenefit(PolicyBenefit::BENEFIT_POLICE_FILE, "غير مطالب في حالة الحوادث ب استثناء(السرقة و الهلاك الكلي)");

        $plus->addBenefit(PolicyBenefit::BENEFIT_NATURAL_DISASTER, "غير مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_NATURAL_DISASTER, "مغطى");

        $plus->addBenefit(PolicyBenefit::BENEFIT_STRIKES, "غير مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_STRIKES, "مغطى");

        $plus->addBenefit(PolicyBenefit::BENEFIT_CAR_KEYS_LOSS, "مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_CAR_KEYS_LOSS, "مغطى");

        $plus->addBenefit(PolicyBenefit::BENEFIT_MARKET_PRICE_DIFF, "غير مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_MARKET_PRICE_DIFF, "مغطى");

        $plus->addBenefit(PolicyBenefit::BENEFIT_SPARE_CAR, "غير مغطى");
        $one->addBenefit(PolicyBenefit::BENEFIT_SPARE_CAR, "مغطى");
        ////

        Customer::newLead("Hossam", "Ashmawy", "01151687560", "Hassan Ragab", "حسام", "حسن رجب", "عشماوى");
        Customer::newLead("Hany", "Hassan", "01069086683", "Mohamad Abbas", "هاني", "محمد عباس", "حسن");
        Customer::newLead("Ahmed", "Lotfy", "01222193172");
        Customer::newLead("Veronia", "Raafat", "01200058134");

    }
}
