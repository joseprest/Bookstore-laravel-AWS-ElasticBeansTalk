<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBubblesSourceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bubbles', function (Blueprint $table) {
            $table->integer('source_id')->unsigned()->after('handle');
            $table->index('source_id');
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
            $table->dropColumn('source_id');
        });
    }
}
