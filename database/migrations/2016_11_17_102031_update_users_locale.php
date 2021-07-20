<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersLocale extends Migration
{
    /**
     * Adds a 'locale' column.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('locale')
                ->after('password');
        });
    }

    /**
     * Removes the 'locale' column
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('locale');
        });
    }
}
