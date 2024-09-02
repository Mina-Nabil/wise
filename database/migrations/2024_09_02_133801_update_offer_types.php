<?php

use App\Models\Insurance\Policy;
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
        DB::statement("ALTER TABLE policies MODIFY COLUMN business ENUM('" . implode("','", Policy::LINES_OF_BUSINESS) . "')");
        DB::statement("ALTER TABLE offers MODIFY COLUMN `type` ENUM('" . implode("','", Policy::LINES_OF_BUSINESS) . "')");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
