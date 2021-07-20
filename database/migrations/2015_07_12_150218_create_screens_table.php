<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('organisation_id', 255);
            $table->string('auth_code', 255);
            $table->string('uuid', 255);
            $table->string('name', 255);
            
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
            
            $table->index('auth_code');
            $table->index('uuid');
            $table->index('name');
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
        Schema::drop('screens');
    }
}
