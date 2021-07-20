<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreensPlaylistsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens_playlists_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('screen_id')->unsigned();
            $table->integer('playlist_id')->unsigned();
            $table->integer('organisation_id')->unsigned();
            $table->integer('condition_id')->unsigned();
            $table->longText('settings');
            $table->timestamps();
            
            $table->index('screen_id');
            $table->index('organisation_id');
            $table->index('playlist_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('screens_playlists_pivot');
    }
}
