<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceCoordinatesWithPointInRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->integer('origin_point_id')->default(0)->after('id');
            $table->integer('destination_point_id')->default(0)->after('origin_point_id');
            $table->dropColumn('origin_lat');
            $table->dropColumn('origin_lng');
            $table->dropColumn('destination_lat');
            $table->dropColumn('destination_lng');
            $table->dropColumn('floor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->double('origin_lat')->default(0);
            $table->double('origin_lng')->default(0);
            $table->double('destination_lat')->default(0);
            $table->double('destination_lng')->default(0);
            $table->dropColumn('origin_point_id');
            $table->dropColumn('destination_point_id');
            $table->integer('floor_id');
        });
    }
}
