<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('source_id')->unsigned();
            $table->integer('source_sync_id')->unsigned();
            $table->string('source_job_key');
            $table->string('queue');
            $table->longText('payload');
            $table->tinyInteger('attempts')->unsigned();
            $table->tinyInteger('reserved')->unsigned();
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
            
            $table->index(['queue', 'reserved', 'reserved_at']);
            
            $table->index('source_id');
            $table->index('source_sync_id');
            $table->index('source_job_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sources_jobs');
    }
}
