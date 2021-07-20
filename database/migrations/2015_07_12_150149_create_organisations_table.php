<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('description');
            $table->string('address');
            $table->string('city', 100);
            $table->string('country', 2);
            $table->string('region', 100);
            $table->string('postalcode', 20);
            $table->string('locale', 5);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organisations');
    }
}
