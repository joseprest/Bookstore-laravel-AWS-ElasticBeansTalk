<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('external_id');
            $table->string('name');
            $table->string('address');
            $table->string('postalcode', 20);
            $table->string('city', 100);
            $table->string('region', 100);
            $table->string('country', 100);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
            
            $table->index('namespace');
            $table->index('external_id');
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields_locations');
    }
}
