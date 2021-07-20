<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBubblesOrganisationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bubbles', function (Blueprint $table) {
            $table->integer('organisation_id')->unsigned()->nullable()->after('source_id');

            $table->index('organisation_id');
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
            $table->dropColumn('organisation_id');
        });
    }
}
