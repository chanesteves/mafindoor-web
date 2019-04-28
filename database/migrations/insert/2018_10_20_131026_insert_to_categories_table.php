<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        if (!DB::table('categories')->where(array('name' => 'Shop'))->first())
            DB::table('categories')->where(array('name' => 'Shop'))->insert(array('name' => 'Shop', 'icon' => 'shop', 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('categories')->where(array('name' => 'Food'))->first())
            DB::table('categories')->where(array('name' => 'Food'))->insert(array('name' => 'Food', 'icon' => 'food', 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('categories')->where(array('name' => 'Services'))->first())
            DB::table('categories')->where(array('name' => 'Services'))->insert(array('name' => 'Services', 'icon' => 'services', 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('categories')->where(array('name' => 'Entertainment'))->first())
            DB::table('categories')->where(array('name' => 'Entertainment'))->insert(array('name' => 'Entertainment', 'icon' => 'entertainment', 'created_at' => $now, 'updated_at' => $now));

        if (!DB::table('categories')->where(array('name' => 'Others'))->first())
            DB::table('categories')->where(array('name' => 'Others'))->insert(array('name' => 'Others', 'icon' => 'others', 'created_at' => $now, 'updated_at' => $now));
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
