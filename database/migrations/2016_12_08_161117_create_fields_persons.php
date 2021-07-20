<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields_persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('external_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->smallInteger('birth_year')->unsigned();
            $table->smallInteger('death_year')->unsigned();
            $table->string('name');
            $table->string('order');
            $table->timestamps();
            
            $table->index('namespace');
            $table->index('external_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields_persons');
    }
}
