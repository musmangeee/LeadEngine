<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdToPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //only run this on development/production
        //on testing (using sqlite), adding increments after create table will throw error
        if (app()->environment() !== 'testing') {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->increments('id')->first();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->environment() !== 'testing') {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->dropColumn('id');
            });
        }
    }
}
