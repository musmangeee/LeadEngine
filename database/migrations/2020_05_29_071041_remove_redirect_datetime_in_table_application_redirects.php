<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRedirectDatetimeInTableApplicationRedirects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_redirects', function (Blueprint $table) {
            $table->dropColumn('redirect_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_redirects', function (Blueprint $table) {
            $table->datetime('redirect_datetime')->nullable();
        });
    }
}
