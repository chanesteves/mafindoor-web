<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        if (!DB::table('roles')->where(array('name' => 'Administrator'))->first())
            DB::table('roles')->insert(array('name' => 'Administrator', 'code' => '000', 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('roles')->where(array('name' => 'Visitor'))->first())
            DB::table('roles')->insert(array('name' => 'Visitor', 'code' => '001', 'created_at' => $now, 'updated_at' => $now));
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
}
