<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration
{

    public function up()
    {
        Schema::create('tagging_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 255)->index();
            $table->string('name', 255);
            $table->boolean('suggest')->default(false);
            $table->integer('count')->unsigned()->default(0); // count of how many times this tag was used
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('tagging_tags');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
