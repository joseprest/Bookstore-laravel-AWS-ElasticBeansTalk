<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanneauBubbleablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bubbleables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bubble_id')->unsigned();
            $table->morphs('bubbleable');
            $table->string('bubbleable_position', 50);
            $table->integer('bubbleable_order')->unsigned();
            $table->timestamps();

            $table->index('bubble_id');
            $table->index('bubbleable_id');
            $table->index('bubbleable_type');
            $table->index('bubbleable_position');
            $table->index('bubbleable_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bubbleables');
    }
}
