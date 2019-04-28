<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('label')->default('');
            $table->string('map_url')->default('');
            $table->double('longitude')->default(0);
            $table->double('latitude')->default(0);
            $table->double('zoom')->default(0);
            $table->double('max_zoom')->default(0);
            $table->double('min_zoom')->default(0);
            $table->enum('status', ['pending', 'in progress', 'live'])->default('pending');
            $table->integer('building_id')->nullable();
            $table->string('slug')->default('');
            $table->integer('creator_id')->nullable();
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
        Schema::dropIfExists('floors');
    }
}
