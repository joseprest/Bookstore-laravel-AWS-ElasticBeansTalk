<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationsInvitations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations_invitations', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('organisation_id')->unsigned();
            $table->string('invitation_key');
            $table->integer('user_id')->unsigned();
            $table->string('email');
            $table->integer('user_role_id')->unsigned();
            $table->timestamps();
            
            $table->index('organisation_id');
            $table->index('user_id');
            $table->index('invitation_key');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organisations_invitations');
    }
}
