<?php

use App\Models\Payments\ClientPayment;
use App\Models\Payments\CommProfile;
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
        DB::statement("ALTER TABLE client_payments MODIFY COLUMN type ENUM('" . implode("','", ClientPayment::PYMT_TYPES) . "')");
        Schema::table('client_payments', function (Blueprint $table) {
            $table->foreignIdFor(CommProfile::class, 'sales_out_id')->nullable()->constrained('comm_profiles')->nullOnDelete();
        });
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
