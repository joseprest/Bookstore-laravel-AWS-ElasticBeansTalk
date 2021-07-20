<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanneauBubblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bubbles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('handle');
            $table->longText('settings');
            $table->boolean('published');
            $table->timestamps();
            
            $table->index('handle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bubbles');
    }
}
