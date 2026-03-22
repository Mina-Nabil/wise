<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE customer_status MODIFY COLUMN status ENUM('new','qualified','rejected','client','in_active','not_qualified','no_answer') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE customer_status MODIFY COLUMN status ENUM('new','qualified','rejected','client','in_active') NOT NULL");
    }
};
