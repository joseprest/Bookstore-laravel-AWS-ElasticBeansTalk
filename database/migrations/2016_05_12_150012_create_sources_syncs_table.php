<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources_syncs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')->unsigned();
            $table->longText('state');
            $table->tinyInteger('started')->unsigned();
            $table->tinyInteger('finished')->unsigned();
            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->timestamps();
            
            $table->index('source_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sources_syncs');
    }
}
