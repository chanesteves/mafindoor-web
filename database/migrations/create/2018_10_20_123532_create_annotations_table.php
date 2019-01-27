<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annotations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('map_name')->default('');
            $table->string('map_marker')->nullable();
            $table->string('logo')->nullable();
            $table->double('longitude')->default(0);
            $table->double('latitude')->default(0);
            $table->double('min_zoom')->nullable();
            $table->double('max_zoom')->nullable();
            $table->integer('sub_category_id');
            $table->integer('floor_id');
            $table->string('slug')->default('');
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
        Schema::dropIfExists('annotations');
    }
}
