<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsLocationsMorphPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields_locations_morph_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->unsigned();
            $table->string('field_name');
            $table->morphs('fieldable');
            $table->string('fieldable_position');
            $table->integer('fieldable_order')->unsigned();
            $table->timestamps();
            
            $table->index('field_id');
            $table->index('field_name');
            $table->index('fieldable_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields_locations_morph_pivot');
    }
}
