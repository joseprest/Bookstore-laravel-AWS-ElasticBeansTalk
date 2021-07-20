<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsBubblesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels_bubbles_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned();
            $table->integer('bubble_id')->unsigned();
            $table->integer('order');
            $table->longText('settings');
            $table->timestamps();
            
            $table->index('channel_id');
            $table->index('bubble_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('channels_bubbles_pivot');
    }
}
