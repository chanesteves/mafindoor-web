<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('origin_point_id')->default(0);
            $table->integer('destination_point_id')->default(0);
            $table->integer('origin_annotation_id')->default(0);
            $table->integer('destination_annotation_id')->default(0);
            $table->string('via')->nullable();
            $table->string('reason')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('no_routes');
    }
}
