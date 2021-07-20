<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreensCommands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens_commands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('screen_id')->unsigned();
            $table->string('command');
            $table->string('arguments', 1024);
            $table->longText('payload');
            $table->smallInteger('return_code');
            $table->longText('output');
            $table->timestamp('sended_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
            
            $table->index('screen_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('screens_commands');
    }
}
