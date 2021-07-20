<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreensPingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens_pings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('screen_id')->unsigned();
            $table->longText('load');
            $table->longText('logs');
            $table->bigInteger('memory_total');
            $table->bigInteger('memory_free');
            $table->bigInteger('uptime');
            $table->bigInteger('timestamp');
            $table->timestamps();
            
            $table->index('screen_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('screens_pings');
    }
}
