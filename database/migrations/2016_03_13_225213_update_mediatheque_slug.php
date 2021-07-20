<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediathequeSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*$tables = ['metadatas', 'texts', 'pictures', 'audios', 'videos'];
        foreach($tables as $tableName)
        {
            Schema::table(config('mediatheque.table_prefix').$tableName, function(Blueprint $table) use ($tableName)
    		{
    			$table->dropUnique(config('mediatheque.table_prefix').$tableName.'_slug_unique');
                $table->index('slug');
    		});
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*$tables = ['metadatas', 'texts', 'pictures', 'audios', 'videos'];
        foreach($tables as $tableName)
        {
            Schema::table(config('mediatheque.table_prefix').$tableName, function(Blueprint $table) use ($tableName)
    		{
    			$table->dropIndex(config('mediatheque.table_prefix').$tableName.'_slug_index');
                $table->unique('slug');
    		});
        }*/
    }
}
