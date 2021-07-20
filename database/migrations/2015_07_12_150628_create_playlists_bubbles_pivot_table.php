<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsBubblesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists_bubbles_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('playlist_id')->unsigned();
            $table->integer('bubble_id')->unsigned();
            $table->integer('condition_id')->unsigned();
            $table->integer('order');
            $table->longText('settings');
            $table->timestamps();
            
            $table->index('playlist_id');
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
        Schema::drop('playlists_bubbles_pivot');
    }
}
