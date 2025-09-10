<?php

use App\Models\Users\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the ENUM column to include the new 'crm' type
        DB::statement("ALTER TABLE users MODIFY COLUMN `type` ENUM('" . implode("','", User::TYPES) . "')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove 'crm' from the ENUM - get all types except CRM
        $typesWithoutCrm = array_filter(User::TYPES, function($type) {
            return $type !== User::TYPE_CRM;
        });
        
        DB::statement("ALTER TABLE users MODIFY COLUMN `type` ENUM('" . implode("','", $typesWithoutCrm) . "')");
    }
};
