<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagGroupsTable extends Migration
{

    public function up()
    {
        Schema::create('tagging_tag_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 255)->index();
            $table->string('name', 255);
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('tagging_tag_groups');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
