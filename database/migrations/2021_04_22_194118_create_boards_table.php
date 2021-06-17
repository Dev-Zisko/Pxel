<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('box');
            $table->string('x');
            $table->string('y');
            $table->string('piececolor')->nullable();
            $table->string('number')->nullable();
            $table->string('validation')->nullable();
            $table->string('uno')->nullable();
            $table->string('dos')->nullable();
            $table->string('tres')->nullable();
            $table->string('cuatro')->nullable();
            $table->bigInteger('id_room')->unsigned();
            $table->foreign('id_room')->references('id')->on('rooms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
