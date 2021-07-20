<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('external_id');
            $table->string('name');
            $table->timestamps();
            
            $table->index('namespace');
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields_categories');
    }
}
