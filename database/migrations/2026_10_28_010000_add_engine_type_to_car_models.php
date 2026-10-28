<?php

use App\Models\Cars\Car;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $engines = implode("','", Car::ENGINE_TYPES);
        DB::statement("ALTER TABLE car_models ADD engine_type ENUM('{$engines}') NULL AFTER name");

        if (!Schema::hasColumn('cars', 'engine_type')) {
            return;
        }

        $agreed = DB::table('cars')
            ->select('car_model_id', DB::raw('MIN(engine_type) as engine_type'))
            ->whereNull('deleted_at')
            ->whereNotNull('engine_type')
            ->where('engine_type', '!=', '')
            ->groupBy('car_model_id')
            ->havingRaw('COUNT(DISTINCT engine_type) = 1')
            ->get();

        foreach ($agreed as $row) {
            DB::table('car_models')
                ->where('id', $row->car_model_id)
                ->update(['engine_type' => $row->engine_type]);
        }
    }

    public function down()
    {
        DB::statement('ALTER TABLE car_models DROP COLUMN engine_type');
    }
};
