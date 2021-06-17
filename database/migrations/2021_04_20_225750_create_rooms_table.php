<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('game');
            $table->string('password')->nullable();
            $table->string('ready');
            $table->string('status');
            $table->bigInteger('id_user1')->unsigned();
            $table->foreign('id_user1')->references('id')->on('users');
            $table->bigInteger('id_user2')->unsigned()->nullable();
            $table->foreign('id_user2')->references('id')->on('users');
            $table->bigInteger('id_user3')->unsigned()->nullable();
            $table->foreign('id_user3')->references('id')->on('users');
            $table->bigInteger('id_user4')->unsigned()->nullable();
            $table->foreign('id_user4')->references('id')->on('users');
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
        Schema::dropIfExists('rooms');
    }
}
