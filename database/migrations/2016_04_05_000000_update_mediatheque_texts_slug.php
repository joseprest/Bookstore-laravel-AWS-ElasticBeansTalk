<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediathequeTextsSlug extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('mediatheque.table_prefix').'texts', function (Blueprint $table) {
            //$table->dropUnique(config('mediatheque.table_prefix').'texts_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('mediatheque.table_prefix').'texts', function (Blueprint $table) {
            //$table->unique('slug');
        });
    }
}
