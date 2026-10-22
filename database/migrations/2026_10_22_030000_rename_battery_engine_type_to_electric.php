<?php

use App\Models\Cars\Car;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('cars', 'engine_type')) {
            DB::statement("ALTER TABLE cars MODIFY engine_type ENUM('Petrol','Battery','Electric','REEV','Hybird') NULL");
            DB::table('cars')->where('engine_type', 'Battery')->update(['engine_type' => 'Electric']);
            $engines = implode("','", Car::ENGINE_TYPES);
            DB::statement("ALTER TABLE cars MODIFY engine_type ENUM('{$engines}') NULL");
        }

        $valueColumn = DB::selectOne("SHOW COLUMNS FROM policy_conditions WHERE Field = 'value'");
        if ($valueColumn && preg_match('/char|text/i', $valueColumn->Type)) {
            DB::table('policy_conditions')
                ->where('scope', 'engine_type')
                ->where('value', 'Battery')
                ->update(['value' => 'Electric']);
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('cars', 'engine_type')) {
            return;
        }

        DB::statement("ALTER TABLE cars MODIFY engine_type ENUM('Petrol','Battery','Electric','REEV','Hybird') NULL");
        DB::table('cars')->where('engine_type', 'Electric')->update(['engine_type' => 'Battery']);
        DB::statement("ALTER TABLE cars MODIFY engine_type ENUM('Petrol','Battery','REEV','Hybird') NULL");

        $valueColumn = DB::selectOne("SHOW COLUMNS FROM policy_conditions WHERE Field = 'value'");
        if ($valueColumn && preg_match('/char|text/i', $valueColumn->Type)) {
            DB::table('policy_conditions')
                ->where('scope', 'engine_type')
                ->where('value', 'Electric')
                ->update(['value' => 'Battery']);
        }
    }
};
