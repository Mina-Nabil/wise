<?php

use App\Models\Insurance\PolicyBenefit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $benefits = implode("','", PolicyBenefit::BENEFITS);
        DB::statement("ALTER TABLE policy_benefits MODIFY COLUMN benefit ENUM('{$benefits}') NOT NULL");
        DB::statement("ALTER TABLE sold_benefits MODIFY COLUMN benefit ENUM('{$benefits}') NOT NULL");
    }

    public function down()
    {
        $benefits = implode("','", array_slice(PolicyBenefit::BENEFITS, 0, -6));
        DB::statement("ALTER TABLE policy_benefits MODIFY COLUMN benefit ENUM('{$benefits}') NOT NULL");
        DB::statement("ALTER TABLE sold_benefits MODIFY COLUMN benefit ENUM('{$benefits}') NOT NULL");
    }
};
