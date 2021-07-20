<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreensCaches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('screen_id')->unsigned();
            $table->string('name');
            $table->integer('version')->unsigned();
            $table->timestamps();
            
            $table->index('screen_id');
            $table->index('name');
            $table->index('version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('screens_caches');
    }
}
