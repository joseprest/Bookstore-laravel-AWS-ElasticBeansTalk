<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePanneauBubblesNestingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bubbles', function (Blueprint $table) {
            $table->dropColumn('nesting_parent_id');
            $table->dropColumn('nesting_left');
            $table->dropColumn('nesting_right');
            $table->dropColumn('nesting_depth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bubbles', function (Blueprint $table) {
            $table->integer('nesting_parent_id')
                    ->nullable()
                    ->unsigned()
                    ->after('published');
            $table->integer('nesting_left')
                    ->nullable()
                    ->unsigned()
                    ->after('nesting_parent_id');
            $table->integer('nesting_right')
                    ->nullable()
                    ->unsigned()
                    ->after('nesting_left');
            $table->integer('nesting_depth')
                    ->nullable()
                    ->unsigned()
                    ->after('nesting_right');
                    
            $table->index('nesting_parent_id');
            $table->index('nesting_left');
            $table->index('nesting_right');
            $table->index('nesting_depth');
        });
    }
}
