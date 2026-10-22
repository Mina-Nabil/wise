<?php

use App\Models\Cars\Car;
use App\Models\Insurance\PolicyCondition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $engines = implode("','", Car::ENGINE_TYPES);
        DB::statement("ALTER TABLE cars ADD engine_type ENUM('{$engines}') NULL AFTER category");

        $scopes = implode("','", PolicyCondition::SCOPES);
        DB::statement("ALTER TABLE policy_conditions MODIFY COLUMN scope ENUM('{$scopes}') NOT NULL");
        DB::statement("ALTER TABLE policy_conditions MODIFY COLUMN value VARCHAR(255) NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE cars DROP COLUMN engine_type");

        $scopes = implode("','", array_values(array_diff(PolicyCondition::SCOPES, [PolicyCondition::SCOPE_ENGINE])));
        DB::statement("ALTER TABLE policy_conditions MODIFY COLUMN scope ENUM('{$scopes}') NOT NULL");
        DB::statement("ALTER TABLE policy_conditions MODIFY COLUMN value DOUBLE NOT NULL");
    }
};
