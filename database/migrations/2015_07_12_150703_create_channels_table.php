<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('handle');
            $table->boolean('notifications');
            $table->boolean('slideshow');
            
            $table->integer('nesting_parent_id')
                    ->nullable()
                    ->unsigned();
            $table->integer('nesting_left')
                    ->nullable()
                    ->unsigned();
            $table->integer('nesting_right')
                    ->nullable()
                    ->unsigned();
            $table->integer('nesting_depth')
                    ->nullable()
                    ->unsigned();
            
            $table->timestamps();
            
            $table->index('type');
            $table->unique('handle');
            $table->index('nesting_parent_id');
            $table->index('nesting_left');
            $table->index('nesting_right');
            $table->index('nesting_depth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('channels');
    }
}
