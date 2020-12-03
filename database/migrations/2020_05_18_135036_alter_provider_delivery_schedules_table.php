<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProviderDeliverySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_delivery_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->time('start_at')->nullable()->change();
            $table->time('end_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_delivery_schedules', function (Blueprint $table) {
            $table->dropColumn(['provider_id']);
            $table->dateTime('start_at')->nullable()->change();
            $table->dateTime('end_at')->nullable()->change();
        });
    }
}
