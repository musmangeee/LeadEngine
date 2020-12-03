<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderIdToTableApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->bigInteger('provider_id')->unsigned()->after('id')->nullable();
            if(env('APP_ENV') !== 'testing'){
                $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            if(env('APP_ENV') !== 'testing'){
                $table->dropForeign(['provider_id']);
            }
            $table->dropColumn('provider_id');
        });
    }
}
